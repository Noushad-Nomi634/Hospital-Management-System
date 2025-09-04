@extends('layouts.app')

@section('title', 'Doctor Availability')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Doctor Availability - <span class="text-primary">{{ $doctor->name }}</span></h2>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Month Navigation --}}
    <div class="d-flex justify-content-between mb-3">
        <a href="{{ route('doctors.availability.index', ['doctor' => $doctor->id, 'month' => $month-1 < 1 ? 12 : $month-1, 'year' => $month-1 < 1 ? $year-1 : $year]) }}" class="btn btn-outline-secondary">&larr; Previous Month</a>
        <h4 class="text-center">{{ date('F Y', mktime(0,0,0,$month,1,$year)) }}</h4>
        <a href="{{ route('doctors.availability.index', ['doctor' => $doctor->id, 'month' => $month+1 > 12 ? 1 : $month+1, 'year' => $month+1 > 12 ? $year+1 : $year]) }}" class="btn btn-outline-secondary">Next Month &rarr;</a>
    </div>

    {{-- Saved Schedule Table --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-dark text-white">
            Schedule
        </div>
        <div class="card-body p-0">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Day</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($datesInMonth as $date)
                        @php $avail = $availabilities[$date] ?? null; @endphp
                        <tr>
                            <td>{{ $date }}</td>
                            <td>{{ \Carbon\Carbon::parse($date)->format('l') }}</td>
                            <td>{{ $avail?->start_time ?? '-' }}</td>
                            <td>{{ $avail?->end_time ?? '-' }}</td>
                            <td>
                                @if($avail?->is_leave)
                                    <span class="badge bg-danger">Leave</span>
                                @else
                                    <span class="badge bg-success">Available</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">No schedule set for this month.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Toggle Button for Set/Update --}}
    <div class="mb-4 text-center">
        <button class="btn btn-lg btn-outline-primary px-5" type="button" data-bs-toggle="collapse" data-bs-target="#setForm">
            + Set / Update Availability
        </button>
    </div>

    {{-- Set/Update Form --}}
    <div class="collapse" id="setForm">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">Set / Update Availability</div>
            <div class="card-body">
                <form method="POST" action="{{ route('doctors.availability.store', $doctor->id) }}">
                    @csrf
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
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
                                    @php 
                                        $avail = $availabilities[$date] ?? null;
                                        $dayName = \Carbon\Carbon::parse($date)->format('l');
                                    @endphp
                                    <tr>
                                        <td>{{ $date }}</td>
                                        <td>{{ $dayName }}</td>
                                        <td>
                                            <input type="time" 
                                                   name="start_time[{{ $date }}]"
                                                   value="{{ $avail?->start_time ?? '' }}"
                                                   class="form-control form-control-sm time-input"
                                                   data-day="{{ $dayName }}"
                                                   data-row="{{ $date }}">
                                        </td>
                                        <td>
                                            <input type="time" 
                                                   name="end_time[{{ $date }}]"
                                                   value="{{ $avail?->end_time ?? '' }}"
                                                   class="form-control form-control-sm time-input"
                                                   data-day="{{ $dayName }}"
                                                   data-row="{{ $date }}">
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" 
                                                   name="is_leave[{{ $date }}]"
                                                   class="form-check-input leave-checkbox"
                                                   data-row="{{ $date }}"
                                                   @if($avail?->is_leave) checked @endif>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <button type="submit" class="btn btn-success">💾 Save Availability</button>
                </form>
            </div>
        </div>
    </div>

    {{-- Next / Delete Month Buttons --}}
    <div class="d-flex gap-3 mb-4">
        <form method="POST" action="{{ route('doctors.availability.generateNextMonth', $doctor->id) }}" class="flex-fill">
            @csrf
            <button type="submit" class="btn btn-info w-100">📅 Generate Next Month</button>
        </form>
        <form method="POST" action="{{ route('doctors.availability.deleteMonth', $doctor->id) }}" class="flex-fill">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger w-100">🗑 Delete Current Month</button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const timeInputs = document.querySelectorAll('.time-input');
    const leaveCheckboxes = document.querySelectorAll('.leave-checkbox');

    // auto-fill same weekday only if empty
    timeInputs.forEach(input => {
        input.addEventListener('change', function() {
            const day = this.dataset.day;
            const isStart = this.name.includes('start_time');
            const value = this.value;

            document.querySelectorAll(`.time-input[data-day="${day}"]`).forEach(el => {
                if(el !== this && el.value === ''){
                    if(isStart && el.name.includes('start_time')) el.value = value;
                    if(!isStart && el.name.includes('end_time')) el.value = value;
                }
            });
        });
    });

    // leave checkbox → clear times when checked
    leaveCheckboxes.forEach(chk => {
        chk.addEventListener('change', function() {
            const row = this.dataset.row;
            const startInput = document.querySelector(`input[name="start_time[${row}]"]`);
            const endInput = document.querySelector(`input[name="end_time[${row}]"]`);

            if(this.checked){
                startInput.value = '';
                endInput.value = '';
            }
        });
    });
});
</script>
@endpush


