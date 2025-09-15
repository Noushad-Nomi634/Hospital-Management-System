<?php

namespace App\Http\Controllers\Doctors;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Checkup;
use App\Models\DoctorAvailability;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DoctorDashboardController extends Controller
{
    public function index()
    {
        // Logged-in doctor ID (using doctor guard)
        $doctorId = Auth::guard('doctor')->id();

        $today = Carbon::today()->toDateString();
        $next2Days = Carbon::today()->addDays(2)->toDateString();

        // ───────────── Assigned Patients ─────────────
        $assignedPatients = Checkup::with('patient')
            ->where('doctor_id', $doctorId)
            ->get()
            ->map(fn($checkup) => $checkup->patient)
            ->filter() // remove null patients
            ->unique('id');

        // ───────────── Today's Sessions ─────────────
        $todaySessions = Checkup::with('patient')
            ->where('doctor_id', $doctorId)
            ->whereDate('date', $today)
            ->get();

        $totalFee = $todaySessions->sum('fee');
        $totalSessions = $todaySessions->count();

        // ───────────── Next 2 Days Schedule ─────────────
        $nextSchedule = DoctorAvailability::where('doctor_id', $doctorId)
            ->whereDate('date', '>=', $today)
            ->whereDate('date', '<=', $next2Days)
            ->where('is_leave', false)
            ->orderBy('date')
            ->get();

        // Pass data to Blade
        return view('doctors.dashboard', compact(
            'assignedPatients', 
            'todaySessions', 
            'totalFee', 
            'totalSessions', 
            'nextSchedule'
        ));
    }
}
