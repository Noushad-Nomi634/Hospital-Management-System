@extends('layouts.app')
@section('title')
    Payments Outstanding
@endsection

@section('content')
    <x-page-title title="Payments" subtitle="Outstanding Payments" />

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-responsive">
                    <table id="outstandingTable" class="table table-bordered table-hover mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col">Session ID</th>
                                <th scope="col">Checkup ID</th>
                                <th scope="col">Patient Name</th>
                                <th scope="col">Payment Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($outstandings as $session)
                                <tr>
                                    <td>{{ $session->id }}</td>
                                    <td>{{ $session->checkup_id }}</td>
                                    <td>{{ $session->patient->name ?? 'N/A' }}</td>
                                    <td>
                                        Total Fee: {{ $session->session_fee }} <br>
                                        Paid: {{ $session->totalPaid() }} <br>
                                        Due: {{ $session->remainingAmount() }} <br><br>

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
                                    <td colspan="4" style="text-align:center;">No outstanding payments found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    {{-- DataTables CSS --}}
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endpush

@push('script')
    <!-- Core plugins -->
    <script src="{{ URL::asset('build/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
    <script src="{{ URL::asset('build/plugins/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ URL::asset('build/plugins/simplebar/js/simplebar.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/main.js') }}"></script>

    {{-- DataTables JS --}}
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#outstandingTable').DataTable({
                "pageLength": 10,
                "lengthMenu": [5, 10, 25, 50, 100],
                "ordering": true,
                "searching": true,
                "columnDefs": [
                    { "orderable": false, "targets": 3 } // Payment Details column ko sort off
                ]
            });
        });
    </script>
@endpush
