<?php

namespace App\Http\Controllers\Doctors;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Checkup;
use App\Models\DoctorAvailability;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DoctorDashboardController extends Controller
{
    /**
     * Show Doctor Dashboard
     */
    public function index()
    {
        // Logged-in doctor ID
        $doctorId = Auth::guard('doctor')->id();

        $today = Carbon::today()->toDateString();
        $next2Days = Carbon::today()->addDays(2)->toDateString();

        // ───────────── Assigned Patients ─────────────
        $assignedPatients = Patient::whereHas('checkups', function($q) use ($doctorId){
            $q->where('doctor_id', $doctorId);
        })->get();

        // ───────────── Today's Sessions ─────────────
        $todaySessions = Checkup::with('patient')
            ->where('doctor_id', $doctorId)
            ->whereDate('date', $today)
            ->get();

        $totalSessions = $todaySessions->count();
        $totalFee = $todaySessions->sum('fee');

        // ───────────── Next 2 Days Schedule ─────────────
        $nextSchedule = DoctorAvailability::where('doctor_id', $doctorId)
            ->whereDate('date', '>=', $today)
            ->whereDate('date', '<=', $next2Days)
            ->where('is_leave', false)
            ->orderBy('date')
            ->get();

        // ───────────── Pass data to Blade ─────────────
        return view('doctors.dashboard', compact(
            'assignedPatients',
            'todaySessions',
            'totalSessions',
            'totalFee',
            'nextSchedule'
        ));
    }
}
