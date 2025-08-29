@extends('layouts.app')
@section('title')
    Patient Card Print
@endsection
@push('css')
    <link href="{{ URL::asset('build/plugins/input-tags/css/tagsinput.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            width: 100%;
        }
        .card-container {
            display: flex;
            justify-content: center;
            width: 100%;
        }
        .patient-card {
            width: 380px;
            height: 240px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            background: white;
            display: flex;
            flex-direction: column;
            position: relative;
        }
        .card-header {
            background: linear-gradient(135deg, #1976d2 0%, #0d47a1 100%);
            color: white;
            padding: 12px 20px;
            text-align: center;
            position: relative;
        }
        .card-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, #d32f2f 0%, #b71c1c 100%);
        }
        .card-content {
            flex: 1;
            padding: 15px;
            display: flex;
        }
        .left-section {
            width: 30%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding-right: 1px;
            border-right: 1px dashed #ccc;
        }
        .right-section {
            width: 70%;
            padding-left: 15px;
        }
        .patient-img {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
            margin-bottom: 8px;
        }
        .patient-id {
            font-weight: bold;
            color: #0d47a1;
            font-size: 0.9rem;
            background: rgba(255, 255, 255, 0.9);
            padding: 3px 10px;
            border-radius: 15px;
            display: inline-block;
        }
        .card-title {
            font-weight: 700;
            color: #0d47a1;
            margin-bottom: 5px;
            font-size: 1.2rem;
        }
        .p-title{
            font-weight: 700;
            color: #0d47a1;
            margin-bottom: 5px;
            font-size: 1.2rem;
        }
        .card-detail {
            font-size: 0.85rem;
            margin-bottom: 4px;
            color: #37474f;
        }
        .card-label {
            font-weight: 600;
            color: #d32f2f;
        }
        .icon-blue {
            color: #1976d2;
        }
        .icon-red {
            color: #d32f2f;
        }
        .card-footer {
            background: #f8f9fa;
            padding: 12px 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 3px solid #d32f2f;
            height: 55px;
        }
        .footer-item {
            display: flex;
            align-items: center;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 5px;
        }
        .footer-icon {
            margin-right: 3px;
            font-size: 0.8rem;
        }
        .phone-info {
            color: #1976d2;

        }
        .branch-info {
            color: #d32f2f;

        }
        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 40px;
        }
        .btn-print {
            background: linear-gradient(135deg, #1976d2 0%, #d32f2f 100%);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 30px;
            font-weight: 600;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .btn-print:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
        }
        /* Print Styles */
        /* Print Styles */
         /* Print Styles - Force background colors to print */
        @media print {
            body, html {
                width: 100% !important;
                height: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                background: white !important;
            }

            body * {
                visibility: hidden;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .patient-card, .patient-card * {
                visibility: visible;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .patient-card {
                position: absolute !important;
                left: 50% !important;
                top: 10% !important;
                transform: translate(-50%, -50%) !important;
                margin: 0 !important;
                box-shadow: none !important;
                width: 360px !important;
                height: 230px !important;
                page-break-inside: avoid !important;
            }

            .card-header {
                background: linear-gradient(135deg, #1976d2 0%, #0d47a1 100%) !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .card-header::after {
                background: linear-gradient(90deg, #d32f2f 0%, #b71c1c 100%) !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .header, .action-buttons, .header *, .action-buttons * {
                display: none !important;
            }

            /* Ensure all colors print correctly */
            .patient-id, .icon-blue, .phone-info, .p-title {
                color: #1976d2 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .card-label, .icon-red, .branch-info, .card-header::after {
                color: #d32f2f !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .card-title{
                color: #ffffff !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .left-section {
                width: 30%;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                padding-right: 1px;
                border-right: 1px dashed #ccc;
            }
        }


    </style>
@endpush
@section('content')
    <x-page-title title="Patient" subtitle="Details" />

{{--------------------------- New Card Designe ---------------}}


         <div class="card-container">
            <div class="patient-card">
                <div class="card-header">
                    <h5 class="card-title mb-0 text-white">HOSPITAL ID CARD</h5>
                </div>
                <div class="card-content">
                    <div class="left-section">
                        <img src="https://ui-avatars.com/api/?name=Ali+Raza&background=1976d2&color=fff&size=400" class="patient-img">
                        <span class="patient-id">PSN: {{ $patient->id ?? 'N/A' }}1233</span>
                    </div>
                    <div class="right-section">
                        <h6 class="p-title">{{ $patient->name ?? 'N/A' }}</h6>
                        <p class="card-detail"><span class="card-label">F/H Name:</span> {{ $patient->guardian_name ?? 'N/A' }}</p>

                        <div class="row mt-2">
                            <div class="col-12">
                                <p class="card-detail mb-1">
                                    <i class="fas fa-calendar-alt icon-blue me-1"></i>
                                    {{ $patient->created_at->format('d M Y') ?? 'N/A' }}
                                </p>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <p class="card-detail mb-1">
                                    <i class="fas fa-hospital icon-blue me-1"></i>
                                    {{ $patient->branch->name ?? 'N/A' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="footer-item phone-info">
                        <i class="fas fa-phone-alt footer-icon"></i>
                        <span>{{ $patient->branch->phone ?? 'N/A' }}</span>
                    </div>
                    <div class="footer-item branch-info">
                        <i class="fas fa-hospital footer-icon"></i>
                        <span>{{ $patient->branch->address ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="action-buttons">
            <button class="btn-print" onclick="window.print()">
                <i class="fas fa-print me-2"></i> Print Card
            </button>
        </div>



@endsection

@push('script')
    <!--plugins-->
    <script src="{{ URL::asset('build/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
    <script src="{{ URL::asset('build/plugins/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ URL::asset('build/plugins/input-tags/js/tagsinput.js') }}"></script>
    <script src="{{ URL::asset('build/plugins/simplebar/js/simplebar.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/main.js') }}"></script>


     <script>
        function printCard() {
            window.print();
        }

        // Simple hover effect
        document.querySelectorAll('.patient-card').forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'translateY(-8px)';
                card.style.boxShadow = '0 15px 35px rgba(0,0,0,0.2)';
            });

            card.addEventListener('mouseleave', () => {
                card.style.transform = 'translateY(0)';
                card.style.boxShadow = '0 10px 30px rgba(0,0,0,0.15)';
            });
        });
    </script>
@endpush

