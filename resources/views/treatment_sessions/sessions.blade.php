@extends('layouts.app')

@section('title', 'Treatment Slip & Sessions')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card p-3">
            <h4 class="text-center mb-4">Treatment Slip</h4>

            <!-- Ongoing Sessions Table -->
            <div class="table-responsive mb-4">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Patient Name</th>
                            <th>Age</th>
                            <th>Date</th>
                            <th>DR Consultation</th>
                            <th>Session DR</th>
                            <th>Note</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        <tr>
                            <td>{{ $patient->name ?? 'N/A' }}</td>
                            <td>{{ $patient->age ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($ongoingSessions->session_date)->format('d M Y - h:i A') }}</td>
                            <td>{{ $checkup->doctor->first_name ?? 'N/A' }} {{ $s->checkup->doctor->last_name ?? '' }}</td>
                            <td>{{ doctor_get_name($ongoingSessions->doctor_id)}} </td>
                            <td>{{ $ongoingSessions->note ?? '-' }}</td>
                            <td>
                                @if($ongoingSessions->con_status == 0) Pending
                                @elseif($ongoingSessions->con_status == 1) Ongoing
                                @elseif($ongoingSessions->con_status == 2) Completed
                                @elseif($ongoingSessions->con_status == 3) Cancelled
                                @endif
                            </td>
                        </tr>
                      
                    </tbody>
                </table>
            </div>

            <!-- Sessions Fee Summary Table -->
            <div class="table-responsive mb-4">
                <h5>Sessions Fee Summary</h5>
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Session Fee</th>
                            <th>Paid Amount</th>
                            <th>Dues Amount</th>
                            <th>Session Count</th>
                        </tr>
                    </thead>
                    {{-- <tbody>
                        @foreach($ongoingSessions as $s)
                        <tr>
                            <td>{{ $s->id }}</td>
                            <td>{{ $s->session_fee ?? 0 }}</td>
                            <td>{{ $s->paid_amount ?? 0 }}</td>
                            <td>{{ $s->dues_amount ?? 0 }}</td>
                            <td>{{ $s->session_count ?? 1 }}</td>
                        </tr>
                        @endforeach
                    </tbody> --}}
                </table>
            </div>

            <!-- Enrollment Update Form -->
            <div class="card mt-4 p-3">
                <h5>Add / Update Treatment Session for {{ $patient->name ?? 'Patient' }}</h5>

                <form method="POST" action="{{ route('treatment-sessions.enrollmentUpdate', $ongoingSessions->first()->id ?? 0) }}">
                    @csrf
                    @method('PUT')

                    <!-- Hidden IDs -->
                    <input type="hidden" name="session_id" value="{{ $ongoingSessions->first()->id ?? '' }}">
                    <input type="hidden" name="session_count" id="session_count_input" value="0">
                    <input type="hidden" name="dues_amount" id="dues_amount_input" value="0">

                    <!-- Session Fee -->
                    <div class="mb-3">
                        <label class="form-label">Total Session Fee</label>
                        <input type="number" class="form-control" name="session_fee" id="session_fee"
                               value="{{ old('session_fee', $ongoingSessions->first()->session_fee ?? 0) }}" min="0" required>
                    </div>

                    <!-- Paid Amount -->
                    <div class="mb-3">
                        <label class="form-label">Paid Amount</label>
                        <input type="number" class="form-control" name="paid_amount" id="paid_amount"
                               value="{{ old('paid_amount', $ongoingSessions->first()->paid_amount ?? 0) }}" min="0" required>
                    </div>

                    <!-- Fee Summary -->
                    <div class="card mb-3">
                        <div class="card-body bg-light">
                            <h5 class="card-title">Fee Summary</h5>
                            <p>Total Fee: <strong id="totalFee">0</strong></p>
                            <p>Per Session Fee: <strong id="perSessionFee">0</strong></p>
                            <p>Paid Amount: <strong id="paidAmount">0</strong></p>
                            <p>Due Amount: <strong id="dueAmount">0</strong></p>
                        </div>
                    </div>

                    <!-- Sessions Table -->
                    <div class="mb-3">
                        <label class="form-label">Session Dates & Times</label>
                        <table id="sessionTable" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Session Date</th>
                                    <th>Session Time</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                        <p class="mt-2">Total Sessions: <span id="sessionCount">0</span></p>
                    </div>

                    <!-- Submit -->
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">Save Session</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
let sessionIndex = 0;

function formatDate(date) {
    const d = new Date(date);
    return d.getFullYear() + '-' +
           String(d.getMonth()+1).padStart(2,'0') + '-' +
           String(d.getDate()).padStart(2,'0');
}

function addRow(button=null){
    let newDate = new Date();
    const rows = document.querySelectorAll('#sessionTable tbody tr');
    if(rows.length > 0){
        const lastDateInput = rows[rows.length-1].querySelector('input[type="date"]');
        const lastDate = new Date(lastDateInput.value);
        newDate = new Date(lastDate);
        newDate.setDate(newDate.getDate()+1);
    }

    const row = document.createElement('tr');
    row.innerHTML = `
        <td><input type="date" name="sessions[${sessionIndex}][date]" class="form-control" required value="${formatDate(newDate)}"></td>
        <td><input type="time" name="sessions[${sessionIndex}][time]" class="form-control" required value="12:00"></td>
        <td>
            <button type="button" class="btn btn-success btn-sm me-1" onclick="addRow(this)">➕</button>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">❌</button>
        </td>
    `;
    if(button){
        button.closest('tr').after(row);
    }else{
        document.querySelector('#sessionTable tbody').appendChild(row);
    }

    sessionIndex++;
    updateSessionCount();
    calculateFees();
}

function removeRow(button){
    button.closest('tr').remove();
    updateSessionCount();
    calculateFees();
}

function updateSessionCount(){
    const count = document.querySelectorAll('#sessionTable tbody tr').length;
    document.getElementById('sessionCount').innerText = count;
    document.getElementById('session_count_input').value = count; // ✅ hidden update
}

function calculateFees(){
    const sessionCount = document.querySelectorAll('#sessionTable tbody tr').length;
    const sessionFee = parseFloat(document.getElementById('session_fee').value) || 0;
    const paidAmount = parseFloat(document.getElementById('paid_amount').value) || 0;

    const perSession = sessionCount > 0 ? (sessionFee/sessionCount).toFixed(2) : 0;
    const due = (sessionFee - paidAmount).toFixed(2);

    document.getElementById('totalFee').innerText = sessionFee.toFixed(2);
    document.getElementById('perSessionFee').innerText = perSession;
    document.getElementById('paidAmount').innerText = paidAmount.toFixed(2);
    document.getElementById('dueAmount').innerText = due;
    document.getElementById('dues_amount_input').value = due; // ✅ hidden update
}

document.addEventListener('DOMContentLoaded', function(){
    addRow(); // add default first row
    document.getElementById('session_fee').addEventListener('input', calculateFees);
    document.getElementById('paid_amount').addEventListener('input', calculateFees);
});
</script>
@endpush
