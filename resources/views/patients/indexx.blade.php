@extends('layouts.app')

@section('title')
    Patients
@endsection

@push('css')
    {{-- DataTables CSS --}}
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        /* Allow dropdown to overflow table container */
        .table-responsive {
            overflow: visible;
        }
    </style>
@endpush

@section('content')
    <x-page-title title="Patients" subtitle="Management" />

    <div class="row">
        <div class="col-xl-12 mx-auto">
            <div class="card">
                <div class="card-body">
                    <!-- Header with Add New Button -->
                    <div class="d-flex justify-content-between mb-3">
                        <h5 class="mb-0">All Patients</h5>
                        <a href="{{ url('/patients/create') }}" class="btn btn-primary">Add New Patient</a>
                    </div>

                    <!-- Patients Table -->
                    <div class="table-responsive">
                        <table id="patientsTable" class="table table-bordered table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Name</th>
                                    <th>Father/Husband Name</th>
                                    <th>Gender</th>
                                    <th>Age</th>
                                    <th>Branch</th>
                                    <th>Phone</th>
                                    <th style="width:200px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($patients as $patient)
                                    <tr>
                                        <td>{{ $patient->name ?? 'N/A' }}</td>
                                        <td>{{ $patient->guardian_name ?? 'N/A' }}</td>
                                        <td>{{ $patient->gender ?? 'N/A' }}</td>
                                        <td>{{ $patient->age ?? 'N/A' }}</td>
                                        <td>{{ $patient->branch?->name ?? 'N/A' }}</td>
                                        <td>{{ $patient->phone ?? 'N/A' }}</td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-outline-primary">Actions</button>
                                                <button type="button" class="btn btn-outline-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <span class="visually-hidden">Toggle Dropdown</span>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end p-2" style="min-width:220px;">
                                                    <a href="{{ url('/patients/'.$patient->id) }}" class="btn btn-sm btn-info mb-1 w-100">View</a>
                                                    <a href="{{ url('/patients/'.$patient->id.'/edit') }}" class="btn btn-sm btn-warning mb-1 w-100">Edit</a>
                                                    <form action="{{ route('patients.destroy', $patient->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this patient?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger mb-1 w-100">Delete</button>
                                                    </form>
                                                    <a href="{{ url('/checkups/create?patient_id='.$patient->id) }}" class="btn btn-sm btn-primary mb-1 w-100">Checkups</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No patients found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <!-- End table -->
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    {{-- jQuery --}}
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    {{-- DataTables JS --}}
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    {{-- Core Plugins --}}
    <script src="{{ URL::asset('build/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
    <script src="{{ URL::asset('build/plugins/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ URL::asset('build/plugins/input-tags/js/tagsinput.js') }}"></script>
    <script src="{{ URL::asset('build/plugins/simplebar/js/simplebar.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/main.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#patientsTable').DataTable({
                responsive: true,
                autoWidth: false,
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50, 100],
                ordering: true,
                columnDefs: [
                    { orderable: false, targets: 6 } // Actions column
                ],
            });
        });
    </script>
@endpush

