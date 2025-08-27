@extends('layouts.app')
@section('title')
    Add Patient
@endsection
@section('content')
    <x-page-title title="Patient" subtitle="Add New Patient" />

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-4">
                    <h5 class="mb-4">Patient Information</h5>
                    <form method="POST" action="/patients" class="row g-3">
                        @csrf
                        <div class="col-md-6">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" id="name" placeholder="Patient Name" required>
                        </div>

                        <div class="col-md-6">
                            <label for="guardian_name" class="form-label">Father/Husband Name</label>
                            <input type="text" name="guardian_name" class="form-control" id="guardian_name" placeholder="Father/Husband Name" required>
                        </div>

                        <div class="col-md-6">
                            <label for="age" class="form-label">Age</label>
                            <input type="number" name="age" class="form-control" id="age" placeholder="Age" required>
                        </div>

                        <div class="col-md-6">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control" id="phone" placeholder="Phone Number" required>
                        </div>

                        <div class="col-12">
                            <label for="address" class="form-label">Address</label>
                            <textarea name="address" class="form-control" id="address" placeholder="Patient Address" rows="2" required></textarea>
                        </div>

                        {{-- Gender Field --}}
                        <div class="col-md-6">
                            <label for="gender" class="form-label">Gender</label>
                            <select name="gender" id="gender" class="form-select" required>
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="branch_id" class="form-label">Branch</label>
                            <select name="branch_id" id="branch_id" class="form-select" required>
                                <option value="">Select Branch</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary px-4">Save Patient</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <!--plugins-->
    <script src="{{ URL::asset('build/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
    <script src="{{ URL::asset('build/plugins/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ URL::asset('build/plugins/simplebar/js/simplebar.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/main.js') }}"></script>
@endpush
