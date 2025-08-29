@extends('layouts.app')

@section('title', 'Doctor Availability')

@section('content')
<div class="container mt-4">
    <h2>Doctor Availability for {{ $doctor->name }}</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form id="availability-form" method="POST" action="{{ route('doctors.availability.store', $doctor->id) }}">
        @csrf

        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Date</th>
                    <th>Day</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Leave</th>
                </tr>
            </thead>
            <tbody>
                @foreach($datesInMonth as $date)
                @php $avail = $availabilities[$date] ?? null; @endphp
                <tr>
                    <td>{{ $date }}</td>
                    <td>{{ \Carbon\Carbon::parse($date)->format('l') }}</td>
                    <td>
                        <input type="time" name="start_time[{{ $date }}]" value="{{ $avail->start_time ?? '' }}" class="form-control time-input" data-day="{{ \Carbon\Carbon::parse($date)->format('l') }}">
                    </td>
                    <td>
                        <input type="time" name="end_time[{{ $date }}]" value="{{ $avail->end_time ?? '' }}" class="form-control time-input" data-day="{{ \Carbon\Carbon::parse($date)->format('l') }}">
                    </td>
                    <td class="text-center">
                        <input type="checkbox" name="is_leave[{{ $date }}]" class="leave-checkbox" data-day="{{ \Carbon\Carbon::parse($date)->format('l') }}" @if($avail?->is_leave) checked @endif>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-3">
            <button type="submit" class="btn btn-success">Save Availability</button>
        </div>
    </form>

    <hr>

    <h4>Next Month Schedule</h4>
    <form method="POST" action="{{ route('doctors.availability.generateNextMonth', $doctor->id) }}">
        @csrf
        <button type="submit" class="btn btn-primary">Generate Next Month Same Schedule</button>
    </form>

    <hr>

    <h4>Delete Current Month Schedule</h4>
    <form method="POST" action="{{ route('doctors.availability.deleteMonth', $doctor->id) }}">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">Delete Month Schedule</button>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const timeInputs = document.querySelectorAll('.time-input');
    timeInputs.forEach(input => {
        input.addEventListener('change', function() {
            const day = this.dataset.day;
            const value = this.value;
            document.querySelectorAll(`.time-input[data-day="${day}"]`).forEach(el => { el.value = value; });
        });
    });

    const leaveCheckboxes = document.querySelectorAll('.leave-checkbox');
    leaveCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const day = this.dataset.day;
            const checked = this.checked;
            document.querySelectorAll(`.leave-checkbox[data-day="${day}"]`).forEach(el => { el.checked = checked; });
        });
    });
});
</script>
@endpush
