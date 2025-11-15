@extends('layouts.app')

<!-- CSS Plugins -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">

@section('title', 'Receptionist Dashboard')

@section('content')
<div class="container mt-3">

    <!-- Parent Card -->
    <div class="card-header bg-light text-dark d-flex justify-content-between align-items-center">
        <h5 class="mb-1">
            Welcome, {{ Auth::user()->name }} 
            <small class="text-muted ms-5">( {{ $branch }} )</small>
        </h5>
        <small>{{ \Carbon\Carbon::now()->format('d M Y') }}</small>
    </div>

    <div class="card-body">
        <div class="row g-4 text-center">

            <!-- Today Appointments -->
            <div class="col-6 col-md-3">
                <div class="bg-light rounded shadow-sm p-3 d-flex align-items-center justify-content-center">
                    <i class="fas fa-calendar-alt fa-2x text-primary me-2"></i>
                    <div>
                        <h5 class="mb-0">{{ $todayAppointmentsCount }}</h5>
                        <small class="text-muted">Today Appointments</small>
                        <small class="d-block">Fees: ₨{{ number_format($todayAppointmentsFee, 2) }} 🇵🇰</small>
                    </div>
                </div>
            </div>

            <!-- Pending Satisfactory Sessions -->
            <div class="col-6 col-md-3">
                <div class="bg-light rounded shadow-sm p-3 d-flex align-items-center justify-content-center">
                    <i class="fas fa-clock fa-2x text-warning me-2"></i>
                    <div>
                        <h5 class="mb-0">{{ $todayPendingSatisfactorySessions }}</h5>
                        <small class="text-muted">Pending Satisfactory Sessions</small>
                    </div>
                </div>
            </div>

            <!-- Completed Satisfactory Sessions -->
            <div class="col-6 col-md-3">
                <div class="bg-light rounded shadow-sm p-3 d-flex align-items-center justify-content-center">
                    <i class="fas fa-check fa-2x text-success me-2"></i>
                    <div>
                        <h5 class="mb-0">{{ $todayCompletedSatisfactorySessions }}</h5>
                        <small class="text-muted">Completed Satisfactory Sessions</small>
                    </div>
                </div>
            </div>

            <!-- Today Sessions -->
            <div class="col-6 col-md-3">
                <div class="bg-light rounded shadow-sm p-3 d-flex align-items-center justify-content-center">
                    <i class="fas fa-calendar-check fa-2x text-info me-2"></i>
                    <div>
                        <h5 class="mb-0">{{ $todaySessionsCount }}</h5>
                        <small class="text-muted">Today Sessions</small>
                        <small class="d-block">Fees: ₨{{ number_format($todaySessionsFee, 2) }} 🇵🇰</small>
                    </div>
                </div>
            </div>

            <!-- Enrollment Pending -->
            <div class="col-6 col-md-3">
                <div class="bg-light rounded shadow-sm p-3 d-flex align-items-center justify-content-center">
                    <i class="fas fa-user-clock fa-2x text-danger me-2"></i>
                    <div>
                        <h5 class="mb-0">{{ $enrollmentPending }}</h5>
                        <small class="text-muted">Enrollment Pending</small>
                    </div>
                </div>
            </div>

            <!-- Enrollment Completed -->
            <div class="col-6 col-md-3">
                <div class="bg-light rounded shadow-sm p-3 d-flex align-items-center justify-content-center">
                    <i class="fas fa-user-check fa-2x text-success me-2"></i>
                    <div>
                        <h5 class="mb-0">{{ $enrollmentCompleted }}</h5>
                        <small class="text-muted">Enrollment Completed</small>
                    </div>
                </div>
            </div>

            <!-- Pending Invoices -->
            <div class="col-6 col-md-3">
                <div class="bg-light rounded shadow-sm p-3 d-flex align-items-center justify-content-center">
                    <i class="fas fa-file-invoice-dollar fa-2x text-secondary me-2"></i>
                    <div>
                        <h5 class="mb-0">{{ $pendingInvoicesCount }}</h5>
                        <small class="text-muted">Pending Invoices</small>
                        <small class="d-block">Total: ₨{{ number_format($pendingInvoicesTotal, 2) }} 🇵🇰</small>
                    </div>
                </div>
            </div>

            <!-- Today Payments Received -->
            <div class="col-6 col-md-3">
                <div class="bg-light rounded shadow-sm p-3 d-flex align-items-center justify-content-center">
                    <i class="fas fa-money-bill-wave fa-2x text-dark me-2"></i> <!-- Dollar icon replaced -->
                    <div>
                        <h5 class="mb-0">₨{{ number_format($todayPayments, 2) }} 🇵🇰</h5>
                        <small class="text-muted">Today Payments Received</small>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('script')
<!-- Font Awesome JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>

<!-- Other JS Plugins -->
<script src="{{ URL::asset('build/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
<script src="{{ URL::asset('build/plugins/metismenu/metisMenu.min.js') }}"></script>
<script src="{{ URL::asset('build/js/main.js') }}"></script>
@endpush
