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

                        {{-- Prefix --}}
                        @php
                            $prefixValue = old('prefix') ?? $patient->prefix;
                        @endphp
                        <div class="col-md-2">
                            <label for="prefix" class="form-label">Prefix</label>
                            <select name="prefix" id="prefix" class="form-select" required>
                                <option value="">Select</option>
                                <option value="Mr." {{ $prefixValue === 'Mr.' ? 'selected' : '' }}>Mr.</option>
                                <option value="Ms." {{ $prefixValue === 'Ms.' ? 'selected' : '' }}>Ms.</option>
                                <option value="Mrs." {{ $prefixValue === 'Mrs.' ? 'selected' : '' }}>Mrs.</option>
                            </select>
                        </div>

                        {{-- Name --}}
                        <div class="col-md-6">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" id="name"
                                   value="{{ old('name', $patient->name) }}" placeholder="Patient Name" required>
                        </div>

                        {{-- Guardian Name --}}
                        <div class="col-md-6">
                            <label for="guardian_name" class="form-label">Guardian Name</label>
                            <input type="text" name="guardian_name" class="form-control" id="guardian_name"
                                   value="{{ old('guardian_name', $patient->guardian_name) }}" placeholder="Guardian Name" required>
                        </div>

                        {{-- Age --}}
                        <div class="col-md-6">
                            <label for="age" class="form-label">Age</label>
                            <input type="number" name="age" class="form-control" id="age"
                                   value="{{ old('age', $patient->age) }}" placeholder="Age" required>
                        </div>

                        {{-- Phone --}}
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control" id="phone"
                                   value="{{ old('phone', $patient->phone) }}" placeholder="Phone Number" required>
                        </div>

                        {{-- CNIC --}}
                        <div class="col-md-6">
                            <label for="cnic" class="form-label">CNIC</label>
                            <input type="text" name="cnic" class="form-control" id="cnic"
                                   value="{{ old('cnic', $patient->cnic) }}" placeholder="XXXXX-XXXXXXX-X">
                        </div>

                        {{-- Address --}}
                        <div class="col-12">
                            <label for="address" class="form-label">Address</label>
                            <textarea name="address" class="form-control" id="address"
                                      placeholder="Patient Address" rows="2" required>{{ old('address', $patient->address) }}</textarea>
                        </div>

                        {{-- Gender --}}
                        <div class="col-md-6">
                            <label for="gender" class="form-label">Gender</label>
                            <select name="gender" id="gender" class="form-select" required>
                                <option value="">Select Gender</option>
                                <option value="Male" {{ old('gender', $patient->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('gender', $patient->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                <option value="Other" {{ old('gender', $patient->gender) == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>

                        {{-- Branch --}}
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

                        {{-- Referred By --}}
                        @php
                            $typeValue = old('type_select') ?? $patient->type_select;
                            $subValue  = old('sub_select') ?? $patient->sub_select;
                        @endphp
                        <div class="col-md-6 mt-2">
                            <label for="type_select" class="form-label">Referred By</label>
                            <div class="d-flex gap-2">
                                <select name="type_select" id="type_select" class="form-select">
                                    <option value="">Select Type</option>
                                    <option value="doctor" {{ $typeValue === 'doctor' ? 'selected' : '' }}>Doctor</option>
                                    <option value="patient" {{ $typeValue === 'patient' ? 'selected' : '' }}>Patient</option>
                                    <option value="social" {{ $typeValue === 'social' ? 'selected' : '' }}>Social Media</option>
                                </select>

                                <select name="sub_select" id="sub_select" class="form-select {{ $subValue ? '' : 'd-none' }}">
                                    <option value="">{{ $subValue ?? 'Select' }}</option>
                                </select>
                            </div>
                        </div>

                        {{-- Submit --}}
                        <div class="col-12 text-end mt-3">
                            <button type="submit" class="btn btn-info px-4">Update Patient</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
<script>
    const typeSelect = document.getElementById('type_select');
    const subSelect  = document.getElementById('sub_select');

    const doctors  = @json($doctors ?? []);
    const patients = @json($patients ?? []);

    function populateSubSelect(value, oldValue = null) {
        subSelect.classList.remove('d-none');
        subSelect.innerHTML = '<option value="">Select</option>';

        if(value === '') {
            subSelect.classList.add('d-none');
            return;
        }

        if(value === 'doctor') {
            doctors.forEach(doc => {
                const selected = (oldValue === doc) ? 'selected' : '';
                subSelect.innerHTML += `<option value="${doc}" ${selected}>${doc}</option>`;
            });
        }

        if(value === 'patient') {
            patients.forEach(pat => {
                const selected = (oldValue === pat) ? 'selected' : '';
                subSelect.innerHTML += `<option value="${pat}" ${selected}>${pat}</option>`;
            });
        }

        if(value === 'social') {
            ['WhatsApp', 'Facebook', 'Twitter'].forEach(platform => {
                const selected = (oldValue === platform) ? 'selected' : '';
                subSelect.innerHTML += `<option value="${platform}" ${selected}>${platform}</option>`;
            });
        }
    }

    // Initial load
    populateSubSelect(typeSelect.value, "{{ $subValue }}");

    // On change
    typeSelect.addEventListener('change', function () {
        populateSubSelect(this.value);
    });
</script>
@endpush
