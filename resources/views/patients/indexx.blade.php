@extends('layouts.app')

@section('title')
    Patients
@endsection

@push('css')
    <link href="{{ URL::asset('build/plugins/input-tags/css/tagsinput.css') }}" rel="stylesheet">
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
                        <a href="{{ url('/patients/create') }}" class="btn btn-primary">Add New Patients</a>
                    </div>

                    <!-- Patients Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Father/Husband Name</th>
                                    <th>Gender</th>
                                    <th>Age</th>
                                    <th>Branch</th>
                                    <th>Phone</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($patients as $patient)
                                    <tr>
                                        <td>{{ $patient->name }}</td>
                                        <td>{{ $patient->guardian_name }}</td>
                                        <td>{{ $patient->gender ?? 'N/A' }}</td>
                                        <td>{{ $patient->age }}</td>
                                        <td>{{ $patient->branch->name ?? 'N/A' }}</td>
                                        <td>{{ $patient->phone }}</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <!-- View -->
                                                <a href="{{ url('/patients/'.$patient->id) }}" class="btn btn-sm btn-info">View</a>

                                                <!-- Edit -->
                                                <a href="{{ url('/patients/'.$patient->id.'/edit') }}" class="btn btn-sm btn-warning">Edit</a>

                                                <!-- Delete -->
                                                <form action="{{ route('patients.destroy', $patient->id) }}" method="POST"
                                                    onsubmit="return confirm('Are you sure you want to delete this patient?')"
                                                    style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                </form>

                                                <!-- Checkups -->
                                               <a href="{{ url('/checkups/create?patient_id='.$patient->id) }}" 
                                               class="btn btn-sm btn-primary">Checkups</a>

                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
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
    <!-- Plugins -->
    <script src="{{ URL::asset('build/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
    <script src="{{ URL::asset('build/plugins/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ URL::asset('build/plugins/input-tags/js/tagsinput.js') }}"></script>
    <script src="{{ URL::asset('build/plugins/simplebar/js/simplebar.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/main.js') }}"></script>
@endpush
