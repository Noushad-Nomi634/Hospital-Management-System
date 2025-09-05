@extends('layouts.app')

@section('title')
    Clinic Dashboard
@endsection

@section('content')

    <!-- Page Heading -->
    <div class="dashboard-title">
        <h2>Dashboard</h2>
        <p>Clinic Management</p>
    </div>

    <!-- All Branches -->
    <div class="branches-wrapper">
        @foreach($branchStats as $branch)
            <div class="branch-box">
                <div class="branch-header">
                    <h5>{{ $branch['branch_name'] }}</h5>
                    <small>{{ \Carbon\Carbon::now()->format('d M Y') }}</small>
                </div>
                <div class="stats-grid">
                    <div class="stat-card stat-blue">
                        <h6>{{ $branch['totalDoctors'] ?? 0 }}</h6>
                        <p>Doctors</p>
                    </div>
                    <div class="stat-card stat-green">
                        <h6>{{ $branch['totalPatients'] ?? 0 }}</h6>
                        <p>Patients</p>
                    </div>
                    <div class="stat-card stat-cyan">
                        <h6>{{ $branch['totalCheckups'] ?? 0 }}</h6>
                        <p>Checkups</p>
                    </div>
                    <div class="stat-card stat-orange">
                        <h6>{{ $branch['totalSessionsToday'] ?? 0 }}</h6>
                        <p>Sessions</p>
                    </div>
                    <div class="stat-card stat-total">
                        <h6>{{ number_format(($branch['checkupPaymentsToday'] ?? 0) + ($branch['sessionPaymentsToday'] ?? 0), 0) }}</h6>
                        <p>Total Payments</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Overall Stats Card -->
    @php
        $overallDoctors = collect($branchStats)->sum('totalDoctors');
        $overallPatients = collect($branchStats)->sum('totalPatients');
        $overallCheckups = collect($branchStats)->sum('totalCheckups');
        $overallSessions = collect($branchStats)->sum('totalSessionsToday');
        $overallPayments = collect($branchStats)->sum(fn($b) => ($b['checkupPaymentsToday'] ?? 0) + ($b['sessionPaymentsToday'] ?? 0));
    @endphp

    <!-- Overall Heading -->
    <div class="overall-title">
        Overall Branches
    </div>

    <div class="overall-box">
        <div class="overall-header">
            <h4>Overall Branches</h4>
        </div>
        <div class="stats-grid">
            <div class="stat-card stat-blue">
                <h6>{{ $overallDoctors }}</h6>
                <p>Doctors</p>
            </div>
            <div class="stat-card stat-green">
                <h6>{{ $overallPatients }}</h6>
                <p>Patients</p>
            </div>
            <div class="stat-card stat-cyan">
                <h6>{{ $overallCheckups }}</h6>
                <p>Checkups</p>
            </div>
            <div class="stat-card stat-orange">
                <h6>{{ $overallSessions }}</h6>
                <p>Sessions</p>
            </div>
            <div class="stat-card stat-total">
                <h6>{{ number_format($overallPayments, 0) }}</h6>
                <p>Total Payments</p>
            </div>
        </div>
    </div>
@endsection
