@extends('layouts.app')

@section('styles')
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container receptionist-dashboard">
    <h2 class="dashboard-title">Receptionist Dashboard</h2>

    <div class="stats-grid">
        <!-- Today Consultations -->
        <div class="stat-card stat-blue">
            <h6>{{ $todayConsultations->count() }}</h6>
            <p>Today Consultations</p>
            <small>Fees: {{ number_format($todayConsultationFee, 2) }}</small>
        </div>

        <!-- Today Sessions -->
        <div class="stat-card stat-green">
            <h6>{{ $totalTodaySessions }}</h6>
            <p>Today Sessions</p>
            <small>Fees: {{ number_format($todaySessionFee, 2) }}</small>
        </div>

        <!-- Total Payments in Hand -->
        <div class="stat-card stat-total">
            <h6>{{ number_format($totalPaymentsInHand, 2) }}</h6>
            <p>Total Payments in Hand</p>
        </div>

        <!-- Today Payment Breakdown -->
        <div class="stat-card stat-orange">
            <h6>{{ number_format($todayCashPayments, 2) }} / {{ number_format($todayOnlinePayments, 2) }}</h6>
            <p>Today Payment (Cash / Online)</p>
        </div>

        <!-- Last 30 Days Income -->
        <div class="stat-card stat-cyan">
            <h6>{{ number_format($last30DaysIncome, 2) }}</h6>
            <p>Last 30 Days Income</p>
        </div>
    </div>
</div>
@endsection
