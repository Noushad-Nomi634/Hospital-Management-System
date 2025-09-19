@extends('layouts.app')

@section('title', 'Doctor Dashboard')

@push('css')
    <link href="{{ asset('css/doctor-dashboard.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="container">
    <h2>Doctor Dashboard - {{ $doctor->name ?? $doctor->first_name.' '.$doctor->last_name }}</h2>

    <!-- Stats Section -->
    <div class="stats-grid">
        <div class="stat-card stat-blue">
            <h3>{{ $pendingConsultationsCount }}</h3>
            <p>Pending Consultations</p>
        </div>

        <div class="stat-card stat-green">
            <h3>{{ $todayPatients }}</h3>
            <p>Today Patients</p>
        </div>

        <div class="stat-card stat-yellow">
            <h3>{{ $todaySessionsCount }}</h3>
            <p>Today Sessions</p>
        </div>
    </div>

    <hr>

    <!-- Next 2 Days Schedule -->
    <h3>Next 2 Days Schedule</h3>
    <table class="schedule-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Day</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($twoDaySchedule as $schedule)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($schedule->date)->format('Y-m-d') }}</td>
                    <td>{{ $schedule->day_of_week }}</td>
                    <td>{{ $schedule->start_time ?? '-' }}</td>
                    <td>{{ $schedule->end_time ?? '-' }}</td>
                    <td>
                        @if($schedule->is_leave)
                            <span class="badge badge-red">Leave</span>
                        @else
                            <span class="badge badge-green">Available</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No schedule in next 2 days.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
