@extends('layouts.app')
@section('title')
    Doctor Availability
@endsection

@push('css')
<link href="{{ URL::asset('build/plugins/input-tags/css/tagsinput.css') }}" rel="stylesheet">
@endpush

@section('content')
<x-page-title title="Doctor Availability" subtitle="Manage Slots for {{ $doctor->name }}" />

<div class="row">
    <div class="col-xl-10">

        <!-- Generate / Delete Month Buttons -->
        <div class="mb-3 d-flex gap-2">
            <form action="{{ route('doctors.availability.generate', $doctor->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success">Generate Full Month Schedule</button>
            </form>

            <form action="{{ route('doctors.availability.deleteMonth', $doctor->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete the full month schedule?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete Full Month Schedule</button>
            </form>
        </div>

        <!-- Existing Availability Table -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Existing Availability</h5>
                <a href="{{ route('doctors.index') }}" class="btn btn-secondary btn-sm">Back to Doctors</a>
            </div>
            <div class="card-body">
                @if($doctor->availabilities->count() > 0)
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Time Slot</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($doctor->availabilities as $slot)
                        <tr>
                            <!-- Day Name + Date -->
                            <td>{{ \Carbon\Carbon::parse($slot->date)->format('l, d M Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($slot->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($slot->end_time)->format('H:i') }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('doctors.availability.edit', $slot->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('doctors.availability.destroy', $slot->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this slot?')" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                    <p class="text-muted">No availability slots set yet.</p>
                @endif
            </div>
        </div>

        <!-- Add New Availability -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Add New Availability</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('doctors.availability.store', $doctor->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="date" class="form-label">Date:</label>
                        <input type="date" class="form-control" name="date" id="date" required>
                    </div>

                    <div class="mb-3">
                        <label for="start_time" class="form-label">Start Time:</label>
                        <input type="time" class="form-control" name="start_time" id="start_time" required>
                    </div>

                    <div class="mb-3">
                        <label for="end_time" class="form-label">End Time:</label>
                        <input type="time" class="form-control" name="end_time" id="end_time" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Add Slot</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="{{ URL::asset('build/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
<script src="{{ URL::asset('build/plugins/metismenu/metisMenu.min.js') }}"></script>
<script src="{{ URL::asset('build/plugins/input-tags/js/tagsinput.js') }}"></script>
<script src="{{ URL::asset('build/plugins/simplebar/js/simplebar.min.js') }}"></script>
<script src="{{ URL::asset('build/js/main.js') }}"></script>
@endpush
