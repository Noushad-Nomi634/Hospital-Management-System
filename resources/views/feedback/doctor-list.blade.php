@extends('layouts.app')

@section('title', 'Doctor Feedback List')

@section('content')
<div class="container">

    <h3>Doctor Feedback List</h3>

    @if(session('success'))
        <div style="color:green; margin-bottom: 10px;">
            {{ session('success') }}
        </div>
    @endif

    @if($feedbacks->isEmpty())
        <p>No doctor feedback available.</p>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-primary">
                    <tr>
                        <th>ID</th>
                        <th>Session ID</th>
                        <th>Doctor Name</th>
                        <th>Patient Name</th>
                        <th>Doctor Remarks</th>
                        <th>Satisfaction (%)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($feedbacks as $fb)
                        <tr>
                            <td>{{ $fb->id }}</td>
                            <td>{{ $fb->sessionsid }}</td>
                            <td>{{ $fb->doctor_name ?? '-' }}</td>
                            <td>{{ $fb->patient_name ?? '-' }}</td>
                            <td>{{ $fb->doctor_remarks ?? '-' }}</td>
                            <td>{{ $fb->satisfaction }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

</div>
@endsection
