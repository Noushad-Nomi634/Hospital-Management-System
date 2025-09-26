@extends('layouts.app')

@section('title')
    Doctor Consultations
@endsection

@push('css')
<<<<<<< Updated upstream
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css" rel="stylesheet"> {{-- 👈 Buttons CSS --}}
=======
    <link href="{{ URL::asset('build/plugins/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('build/plugins/input-tags/css/tagsinput.css') }}" rel="stylesheet">
>>>>>>> Stashed changes
    <style>
        /* Allow dropdowns to overflow */
        .table-responsive {
            overflow: visible;
        }
    </style>
@endpush

@section('content')
<<<<<<< Updated upstream
    <x-page-title title="Doctor Consultations" subtitle="List of all Doctor consultations" />
=======
    <x-page-title title="Treatment Sessions" subtitle="List of all treatment sessions" />
>>>>>>> Stashed changes

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #f8f9fa;">
                    <h5 class="mb-0 text-dark">Consultations List</h5>
<<<<<<< Updated upstream
                    {{-- <a href="{{ route('consultations.create') }}" class="btn btn-primary btn-sm" style="font-weight: 500;">
                        Add New Consultation
                    </a> --}}
=======
                    <a href="{{ route('consultations.create') }}" class="btn btn-primary btn-sm" style="font-weight: 500;">
                        Add New Consultation
                    </a>
>>>>>>> Stashed changes
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="sessions-table" class="table table-bordered table-hover dataTable no-footer">
                            <thead class="table-dark">
                                <tr>
                                    <th>Sr No</th>
                                    <th>Invoice</th>
                                    <th>Date</th>
<<<<<<< Updated upstream
                                    <th>MR</th>
                                    <th>Patient</th>
=======
                                    <th>MR-Patient</th>
>>>>>>> Stashed changes
                                    <th>Doctor</th>
                                    <th>Diagnosis</th>
                                    <th>Note</th>
                                    <th>Sanction Doctor</th>
                                    <th>Sanction Status</th>
                                    <th style="width:220px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $count = 0;
                                @endphp
                                @foreach ($sessions as $session)
                                    @php
                                        $count++;
                                        $total = $session->sessionTimes->count();
                                        $completed = $session->sessionTimes->where('is_completed', true)->count();
                                        $remaining = $total - $completed;
                                    @endphp
                                    <tr>
                                        <td>{{ $count }}</td>
                                        <td>{{ $session->checkup_id }}</td>
                                        <td>{{ date('d-m-Y', strtotime($session->created_at)) ?? 'N/A' }}</td>
<<<<<<< Updated upstream
                                        <td>{{ $session->patient?->mr ?? 'N/A' }}</td>
                                        <td>{{ $session->patient?->name ?? 'N/A' }}</td>
=======
                                        <td>{{ $session->patient?->mr ?? ('N/A' . '-' . $session->patient?->name ?? 'N/A') }}</td>
>>>>>>> Stashed changes
                                        <td>{{ $session->checkup?->doctor ? $session->checkup->doctor->first_name . ' ' . $session->checkup->doctor->last_name : 'N/A' }}
                                        </td>
                                        <td>{{ $session->diagnosis ?? '-' }}</td>
                                        <td>{{ $session->note ?? '-' }}</td>
                                        <td>{{ doctor_get_name($session->ss_dr_id) }}</td>

                                        {{-- Sessions Info --}}
                                        <td>
                                            @if ($session->con_status === 0)
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            @elseif ($session->con_status === 1)
                                                <span class="badge bg-success">Completed</span>
                                            @elseif ($session->con_status === 2)
                                                <span class="badge bg-success">Completed</span>
                                            @elseif ($session->con_status === 3)
                                                <span class="badge bg-danger">Cancelled</span>
                                            @else
                                                <span class="badge bg-secondary">Unknown</span>
                                            @endif
                                        </td>

                                        {{-- Actions Dropdown --}}
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <button type="button"
                                                    class="btn btn-outline-primary btn-sm">Action</button>
                                                <button type="button"
                                                    class="btn btn-outline-primary btn-sm dropdown-toggle dropdown-toggle-split"
                                                    data-bs-toggle="dropdown">
                                                    <span class="visually-hidden">Toggle Dropdown</span>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end p-2" style="min-width:220px;">
<<<<<<< Updated upstream

                                                    <a href="{{ route('doctor-consultations.status-view', $session->id) }}"
                                                        class="btn btn-sm btn-warning mb-1 w-100">Satisfactory Session Update</a>

                                                    <a href="{{ route('treatment-sessions.edit', $session->id) }}"
                                                        class="btn btn-sm btn-warning mb-1 w-100">Edit</a>

                                                    <form action="{{ route('treatment-sessions.destroy', $session->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger mb-1 w-100"
                                                            onclick="return confirm('Delete this session?')">Delete</button>
                                                    </form>
                                                    <a href="#" class="btn btn-sm btn-info mb-1 w-100"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#sessionModal{{ $session->id }}">Details</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

=======
                                                    <a href="{{ route('treatment-sessions.edit', $session->id) }}"
                                                        class="btn btn-sm btn-warning mb-1 w-100">Edit</a>
                                                    <form action="{{ route('treatment-sessions.destroy', $session->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger mb-1 w-100"
                                                            onclick="return confirm('Delete this session?')">Delete</button>
                                                    </form>
                                                    <a href="#" class="btn btn-sm btn-info mb-1 w-100"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#sessionModal{{ $session->id }}">Details</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

>>>>>>> Stashed changes
                                    {{-- Installment Modal --}}
                                    <div class="modal fade" id="installmentModal{{ $session->id }}" tabindex="-1"
                                        aria-labelledby="installmentModalLabel{{ $session->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('installments.store') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="session_id" value="{{ $session->id }}">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Add Installment (Session ID:
                                                            {{ $session->id }})</h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label>Amount</label>
                                                            <input type="number" class="form-control" name="amount"
                                                                required max="{{ $session->remainingAmount() }}"
                                                                placeholder="Remaining: {{ $session->remainingAmount() }}">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label>Paid On</label>
                                                            <input type="date" class="form-control" name="payment_date"
                                                                required value="{{ date('Y-m-d') }}">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Save
                                                            Installment</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Session Details Modal --}}
                                    <div class="modal fade" id="sessionModal{{ $session->id }}" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Session Details (ID: {{ $session->id }})</h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    {{-- Completed Sessions --}}
                                                    <h6 class="text-success">✅ Completed Sessions</h6>
                                                    <ul>
                                                        @forelse ($session->sessionTimes->where('is_completed', true) as $entry)
                                                            <li>{{ \Carbon\Carbon::parse($entry->session_datetime)->format('d M Y - h:i A') }}
                                                                - <strong>Doctor:</strong>
                                                                {{ $entry->doctor?->first_name . ' ' . $entry->doctor?->last_name ?? 'N/A' }},
                                                                <strong>Work:</strong> {{ $entry->work_done ?? 'N/A' }}
                                                            </li>
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
                                                                <form action="{{ route('sessions.complete', $entry->id) }}"
                                                                    method="POST" class="d-inline">
                                                                    @csrf
                                                                    <select name="doctor_id"
                                                                        class="form-control form-control-sm d-inline w-auto"
                                                                        required>
                                                                        @foreach ($doctors as $doctor)
                                                                            <option value="{{ $doctor->id }}"
                                                                                {{ ($entry->completed_by_doctor_id ?? $session->checkup?->doctor?->id) == $doctor->id ? 'selected' : '' }}>
                                                                                {{ $doctor->first_name }}
                                                                                {{ $doctor->last_name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                    <input type="text" name="work_done"
                                                                        placeholder="Session Description"
                                                                        class="form-control form-control-sm d-inline w-auto"
                                                                        value="{{ $entry->work_done ?? '' }}">
                                                                    <button type="submit"
                                                                        class="btn btn-success btn-sm">✔ Complete</button>
                                                                </form>

                                                                <form action="{{ route('sessions.destroy', $entry->id) }}"
                                                                    method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                                        onclick="return confirm('Delete this session?')">❌</button>
                                                                </form>
                                                            </li>
                                                        @empty
                                                            <li><em>No upcoming sessions</em></li>
                                                        @endforelse
                                                    </ul>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
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
<<<<<<< Updated upstream
    {{-- jQuery --}}
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    {{-- DataTables JS --}}
    {{-- DataTables JS --}}
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    {{-- Buttons Extension JS --}}
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

     {{-- Core Plugins --}}
    <script src="{{ URL::asset('build/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
    <script src="{{ URL::asset('build/plugins/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ URL::asset('build/plugins/input-tags/js/tagsinput.js') }}"></script>
    <script src="{{ URL::asset('build/plugins/simplebar/js/simplebar.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/main.js') }}"></script>




    <script>
        $(document).ready(function() {
            var table = $('#sessions-table').DataTable({
                responsive: false, // ❌ disable responsive (so columns na hide ho)
                scrollX: true, // ✅ horizontal scroll enable
                ordering: true,
                searching: true,
                pageLength: 5,
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],
                // Remove fixed widths and let columns auto adjust
                autoWidth: false,
                scrollX: true,
                columnDefs: [
                    {
                        targets: [8, 9],
                        orderable: false,
                        searchable: false
                    }
                ],
                drawCallback: function() {
                    $('#sessions-table').css('width', '100%');
                },
                createdRow: function(row, data, dataIndex) {
                    $(row).find('td').css('vertical-align', 'middle');
                },
                dom: "<'row mb-3'<'col-md-4'l><'col-md-4 text-end'B><'col-md-4'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row mt-2'<'col-md-5'i><'col-md-7'p>>",
                buttons: [
                    { extend: 'copy', text: 'Copy', className: 'btn btn-sm btn-light' },
                    { extend: 'csv', text: 'CSV', className: 'btn btn-sm btn-light' },
                    { extend: 'excel', text: 'Excel', className: 'btn btn-sm btn-light' },
                    { extend: 'pdf', text: 'PDF', className: 'btn btn-sm btn-light' },
                    { extend: 'print', text: 'Print', className: 'btn btn-sm btn-light' }
                ]
            });

=======
    <script src="{{ URL::asset('build/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
    <script src="{{ URL::asset('build/plugins/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ URL::asset('build/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('build/plugins/datatable/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ URL::asset('build/plugins/input-tags/js/tagsinput.js') }}"></script>
    <script src="{{ URL::asset('build/plugins/simplebar/js/simplebar.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/main.js') }}"></script>

    <script>
        $(document).ready(function() {
            var table = $('#sessions-table').DataTable({
                responsive: false, // ❌ disable responsive (so columns na hide ho)
                scrollX: true, // ✅ horizontal scroll enable
                ordering: true,
                searching: true,
                pageLength: 5,
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],
                // Remove fixed widths and let columns auto adjust
                autoWidth: false,
                scrollX: true,
                columnDefs: [
                    {
                        targets: [8, 9],
                        orderable: false,
                        searchable: false
                    }
                ],
                drawCallback: function() {
                    $('#sessions-table').css('width', '100%');
                },
                createdRow: function(row, data, dataIndex) {
                    $(row).find('td').css('vertical-align', 'middle');
                },
                dom: "<'row mb-3'<'col-md-4'l><'col-md-4 text-end'B><'col-md-4'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row mt-2'<'col-md-5'i><'col-md-7'p>>",
                buttons: [{
                        extend: 'copy',
                        text: 'Copy',
                        className: 'btn btn-outline-secondary btn-sm'
                    },
                    {
                        extend: 'csv',
                        text: 'CSV',
                        className: 'btn btn-outline-info btn-sm'
                    },
                    {
                        extend: 'excel',
                        text: 'Excel',
                        className: 'btn btn-outline-success btn-sm'
                    },
                    {
                        extend: 'pdf',
                        text: 'PDF',
                        className: 'btn btn-outline-danger btn-sm'
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        className: 'btn btn-outline-primary btn-sm'
                    }
                ]
            });

>>>>>>> Stashed changes
            // Styling fix for search + length dropdown
            $('.dataTables_filter input').addClass('form-control form-control-sm');
            $('.dataTables_length select').addClass('form-select form-select-sm');
        });
    </script>
@endpush
