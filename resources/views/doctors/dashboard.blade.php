@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Doctor Dashboard</h1>

    <!-- Assigned Patients -->
    <div class="card mb-4">
        <div class="card-header">Assigned Patients</div>
        <div class="card-body">
            @if($assignedPatients->isEmpty())
                <p>No assigned patients.</p>
            @else
                <ul>
                    @foreach($assignedPatients as $patient)
                        <li>{{ $patient->name ?? 'N/A' }} (ID: {{ $patient->id ?? 'N/A' }})</li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    <!-- Today's Sessions -->
    <div class="card mb-4">
        <div class="card-header">Today's Sessions</div>
        <div class="card-body">
            @if($todaySessions->isEmpty())
                <p>No sessions today.</p>
            @else
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Patient Name</th>
                            <th>Fee</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($todaySessions as $session)
                            <tr>
                                <!-- Safe null handling -->
                                <td>{{ $session->patient?->name ?? 'N/A' }}</td>
                                <td>{{ $session->fee ?? '0' }}</td>
                                <td>
                                    @if(!empty($session->date))
                                        {{ \Carbon\Carbon::parse($session->date)->format('d M Y') }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <p><strong>Total Sessions:</strong> {{ $totalSessions ?? 0 }}</p>
                <p><strong>Total Fee:</strong> {{ $totalFee ?? 0 }}</p>
            @endif
        </div>
    </div>

    <!-- Next 2 Days Schedule -->
    <div class="card mb-4">
        <div class="card-header">Next 2 Days Schedule</div>
        <div class="card-body">
            @if($nextSchedule->isEmpty())
                <p>No scheduled availability for the next 2 days.</p>
            @else
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Day</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($nextSchedule as $schedule)
                            <tr>
                                <td>
                                    @if(!empty($schedule->date))
                                        {{ \Carbon\Carbon::parse($schedule->date)->format('d M Y') }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>{{ $schedule->day_of_week ?? 'N/A' }}</td>
                                <td>{{ $schedule->start_time ?? 'N/A' }}</td>
                                <td>{{ $schedule->end_time ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection
