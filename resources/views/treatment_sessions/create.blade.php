@extends('layouts.app')

@section('title')
Dr Consultations
@endsection

@push('css')
<link href="{{ URL::asset('build/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet">
<link href="{{ URL::asset('build/plugins/metismenu/metisMenu.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('build/plugins/simplebar/css/simplebar.min.css') }}" rel="stylesheet">
@endpush

@section('content')
<x-page-title title="Doctor Consultations" subtitle="Diagnosis" />

<div class="row">
    <div class="col-xl-12 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="text-white">Dr Consultations</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('treatment-sessions.store') }}">
                    @csrf

                    @php
                        $selectedCheckupId = old('checkup_id') ?? request()->get('checkup_id');
                        $selectedCheckup = $checkups->where('id', $selectedCheckupId)->first();
                        $selectedDoctorId = old('doctor_id') ?? ($selectedCheckup->doctor_id ?? null);
                        $selectedPatientName = $selectedCheckup->patient->name ?? '';
                        $selectedDoctorName = $selectedCheckup && $selectedCheckup->doctor
                                              ? $selectedCheckup->doctor->first_name.' '.$selectedCheckup->doctor->last_name
                                              : '';
                    @endphp

                    <div class="row">
                        <!-- Checkup -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Checkup</label>
                            <select class="form-select" name="checkup_id" id="checkup_id" required>
                                <option value="">Select Checkup</option>
                                @foreach($checkups as $checkup)
                                    <option value="{{ $checkup->id }}"
                                        {{ $selectedCheckupId == $checkup->id ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::parse($checkup->created_at)->format('Y-m-d') }} - {{ $checkup->patient->name ?? 'No Patient' }}
                                        ({{ $checkup->doctor ? $checkup->doctor->first_name.' '.$checkup->doctor->last_name : 'No Doctor' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Patient Info -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Patient</label>
                            <input type="text" class="form-control" id="patient_name"
                                   value="{{ $selectedPatientName }}" readonly>
                        </div>

                        <!-- Doctor Info -->
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Doctor</label>
                            <input type="text" class="form-control" id="doctor_name"
                                   value="{{ $selectedDoctorName }}" readonly>
                            <input type="hidden" name="doctor_id" id="doctor_id" value="{{ $selectedDoctorId }}">
                        </div>

                        <!-- Note -->
                        <div class="mb-3">
                            <label class="form-label">Note</label>
                            <textarea class="form-control" name="note" id="note" rows="3"
                                      placeholder="Enter any notes">{{ old('note') }}</textarea>
                        </div>

                        <!-- Diagnosis -->
                        <div class="mb-3">
                            <label class="form-label">Diagnosis</label>
                            <input type="text" class="form-control" name="diagnosis" id="diagnosis"
                                   value="{{ old('diagnosis') }}" placeholder="Enter diagnosis" required>
                        </div>

                        <!-- Satisfactory Checkbox -->
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="satisfactory_check" name="satisfactory_check">
                            <label class="form-check-label" for="satisfactory_check">
                                Require Satisfactory Sanction?
                            </label>
                        </div>

                        <!-- Satisfactory Doctor Dropdown -->
                        <div class="col-md-6 mb-3" id="ss_dr_div" style="display:none;">
                            <label class="form-label">Doctor (Satisfactory Sanction)</label>
                            <select class="form-select" name="ss_dr" id="ss_dr">
                                <option value="">Select Doctor</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}">
                                        Dr. {{ $doctor->name ?? 'NL' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Submit -->
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Save Session</button>
                        </div>
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

<script>
document.addEventListener('DOMContentLoaded', function(){
    // Update Patient & Doctor names when checkup changes
    const checkups = @json($checkups->keyBy('id'));
    document.getElementById('checkup_id').addEventListener('change', function(){
        const selectedCheckupId = this.value;
        if(checkups[selectedCheckupId]){
            const doctor = checkups[selectedCheckupId].doctor;
            document.getElementById('doctor_name').value = doctor ? doctor.first_name + ' ' + doctor.last_name : '';
            document.getElementById('doctor_id').value = doctor ? doctor.id : '';
            document.getElementById('patient_name').value = checkups[selectedCheckupId].patient?.name ?? '';
        } else {
            document.getElementById('doctor_name').value = '';
            document.getElementById('doctor_id').value = '';
            document.getElementById('patient_name').value = '';
        }
    });

    // Satisfactory Checkbox Logic
    const checkbox = document.getElementById('satisfactory_check');
    const ssDiv = document.getElementById('ss_dr_div');
    const ssSelect = document.getElementById('ss_dr');

    checkbox.addEventListener('change', function(){
        if(this.checked){
            ssDiv.style.display = 'block';  // show dropdown
            ssSelect.required = true;       // make it required
        } else {
            ssDiv.style.display = 'none';   // hide dropdown
            ssSelect.required = false;      // not required
            ssSelect.value = '';            // reset value
        }
    });
});
</script>
@endpush
