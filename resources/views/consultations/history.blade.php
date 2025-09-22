@extends('layouts.app')

@section('title')
    Patient History
@endsection

@push('css')
    <link href="{{ URL::asset('build/plugins/input-tags/css/tagsinput.css') }}" rel="stylesheet">
@endpush

@section('content')

    <x-page-title title="Patient History" subtitle="Checkups Record" />

    <div class="card">
        <div class="card-body">

            <h4>Patient: {{ $patient->name ?? 'Unknown' }}</h4>
            <p><strong class="fs-5">Total Checkups:</strong> <span class="fs-5">{{ $history->count() }}</span></p>

            <div class="table-responsive mt-3">
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Patient</th>
                            <th>Date</th>
                            <th>Doctor</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($history as $record)
                            <tr>
                                <td>{{ $patient->name ?? 'Unknown' }}</td>
                                <td>{{ \Carbon\Carbon::parse($record->created_at)->format('d-m-Y') }}</td>
                                <td>{{ $record->doctor_name }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No checkup history found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

@endsection

@push('script')
    <!-- Plugins -->
    <script src="{{ URL::asset('build/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
    <script src="{{ URL::asset('build/plugins/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ URL::asset('build/plugins/input-tags/js/tagsinput.js') }}"></script>
    <script src="{{ URL::asset('build/plugins/simplebar/js/simplebar.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/main.js') }}"></script>
@endpush


