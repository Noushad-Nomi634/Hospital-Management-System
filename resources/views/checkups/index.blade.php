@extends('layouts.app')

@section('title')
    Patient Checkups
@endsection

@push('css')
<link href="{{ URL::asset('build/plugins/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
<style>
    .table-responsive {
        overflow: visible; /* Dropdown visible outside table */
    }
</style>
@endpush

@section('content')
<x-page-title title="Patient Records" subtitle="Checkups List" />

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #f8f9fa;">
                <h5 class="mb-0 text-dark">Checkups List</h5>
                <a href="{{ url('/checkups/create') }}" class="btn btn-primary btn-lg" style="font-weight: 500;">
                    Add New Consultation
                </a>
            </div>

            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="table-responsive">
                    <table id="checkups-table" class="table table-bordered table-hover mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>Patient</th>
                                <th>Date</th>
                                <th>Doctor</th>
                                <th>Fee</th>
                                <th style="width:200px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($checkups as $checkup)
                                <tr>
                                    <td>{{ $checkup->patient_name ?? 'N/A' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($checkup->date)->format('d-m-Y') }}</td>
                                    <td>{{ $checkup->doctor_name }}</td>
                                    <td>Rs. {{ $checkup->fee }}</td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <!-- Main Actions Button -->
                                            <button type="button" class="btn btn-outline-primary btn-sm">Buttons</button>
                                            <button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                                <span class="visually-hidden">Toggle Dropdown</span>
                                            </button>

                                            <!-- Dropdown Menu -->
                                            <div class="dropdown-menu dropdown-menu-end p-2" style="min-width:220px;">
                                                <a href="{{ url('/checkups/' . $checkup->id) }}" class="btn btn-info btn-sm mb-1 w-100">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                                <a href="{{ route('checkups.history', $checkup->patient_id) }}" class="btn btn-dark btn-sm mb-1 w-100">
                                                    <i class="fas fa-history"></i> History
                                                </a>
                                                <a href="{{ url('/checkups/' . $checkup->id . '/edit') }}" class="btn btn-warning btn-sm mb-1 w-100">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <a href="{{ route('checkups.print', $checkup->id) }}" class="btn btn-secondary btn-sm mb-1 w-100">
                                                    <i class="fas fa-print"></i> Print
                                                </a>
                                                <a href="{{ route('treatment-sessions.create', ['checkup_id' => $checkup->id]) }}" class="btn btn-success btn-sm mb-1 w-100">
                                                    <i class="fas fa-layer-group"></i> Sessions
                                                </a>
                                                <form method="POST" action="{{ url('/checkups/' . $checkup->id) }}" onsubmit="return confirm('Are you sure?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-danger btn-sm w-100" type="submit">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="{{ URL::asset('build/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('build/plugins/datatable/js/dataTables.bootstrap5.min.js') }}"></script>
<script>
$(document).ready(function() {
    $('#checkups-table').DataTable({
        responsive: true,
        autoWidth: false,
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50, 100],
        ordering: true,
        columnDefs: [
            { orderable: false, targets: 4 } // Actions column
        ],
    });
});
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
@endpush
