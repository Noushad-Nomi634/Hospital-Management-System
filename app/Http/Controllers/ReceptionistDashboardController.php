<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Checkup;
use App\Models\TreatmentSession;
use App\Models\Transaction;
use Carbon\Carbon;

class ReceptionistDashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $thirtyDaysAgo = Carbon::today()->subDays(30);

        // ─────────── Today Consultations ───────────
        $todayConsultations = Checkup::whereDate('date', $today)->get();
        $todayConsultationFee = $todayConsultations->sum('fee');

        // ─────────── Today Sessions ───────────
        $todaySessionsQuery = TreatmentSession::where(function ($query) use ($today) {
            $query->whereDate('session_date', $today)
                  ->orWhere(function ($q2) use ($today) {
                      $q2->whereNull('session_date')
                         ->whereDate('created_at', $today);
                  });
        });

        $todaySessions = $todaySessionsQuery->get();
        $todaySessionFee = $todaySessionsQuery->sum('paid_amount'); // total paid amount today
        $totalTodaySessions = $todaySessions->count();

        // ─────────── Today Payments ───────────
        $todayPayments = Transaction::whereDate('created_at', $today)
                                    ->where('type', '+')
                                    ->get();

        $totalPaymentsInHand = $todayPayments->sum('amount');

        // ─────────── Cash / Online Breakdown ───────────
        $todayCashPayments = $todayPayments->filter(fn($p) => str_contains(strtolower($p->Remx), 'cash'))->sum('amount');
        $todayOnlinePayments = $todayPayments->filter(fn($p) => str_contains(strtolower($p->Remx), 'online'))->sum('amount');

        // ─────────── Last 30 Days Income ───────────
        $last30DaysIncome = Transaction::whereDate('created_at', '>=', $thirtyDaysAgo)
                                       ->where('type', '+')
                                       ->sum('amount');

        return view('receptionist.dashboard', compact(
            'todayConsultations',
            'todayConsultationFee',
            'todaySessions',
            'todaySessionFee',
            'totalTodaySessions',
            'totalPaymentsInHand',
            'todayCashPayments',
            'todayOnlinePayments',
            'last30DaysIncome'
        ));
    }
}
