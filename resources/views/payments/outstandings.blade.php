@extends('layouts.app')
@section('title')
    Payments Outstanding
@endsection

@push('css')
    {{-- DataTables CSS --}}
    <link href="{{ URL::asset('build/plugins/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
    <!-- Bootstrap 5 CSS for consistency -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush

@section('content')
    <x-page-title title="Payments" subtitle="Outstanding Payments" />

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-responsive">
                    <table id="outstandingTable" class="table table-striped table-bordered" style="width:100%">
                        <thead class="table-dark">
                            <tr>
                                <th>Session ID</th>
                                <th>Checkup ID</th>
                                <th>Patient Name</th>
                                <th>Payment Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($outstandings as $session)
                                <tr>
                                    <td>{{ $session->id }}</td>
                                    <td>{{ $session->checkup_id }}</td>
                                    <td>{{ $session->patient->name ?? 'N/A' }}</td>
                                    <td>
                                        <strong>Total Fee:</strong> {{ $session->session_fee }} <br>
                                        <strong>Paid:</strong> {{ $session->totalPaid() }} <br>
                                        <strong>Due:</strong> {{ $session->remainingAmount() }} <br><br>

                                        @if($session->installments && $session->installments->count())
                                            <strong>Installments:</strong><br>
                                            <ul style="margin: 0; padding-left: 16px;">
                                                @foreach($session->installments as $installment)
                                                    <li>
                                                        {{ number_format($installment->amount, 2) }} 
                                                        on {{ \Carbon\Carbon::parse($installment->date)->format('d M Y') }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <em>No installments</em>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No outstanding payments found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <!-- Core plugins -->
    <script src="{{ URL::asset('build/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
    <script src="{{ URL::asset('build/plugins/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ URL::asset('build/plugins/simplebar/js/simplebar.min.js') }}"></script>
    <script src="{{ URL::asset('build/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('build/plugins/datatable/js/dataTables.bootstrap5.min.js') }}"></script>
    <!-- Bootstrap Bundle (Modal, Dropdown fix etc.) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Main JS -->
    <script src="{{ URL::asset('build/js/main.js') }}"></script>

    <script>
        $(document).ready(function () {
            $('#outstandingTable').DataTable({
                responsive: true,
                ordering: true,
                searching: true,
                pageLength: 10,
                lengthMenu: [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, "All"]],
                dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-end"f>>rt<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                columnDefs: [
                    { orderable: false, targets: 3 } // Payment Details column
                ],
                language: {
                    search: "",
                    searchPlaceholder: "Search payments...",
                    lengthMenu: "_MENU_ records per page",
                },
                createdRow: function(row, data, dataIndex) {
                    $(row).find('td').css('vertical-align', 'middle');
                }
            });

            // Custom styling for search box
            $('.dataTables_filter input').addClass('form-control form-control-sm');
        });
    </script>
@endpush
