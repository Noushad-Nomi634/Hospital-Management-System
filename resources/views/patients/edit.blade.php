@extends('layouts.app')

@section('title')
    Edit Patient
@endsection

@section('content')
    <x-page-title title="Patient" subtitle="Edit Patient" />

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-4">
                    <h5 class="mb-4">Edit Patient Information</h5>

                    <form method="POST" action="{{ route('patients.update', $patient->id) }}" class="row g-3">
                        @csrf
                        @method('PUT')

                        <div class="col-md-6">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" id="name"
                                   value="{{ old('name', $patient->name) }}" placeholder="Patient Name" required>
                        </div>

                        <div class="col-md-6">
                            <label for="guardian_name" class="form-label">Guardian Name</label>
                            <input type="text" name="guardian_name" class="form-control" id="guardian_name"
                                   value="{{ old('guardian_name', $patient->guardian_name) }}" placeholder="Guardian Name" required>
                        </div>

                        <div class="col-md-6">
                            <label for="age" class="form-label">Age</label>
                            <input type="number" name="age" class="form-control" id="age"
                                   value="{{ old('age', $patient->age) }}" placeholder="Age" required>
                        </div>

                        <div class="col-md-6">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control" id="phone"
                                   value="{{ old('phone', $patient->phone) }}" placeholder="Phone Number" required>
                        </div>

                        <div class="col-12">
                            <label for="address" class="form-label">Address</label>
                            <textarea name="address" class="form-control" id="address"
                                      placeholder="Patient Address" rows="2" required>{{ old('address', $patient->address) }}</textarea>
                        </div>

                        <div class="col-md-6">
                            <label for="gender" class="form-label">Gender</label>
                            <select name="gender" id="gender" class="form-select" required>
                                <option value="">Select Gender</option>
                                <option value="Male" {{ old('gender', $patient->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('gender', $patient->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                <option value="Other" {{ old('gender', $patient->gender) == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="branch_id" class="form-label">Branch</label>
                            <select name="branch_id" id="branch_id" class="form-select" required>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ old('branch_id', $patient->branch_id) == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-info px-4">Update Patient</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ URL::asset('build/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
    <script src="{{ URL::asset('build/plugins/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ URL::asset('build/plugins/simplebar/js/simplebar.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/main.js') }}"></script>
@endpush
