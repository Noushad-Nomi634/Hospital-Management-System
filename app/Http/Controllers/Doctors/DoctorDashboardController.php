<?php

namespace App\Http\Controllers\Doctors;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Checkup;
use App\Models\TreatmentSession;
use App\Models\DoctorAvailability;
use Carbon\Carbon;

class DoctorDashboardController extends Controller
{
    public function index()
    {
        $doctor = auth()->user(); // logged-in doctor
        $today = Carbon::today()->toDateString();

        // 1️⃣ Pending Consultations
        $pendingConsultationsCount = Checkup::where('doctor_id', $doctor->id)
            ->where('checkup_status', 'pending')
            ->count();

        // 2️⃣ Today Patients (unique patients with checkups today)
        $todayPatients = Checkup::where('doctor_id', $doctor->id)
            ->whereDate('created_at', $today)
            ->distinct('patient_id')
            ->count('patient_id');

        // 3️⃣ Today Sessions (all sessions scheduled for today)
        $todaySessions = TreatmentSession::where('doctor_id', $doctor->id)
            ->whereDate('session_date', $today)
            ->orderBy('session_date', 'asc')
            ->get();

        $todaySessionsCount = $todaySessions->count(); // total sessions today

        // 4️⃣ Next 2 Days Schedule
        $twoDaySchedule = DoctorAvailability::where('doctor_id', $doctor->id)
            ->whereDate('date', '>=', $today)
            ->whereDate('date', '<=', Carbon::today()->addDays(2))
            ->orderBy('date', 'asc')
            ->get();

        return view('doctors.dashboard', compact(
            'doctor',
            'pendingConsultationsCount',
            'todayPatients',
            'todaySessionsCount',
            'todaySessions',   // details for Blade loop
            'twoDaySchedule'
        ));
    }
}
