@extends('layouts.app')

@section('title')
    Patient Consultations
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
<x-page-title title="Patient Records" subtitle="Consultations List" />

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #f8f9fa;">
                <h5 class="mb-0 text-dark">Consultations List</h5>
                <a href="{{ url('/consultations/create') }}" class="btn btn-primary btn-lg" style="font-weight: 500;">
                    Add New Consultation
                </a>
            </div>

            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="table-responsive">
                    <table id="consultations-table" class="table table-bordered table-hover mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>Patient</th>
                                <th>Date</th>
                                <th>Doctor</th>
                                <th>Fee</th>
                                <th>Paid Amount</th>
                                <th>Payment Method</th>
                                <th>Checkup Status</th>
                                <th style="width:220px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($consultations as $consultation)
                                <tr id="row-{{ $consultation->id }}">
                                    <td>{{ $consultation->patient_name ?? 'N/A' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($consultation->created_at)->format('d-m-Y') }}</td>
                                    <td>{{ $consultation->doctor_name }}</td>
                                    <td>Rs. {{ $consultation->fee }}</td>
                                    <td>Rs. {{ $consultation->paid_amount ?? 0 }}</td>
                                    <td>{{ $consultation->payment_method ?? 'N/A' }}</td>
                                    <td>
                                        @php $status = (int)($consultation->checkup_status ?? 0); @endphp
                                        @if($status === 0)
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @elseif($status === 1)
                                            <span class="badge bg-success">Completed</span>
                                        @elseif($status === 2)
                                            <span class="badge bg-danger">Cancelled</span>
                                        @else
                                            <span class="badge bg-secondary">Unknown</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-outline-primary btn-sm">Actions</button>
                                            <button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                                <span class="visually-hidden">Toggle Dropdown</span>
                                            </button>

                                            <div class="dropdown-menu dropdown-menu-end p-2" style="min-width:220px;">
                                                <a href="{{ url('/consultations/' . $consultation->id) }}" class="btn btn-info btn-sm mb-1 w-100">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                                <a href="{{ route('consultations.history', $consultation->patient_id) }}" class="btn btn-dark btn-sm mb-1 w-100">
                                                    <i class="fas fa-history"></i> History
                                                </a>
                                                <a href="{{ url('/consultations/' . $consultation->id . '/edit') }}" class="btn btn-warning btn-sm mb-1 w-100">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <a href="{{ route('consultations.print', $consultation->id) }}" class="btn btn-secondary btn-sm mb-1 w-100">
                                                    <i class="fas fa-print"></i> Print
                                                </a>
                                                <a href="{{ route('treatment-sessions.create', ['checkup_id' => $consultation->id]) }}" class="btn btn-success btn-sm mb-1 w-100">
                                                    <i class="fas fa-layer-group"></i> Sessions
                                                </a>
                                                <!-- AJAX Delete -->
                                                <button class="btn btn-danger btn-sm w-100 btn-delete" data-id="{{ $consultation->id }}">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>

<!-- Layout Plugins -->
<script src="{{ URL::asset('build/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
<script src="{{ URL::asset('build/plugins/metismenu/metisMenu.min.js') }}"></script>
<script src="{{ URL::asset('build/plugins/simplebar/js/simplebar.min.js') }}"></script>
<script src="{{ URL::asset('build/js/main.js') }}"></script>

<script>
$(document).ready(function() {
    $('#consultations-table').DataTable({
        responsive: true,
        autoWidth: false,
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50, 100],
        ordering: true,
        columnDefs: [
            { orderable: false, targets: 7 } // Actions column
        ],
    });

    // AJAX Delete
    $('.btn-delete').click(function(e){
        e.preventDefault();
        var id = $(this).data('id');
        if(!confirm('Are you sure you want to delete this consultation?')) return;

        $.ajax({
            url: '/consultations/' + id,
            type: 'POST',
            data: {_token: '{{ csrf_token() }}', _method: 'DELETE'},
            success: function(response){
                $('#row-' + id).fadeOut();
                alert('Consultation deleted successfully.');
            },
            error: function(xhr){
                alert('Failed to delete consultation.');
            }
        });
    });
});
</script>
@endpush
