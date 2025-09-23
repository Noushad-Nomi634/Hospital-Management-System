@extends('layouts.app')

@section('title')
    Patient Card Print
@endsection

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .card-container {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .patient-card {
            width: 380px;
            height: 250px;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            background: #fff;
            overflow: hidden;
            position: relative;
        }

        .card-header {
            background: linear-gradient(135deg, #1976d2, #0d47a1);
            color: #fff;
            text-align: center;
            padding: 10px;
            font-weight: bold;
            font-size: 1rem;
            position: relative;
        }

        .card-header::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, #d32f2f, #b71c1c);
        }

        .card-content {
            display: flex;
            padding: 12px;
        }

        .left-section {
            width: 35%;
            text-align: center;
            border-right: 1px dashed #ccc;
            padding-right: 8px;
        }

        /* QR Code replace avatar */
        .qr-code {
            margin: 10px auto;
        }

        .patient-id {
            display: inline-block;
            font-weight: bold;
            font-size: 0.85rem;
            color: #0d47a1;
            background: #e3f2fd;
            border-radius: 12px;
            padding: 2px 8px;
            margin-top: 4px;
        }

        .right-section {
            width: 65%;
            padding-left: 10px;
        }

        .p-title {
            font-size: 1rem;
            font-weight: bold;
            color: #1976d2;
            margin-bottom: 5px;
        }

        .card-detail {
            font-size: 0.8rem;
            margin-bottom: 3px;
            color: #37474f;
        }

        .card-label {
            font-weight: 600;
            color: #d32f2f;
        }

        .card-footer {
            background: #f8f9fa;
            padding: 8px 10px;
            border-top: 3px solid #d32f2f;
            font-size: 0.75rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .footer-item {
            display: flex;
            align-items: center;
            font-weight: 600;
        }

        .footer-icon {
            margin-right: 5px;
            color: #1976d2;
        }

        .branch-info {
            color: #d32f2f;
        }

        /* ✅ Print Styles */
        @media print {
            body * {
                visibility: hidden;
            }

            .patient-card,
            .patient-card * {
                visibility: visible;
            }

            .patient-card {
                position: absolute;
                top: 0;
                left: 0;
                margin: 0;
            }

            /* Force colors in print */
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color-adjust: exact !important;
            }
        }
    </style>
@endpush

@section('content')
    <x-page-title title="Patient" subtitle="Details" />

    <div class="card-container">
        <div class="patient-card">
            <div class="card-header d-flex align-items-center">
                <!-- Logo in circle -->
                <div style="width:45px; height:45px; border-radius:50%; background:#fff; display:flex; align-items:center; justify-content:center; overflow:hidden; margin-right:12px;">
                    <img src="{{ URL::asset('build/images/bodylogo.png') }}" alt="Logo" style="width:40px; height:40px; object-fit:contain;">
                </div>

                <!-- Hospital Name & Subtitle -->
                <div style="text-align:left; color:#fff;">
                    <div style="font-size:16px; font-weight:700; line-height:1.2;">
                        BODY EXPERTS
                    </div>
                    <div style="font-size:11px; font-weight:500; line-height:1.2;">
                        Ortho-Neuro-Sports Physiotherapy,<br>
                        Rehabilitation Center & Institute of Autism
                    </div>
                </div>
            </div>



            <div class="card-content">
                <!-- Left -->
                <div class="left-section">
                    <!-- QR Code -->
                    <div class="qr-code">
                        {!! QrCode::size(80)->generate($patient->mr ?? 'N/A') !!}
                    </div>
                    <span class="patient-id">MR:{{ $patient->mr ?? 'N/A' }}</span>
                </div>

                <!-- Right -->
                <div class="right-section">
                    <h6 class="p-title">{{ $patient->name ?? 'N/A' }}</h6>
                    <p class="card-detail"><span class="card-label">F/H Name:</span> {{ $patient->guardian_name ?? 'N/A' }}</p>
                    <p class="card-detail"><i class="fas fa-calendar-alt text-primary"></i>
                        {{ $patient->created_at->format('d M Y') ?? 'N/A' }}
                    </p>
                    <p class="card-detail"><i class="fas fa-map-marker-alt text-primary"></i>
                        {{ $patient->branch->address ?? 'N/A' }}
                    </p>
                </div>
            </div>

            <div class="card-footer">
                <div class="footer-item">
                    <i class="fas fa-phone-alt footer-icon"></i> {{ $patient->branch->phone ?? 'N/A' }}
                </div>
                <div class="footer-item branch-info">
                    <i class="fas fa-hospital  footer-icon"></i> {{ $patient->branch->name ?? 'N/A' }}
                </div>
            </div>
        </div>
    </div>

    <div class="text-center mt-4 no-print">
        <button class="btn btn-primary" onclick="window.print()">
            <i class="fas fa-print"></i> Print Card
        </button>
    </div>
@endsection
