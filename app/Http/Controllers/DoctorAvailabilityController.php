<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\DoctorAvailability;
use Carbon\Carbon;

class DoctorAvailabilityController extends Controller
{
    public function index($doctorId)
    {
        $doctor = Doctor::findOrFail($doctorId);

        $datesInMonth = [];
        $start = Carbon::now()->startOfMonth();
        $end   = Carbon::now()->endOfMonth();

        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            $datesInMonth[] = $date->format('Y-m-d');
        }

        $availabilities = DoctorAvailability::where('doctor_id', $doctorId)
            ->whereMonth('date', Carbon::now()->month)
            ->get()
            ->keyBy('date');

        return view('doctors.availability.index', compact('doctor', 'datesInMonth', 'availabilities'));
    }

    public function store(Request $request, $doctorId)
    {
        $request->validate([
            'start_time' => 'required|array',
            'end_time' => 'required|array',
            'is_leave' => 'nullable|array'
        ]);

        foreach ($request->start_time as $date => $start) {
            $dayOfWeek = Carbon::parse($date)->format('l');
            $availabilities = DoctorAvailability::where('doctor_id', $doctorId)
                ->whereMonth('date', Carbon::parse($date)->month)
                ->where('day_of_week', $dayOfWeek)
                ->get();

            foreach ($availabilities as $availability) {
                $availability->update([
                    'start_time' => $start,
                    'end_time' => $request->end_time[$date] ?? null,
                    'is_leave' => isset($request->is_leave[$date]) ? true : false
                ]);
            }
        }

        return redirect()->back()->with('success', 'Availability saved successfully.');
    }

    public function generateNextMonth($doctorId)
    {
        $currentMonthAvail = DoctorAvailability::where('doctor_id', $doctorId)
            ->whereMonth('date', Carbon::now()->month)
            ->get();

        $nextMonthStart = Carbon::now()->addMonth()->startOfMonth();
        $nextMonthEnd   = Carbon::now()->addMonth()->endOfMonth();

        for ($date = $nextMonthStart->copy(); $date->lte($nextMonthEnd); $date->addDay()) {
            $dayName = $date->format('l');
            $existing = $currentMonthAvail->firstWhere('day_of_week', $dayName);

            if ($existing) {
                DoctorAvailability::updateOrCreate(
                    [
                        'doctor_id' => $doctorId,
                        'date'      => $date->format('Y-m-d')
                    ],
                    [
                        'start_time' => $existing->start_time,
                        'end_time'   => $existing->end_time,
                        'is_leave'   => $existing->is_leave,
                        'day_of_week'=> $dayName
                    ]
                );
            }
        }

        return redirect()->back()->with('success', 'Next month schedule generated successfully.');
    }

    public function deleteMonth($doctorId)
    {
        DoctorAvailability::where('doctor_id', $doctorId)
            ->whereMonth('date', Carbon::now()->month)
            ->delete();

        return redirect()->back()->with('success', 'Current month schedule deleted.');
    }
}

