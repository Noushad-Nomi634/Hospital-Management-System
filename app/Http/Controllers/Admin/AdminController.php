<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Checkup;
use App\Models\TreatmentSession;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        $branches = Branch::all();

        $branchStats = $branches->map(function ($branch) {

            // ────────────── Total Doctors, Patients, Checkups ──────────────
            $totalDoctors  = Doctor::where('branch_id', $branch->id)->count();
            $totalPatients = Patient::where('branch_id', $branch->id)->count();
            $totalCheckups = Checkup::where('branch_id', $branch->id)->count();

            // ────────────── Sessions Today (Patient Branch ke hisaab se) ──────────────
            $sessionsTodayQuery = TreatmentSession::whereHas('patient', function ($q) use ($branch) {
                    $q->where('branch_id', $branch->id);
                })
                ->where(function ($query) {
                    $query->whereDate('session_date', Carbon::today())
                          ->orWhere(function ($q2) {
                              $q2->whereNull('session_date')
                                 ->whereDate('created_at', Carbon::today());
                          });
                });

            $totalSessionsToday = $sessionsTodayQuery->count();

            // ────────────── Session Payments Today (Only Paid Amount) ──────────────
            $sessionPaymentsToday = $sessionsTodayQuery->sum('paid_amount');

            // ────────────── Checkup Payments Today (Patient Branch ke hisaab se) ──────────────
            $checkupPaymentsToday = Checkup::whereHas('patient', function ($q) use ($branch) {
                    $q->where('branch_id', $branch->id);
                })
                ->whereDate('created_at', Carbon::today())
                ->sum('fee');

            // ────────────── Total Payments Today ──────────────
            $totalPaymentsToday = $checkupPaymentsToday + $sessionPaymentsToday;

            return [
                'branch_name'           => $branch->name,
                'totalDoctors'          => $totalDoctors,
                'totalPatients'         => $totalPatients,
                'totalCheckups'         => $totalCheckups,
                'totalSessionsToday'    => $totalSessionsToday,
                'checkupPaymentsToday'  => $checkupPaymentsToday,
                'sessionPaymentsToday'  => $sessionPaymentsToday,
                'totalPaymentsToday'    => $totalPaymentsToday,
            ];
        });

        return view('admin.dashboard', compact('branchStats'));
    }
}
