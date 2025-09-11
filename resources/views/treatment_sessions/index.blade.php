@extends('layouts.app')

@section('title')
    Treatment Sessions
@endsection

@push('css')
<link href="{{ URL::asset('build/plugins/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('build/plugins/input-tags/css/tagsinput.css') }}" rel="stylesheet">
<style>
    /* Allow dropdowns to overflow */
    .table-responsive {
        overflow: visible;
    }
</style>
@endpush

@section('content')
<x-page-title title="Treatment Sessions" subtitle="List of all treatment sessions" />

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <a href="{{ route('treatment-sessions.create') }}" class="btn btn-primary">Add New Sessions</a>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="sessions-table" class="table table-bordered table-hover mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>Session ID</th>
                                <th>Checkup ID</th>
                                <th>Patient</th>
                                <th>Doctor</th>
                                <th>Diagnosis</th>
                                <th>Note</th>
                                <th>Fee</th>
                                <th>Payment Details</th>
                                <th>Sessions</th>
                                <th style="width:220px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sessions as $session)
                                @php
                                    $total = $session->sessionTimes->count();
                                    $completed = $session->sessionTimes->where('is_completed', true)->count();
                                    $remaining = $total - $completed;
                                @endphp
                                <tr>
                                    <td>{{ $session->id }}</td>
                                    <td>{{ $session->checkup_id }}</td>
                                    <td>{{ $session->patient?->name ?? 'N/A' }}</td>
                                    <td>{{ $session->checkup?->doctor ? $session->checkup->doctor->first_name.' '.$session->checkup->doctor->last_name : 'N/A' }}</td>
                                    <td>{{ $session->diagnosis ?? '-' }}</td>
                                    <td>{{ $session->note ?? '-' }}</td>
                                    <td>Rs. {{ number_format($session->session_fee, 0) }}</td>

                                    {{-- Payment Details --}}
                                    <td>
                                        <strong>Total Fee:</strong> Rs. {{ number_format($session->session_fee, 0) }}<br>
                                        <strong>Total Paid:</strong> Rs. {{ number_format($session->installments->sum('amount'), 0) }}<br>
                                        <strong>Dues:</strong> 
                                        <span class="{{ ($session->session_fee - $session->installments->sum('amount')) > 0 ? 'text-danger' : 'text-success' }}">
                                            Rs. {{ number_format($session->session_fee - $session->installments->sum('amount'), 0) }}
                                        </span>
                                        <br>
                                        <button class="btn btn-sm btn-success mt-2" data-bs-toggle="modal" data-bs-target="#installmentModal{{ $session->id }}">
                                            + Add Installment
                                        </button>
                                    </td>

                                    {{-- Sessions Info --}}
                                    <td>
                                        <small>Total: {{ $total }}</small><br>
                                        <small class="text-success">Completed: {{ $completed }}</small><br>
                                        <small class="text-warning">Remaining: {{ $remaining }}</small>
                                        <br>
                                        <button class="btn btn-info btn-sm mt-1" data-bs-toggle="modal" data-bs-target="#sessionModal{{ $session->id }}">
                                            View
                                        </button>
                                    </td>

                                    {{-- Actions Dropdown --}}
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-outline-primary btn-sm">Buttons</button>
                                            <button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">
                                                <span class="visually-hidden">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end p-2" style="min-width:220px;">
                                                <a href="{{ route('treatment-sessions.edit', $session->id) }}" class="btn btn-sm btn-warning mb-1 w-100">Edit</a>
                                                <form action="{{ route('treatment-sessions.destroy', $session->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger mb-1 w-100" onclick="return confirm('Delete this session?')">Delete</button>
                                                </form>
                                                <a href="#" class="btn btn-sm btn-info mb-1 w-100" data-bs-toggle="modal" data-bs-target="#sessionModal{{ $session->id }}">Details</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                {{-- Installment Modal --}}
                                <div class="modal fade" id="installmentModal{{ $session->id }}" tabindex="-1" aria-labelledby="installmentModalLabel{{ $session->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('installments.store') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="session_id" value="{{ $session->id }}">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Add Installment (Session ID: {{ $session->id }})</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label>Amount</label>
                                                        <input type="number" class="form-control" name="amount" required max="{{ $session->remainingAmount() }}" placeholder="Remaining: {{ $session->remainingAmount() }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label>Paid On</label>
                                                        <input type="date" class="form-control" name="payment_date" required value="{{ date('Y-m-d') }}">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Save Installment</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                {{-- Session Details Modal --}}
                                <div class="modal fade" id="sessionModal{{ $session->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Session Details (ID: {{ $session->id }})</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                {{-- Completed Sessions --}}
                                                <h6 class="text-success">✅ Completed Sessions</h6>
                                                <ul>
                                                    @forelse ($session->sessionTimes->where('is_completed', true) as $entry)
                                                        <li>{{ \Carbon\Carbon::parse($entry->session_datetime)->format('d M Y - h:i A') }} - <strong>Doctor:</strong> {{ $entry->doctor?->first_name.' '.$entry->doctor?->last_name ?? 'N/A' }}, <strong>Work:</strong> {{ $entry->work_done ?? 'N/A' }}</li>
                                                    @empty
                                                        <li><em>No completed sessions yet</em></li>
                                                    @endforelse
                                                </ul>

                                                {{-- Upcoming Sessions --}}
                                                <h6 class="text-primary mt-3">🕒 Upcoming Sessions</h6>
                                                <ul>
                                                    @forelse ($session->sessionTimes->where('is_completed', false) as $entry)
                                                        <li>
                                                            {{ \Carbon\Carbon::parse($entry->session_datetime)->format('d M Y - h:i A') }}
                                                            <form action="{{ route('sessions.complete', $entry->id) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <select name="doctor_id" class="form-control form-control-sm d-inline w-auto" required>
                                                                    @foreach($doctors as $doctor)
                                                                        <option value="{{ $doctor->id }}" {{ ($entry->completed_by_doctor_id ?? $session->checkup?->doctor?->id) == $doctor->id ? 'selected' : '' }}>
                                                                            {{ $doctor->first_name }} {{ $doctor->last_name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                <input type="text" name="work_done" placeholder="Session Description" class="form-control form-control-sm d-inline w-auto" value="{{ $entry->work_done ?? '' }}">
                                                                <button type="submit" class="btn btn-success btn-sm">✔ Complete</button>
                                                            </form>

                                                            <form action="{{ route('sessions.destroy', $entry->id) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this session?')">❌</button>
                                                            </form>
                                                        </li>
                                                    @empty
                                                        <li><em>No upcoming sessions</em></li>
                                                    @endforelse
                                                </ul>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

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
<script src="{{ URL::asset('build/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
<script src="{{ URL::asset('build/plugins/metismenu/metisMenu.min.js') }}"></script>
<script src="{{ URL::asset('build/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('build/plugins/datatable/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ URL::asset('build/plugins/input-tags/js/tagsinput.js') }}"></script>
<script src="{{ URL::asset('build/plugins/simplebar/js/simplebar.min.js') }}"></script>
<script src="{{ URL::asset('build/js/main.js') }}"></script>

<script>
$(document).ready(function() {
    $('#sessions-table').DataTable({
        responsive: false, // ✅ disable responsive to avoid hiding Actions
        ordering: true,
        searching: true,
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        columnDefs: [
            { targets: [7, 8, 9], orderable: false, searchable: false }, // Actions, Payment, Sessions columns
            { targets: [0, 1], width: "5%" },
            { targets: [6, 7, 8, 9], width: "12%" }
        ],
        createdRow: function(row, data, dataIndex) {
            $(row).find('td').css('vertical-align', 'middle');
        }
    });

    $('.dataTables_filter input').addClass('form-control form-control-sm');
});
</script>
@endpush
