<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Checkup;
use App\Models\TreatmentSession;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // echo "<pre>";
        // print_r(auth()->user());
        // echo "</pre>";

        // ✅ Total counts
        $totalDoctors = Doctor::count();
        $totalPatients = Patient::count();
        $totalCheckups = Checkup::count();

        // ✅ Aaj ke sessions ka count (session_date pe filter)
        $totalSessionsToday = TreatmentSession::whereDate('session_date', Carbon::today())->count();

        // ✅ Aaj ke checkups ki payment (checkup tabhi hota hai jab create hota hai, is liye created_at sahi hai)
        $checkupPaymentsToday = Checkup::whereDate('created_at', Carbon::today())->sum('fee');

        // ✅ Aaj ke sirf paid sessions ki payment (session_date pe filter)
        $sessionPaymentsToday = TreatmentSession::whereDate('session_date', Carbon::today())
            ->where('payment_status', 'paid')
            ->sum('session_fee');

        // ✅ Aaj ki total paid payment (checkup + sessions)
        $totalPaymentsToday = $checkupPaymentsToday + $sessionPaymentsToday;

        return view('dashboard', compact(
            'totalDoctors',
            'totalPatients',
            'totalCheckups',
            'totalSessionsToday',
            'totalPaymentsToday'
        ));
    }
}
