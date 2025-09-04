<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\DoctorAvailability;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class DoctorAvailabilityController extends Controller
{
    public function index($doctorId)
    {
        try {
            $doctor = Doctor::findOrFail($doctorId);

            $month = request('month', now()->month);
            $year  = request('year', now()->year);

            // All dates of the month
            $datesInMonth = collect(range(1, Carbon::create($year, $month)->daysInMonth))
                ->map(fn($day) => Carbon::create($year, $month, $day)->toDateString());

            // Fresh availabilities from DB
            $availabilities = DB::table('doctor_availabilities')
                ->where('doctor_id', $doctorId)
                ->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->get()
                ->keyBy('date');

            return view('doctors.availability.index', compact(
                'doctor', 'datesInMonth', 'availabilities', 'month', 'year'
            ));
        } catch (Exception $e) {
            Log::error('Error loading doctor availability: '.$e->getMessage());
            return redirect()->back()->with('error','Failed to load availability.');
        }
    }

    public function store(Request $request, $doctorId)
    {
        try {
            $request->validate([
                'start_time' => 'nullable|array',
                'end_time'   => 'nullable|array',
                'is_leave'   => 'nullable|array'
            ]);

            $allDates = collect($request->start_time ?? [])
                ->keys()
                ->merge(collect($request->end_time ?? [])->keys())
                ->unique();

            foreach ($allDates as $date) {
                $dayOfWeek = Carbon::parse($date)->format('l');

                $availability = DoctorAvailability::firstOrNew([
                    'doctor_id' => $doctorId,
                    'date'      => $date
                ]);

                if (isset($request->is_leave[$date])) {
                    $availability->start_time = null;
                    $availability->end_time   = null;
                    $availability->is_leave   = true;
                } else {
                    $availability->start_time = $request->start_time[$date] ?? null;
                    $availability->end_time   = $request->end_time[$date] ?? null;
                    $availability->is_leave   = false;
                }

                $availability->day_of_week = $dayOfWeek;
                $availability->save();
            }

            return redirect()->back()->with('success','Availability saved successfully.');
        } catch (Exception $e) {
            Log::error('Error saving doctor availability: '.$e->getMessage());
            return redirect()->back()->with('error','Failed to save availability.');
        }
    }

    public function generateNextMonth($doctorId)
    {
        try {
            $currentMonthAvail = DoctorAvailability::where('doctor_id', $doctorId)
                ->whereMonth('date', now()->month)
                ->get()
                ->keyBy('day_of_week');

            $nextMonthStart = now()->addMonth()->startOfMonth();
            $nextMonthEnd   = now()->addMonth()->endOfMonth();

            for ($date = $nextMonthStart->copy(); $date->lte($nextMonthEnd); $date->addDay()) {
                $dayName = $date->format('l');
                $source  = $currentMonthAvail[$dayName] ?? null;

                DoctorAvailability::updateOrCreate(
                    [
                        'doctor_id' => $doctorId,
                        'date'      => $date->format('Y-m-d')
                    ],
                    [
                        'start_time' => $source?->start_time,
                        'end_time'   => $source?->end_time,
                        'is_leave'   => $source?->is_leave ?? false,
                        'day_of_week'=> $dayName
                    ]
                );
            }

            // Redirect automatically to next month view
            return redirect()->route('doctors.availability.index', [
                'doctor' => $doctorId,
                'month'  => now()->addMonth()->month,
                'year'   => now()->addMonth()->year
            ])->with('success','Next month schedule generated successfully.');
        } catch (Exception $e) {
            Log::error('Error generating next month schedule: '.$e->getMessage());
            return redirect()->back()->with('error','Failed to generate next month schedule.');
        }
    }

    public function deleteMonth($doctorId)
    {
        try {
            $month = now()->month;
            $year  = now()->year;

            DB::table('doctor_availabilities')
                ->where('doctor_id', $doctorId)
                ->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->delete();

            return redirect()->route('doctors.availability.index', [
                'doctor' => $doctorId
            ])->with('success','Current month schedule deleted successfully.');
        } catch (Exception $e) {
            Log::error('Error deleting month schedule: '.$e->getMessage());
            return redirect()->back()->with('error','Failed to delete schedule.');
        }
    }
}
