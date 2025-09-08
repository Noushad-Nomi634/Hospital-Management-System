@extends('layouts.app')

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <style>
        :root {
            --doctor-bg: #e3f2fd;
            --doctor-color: #1565c0;
            --patient-bg: #ffebee;
            --patient-color: #c62828;
            --checkup-bg: #e8f5e9;
            --checkup-color: #2e7d32;
            --session-bg: #fff8e1;
            --session-color: #f9a825;
            --payment-bg: #f3e5f5;
            --payment-color: #7b1fa2;
            --clinic-header: #f5f5f5;
            --overview-bg: #fafafa;
        }


        .header {
            padding: 0.5rem 0;
            margin-bottom: 0.8rem;
            border-bottom: 1px solid #ddd;
        }

        .clinics-row {
            display: flex;
            gap: 0.8rem;
            overflow-x: auto;
            padding-bottom: 0.5rem;
        }

        .clinic-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
            min-width: 300px;
            flex: 1;
            transition: transform 0.2s ease;
        }

        .clinic-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        }

        .clinic-header {
            background: rgb(5, 5, 175);
            padding: 0.7rem 0.9rem;
            border-bottom: 1px solid #ddd;
            font-weight: 600;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 8px 8px 0 0;
        }

        .clinic-date {
            color: #ffffff;
            font-size: 0.75rem;
        }

        .clinic-content {
            padding: 0.9rem;
        }

        .stats-pair {
            display: flex;
            margin-bottom: 0.9rem;
            gap: 0.8rem;
        }

        .stat-box {
            flex: 1;
            text-align: center;
            padding: 0.8rem 0.4rem;
            border-radius: 6px;
            transition: all 0.2s ease;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .stat-box.doctor {
            background: var(--doctor-bg);
            color: var(--doctor-color);
            border-left: 4px solid var(--doctor-color);
        }

        .stat-box.patient {
            background: var(--patient-bg);
            color: var(--patient-color);
            border-left: 4px solid var(--patient-color);
        }

        .stat-box.checkup {
            background: var(--checkup-bg);
            color: var(--checkup-color);
            border-left: 4px solid var(--checkup-color);
        }

        .stat-box.session {
            background: var(--session-bg);
            color: var(--session-color);
            border-left: 4px solid var(--session-color);
        }

        .stat-box:hover {
            transform: scale(1.03);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.2rem;
        }

        .stat-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
        }

        .stats-row {
            display: flex;
            gap: 0.8rem;
            margin-bottom: 0.8rem;
        }

        .stat-item {
            flex: 1;
            text-align: center;
            padding: 0.7rem 0.3rem;
            border-radius: 6px;
            transition: all 0.2s ease;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .stat-item.payment {
            background: var(--payment-bg);
            color: var(--payment-color);
            border-left: 4px solid var(--payment-color);
        }

        .stat-item:hover {
            transform: scale(1.03);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }

        .stat-item-value {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 0.2rem;
        }

        .stat-item-label {
            font-size: 0.65rem;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
        }

        .overview-section {
            background: var(--overview-bg);
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
            margin-top: 1.2rem;
        }

        .section-title {
            font-weight: 600;
            margin-bottom: 0.9rem;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.95rem;
            background: rgb(5, 5, 175);
            padding: 0.7rem 0.9rem;
            border-bottom: 1px solid #ddd;
            border-radius: 8px 8px 0 0;
        }

        .overview-stats {
            display: flex;
            gap: 0.9rem;
            padding: 0.9rem;
        }

        .overview-stat {
            text-align: center;
            padding: 0.8rem 0.5rem;
            border-radius: 6px;
            flex: 1;
            transition: all 0.2s ease;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .overview-stat.doctor {
            background: var(--doctor-bg);
            color: var(--doctor-color);
            border-left: 4px solid var(--doctor-color);
        }

        .overview-stat.patient {
            background: var(--patient-bg);
            color: var(--patient-color);
            border-left: 4px solid var(--patient-color);
        }

        .overview-stat.checkup {
            background: var(--checkup-bg);
            color: var(--checkup-color);
            border-left: 4px solid var(--checkup-color);
        }

        .overview-stat.session {
            background: var(--session-bg);
            color: var(--session-color);
            border-left: 4px solid var(--session-color);
        }

        .overview-stat.payment {
            background: var(--payment-bg);
            color: var(--payment-color);
            border-left: 4px solid var(--payment-color);
        }

        .overview-stat:hover {
            transform: scale(1.03);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }

        .overview-value {
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: 0.2rem;
        }

        .overview-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
        }

        @media (max-width: 992px) {
            .clinics-row {
                flex-direction: column;
            }

            .clinic-card {
                min-width: auto;
            }

            .overview-stats {
                flex-wrap: wrap;

            }

            .overview-stat {
                flex: 1 0 40%;
            }
        }

        @media (max-width: 576px) {
            .stats-pair {
                flex-direction: column;
            }

            .overview-stat {
                flex: 1 0 100%;
            }
        }
    </style>

@section('title')
    Clinic Dashboard
@endsection

@section('content')

    <div class="dashboard-container">
        <!-- Header -->
        <div class="header">
            <h1 class="h5 mb-0">Clinic Management Dashboard</h1>
        </div>

        <!-- Clinics Row -->
        <div class="clinics-row">
            @foreach($branchStats as $branch)
                <div class="clinic-card">
                    <div class="clinic-header text-white">
                        <span>{{ $branch['branch_name'] }}</span>
                        <span class="clinic-date">{{ \Carbon\Carbon::now()->format('d M Y') }}</span>
                    </div>
                    <div class="clinic-content">
                        <div class="stats-pair">
                            <div class="stat-box doctor">
                                <div class="stat-value">{{ $branch['totalDoctors'] ?? 0 }}</div>
                                <div class="stat-label">
                                    <i class="bi bi-person-badge"></i>Doctors
                                </div>
                            </div>
                            <div class="stat-box patient">
                                <div class="stat-value">{{ $branch['totalPatients'] ?? 0 }}</div>
                                <div class="stat-label">
                                    <i class="bi bi-people"></i>Patients
                                </div>
                            </div>
                        </div>
                        <div class="stats-pair">
                            <div class="stat-box checkup">
                                <div class="stat-value">{{ $branch['totalCheckups'] ?? 0 }}</div>
                                <div class="stat-label">
                                    <i class="bi bi-clipboard-check"></i>Checkups
                                </div>
                            </div>
                            <div class="stat-box session">
                                <div class="stat-value">{{ $branch['totalSessionsToday'] ?? 0 }}</div>
                                <div class="stat-label">
                                    <i class="bi bi-calendar-event"></i>Sessions
                                </div>
                            </div>
                        </div>
                        <div class="stats-row">
                            <div class="stat-item payment">
                                <div class="stat-item-value">{{ number_format(($branch['checkupPaymentsToday'] ?? 0) + ($branch['sessionPaymentsToday'] ?? 0), 0) }}</div>
                                <div class="stat-item-label">
                                    <i class="bi bi-cash-coin"></i>Payments
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>

        <!-- Overall Branches -->
        <div class="overview-section">
            <h2 class="section-title">
                <i class="bi bi-graph-up"></i>
                Overall Branches
            </h2>
            <div class="overview-stats">
                <div class="overview-stat doctor">
                    <div class="overview-value">0</div>
                    <div class="overview-label">
                        <i class="bi bi-person-badge"></i>Doctors
                    </div>
                </div>
                <div class="overview-stat patient">
                    <div class="overview-value">41</div>
                    <div class="overview-label">
                        <i class="bi bi-people"></i>Patients
                    </div>
                </div>
                <div class="overview-stat checkup">
                    <div class="overview-value">5</div>
                    <div class="overview-label">
                        <i class="bi bi-clipboard-check"></i>Checkups
                    </div>
                </div>
                <div class="overview-stat session">
                    <div class="overview-value">0</div>
                    <div class="overview-label">
                        <i class="bi bi-calendar-event"></i>Sessions
                    </div>
                </div>
                <div class="overview-stat payment">
                    <div class="overview-value">0</div>
                    <div class="overview-label">
                        <i class="bi bi-cash-coin"></i>Payments
                    </div>
                </div>
            </div>
        </div>
    </div>

















    <!-- Page Heading -->
    <div class="dashboard-title">
        <h2>Dashboard</h2>
        <p>Clinic Management</p>
    </div>

    <!-- All Branches -->
    <div class="branches-wrapper">
        @foreach($branchStats as $branch)
            <div class="branch-box">
                <div class="branch-header">
                    <h5>{{ $branch['branch_name'] }}</h5>
                    <small>{{ \Carbon\Carbon::now()->format('d M Y') }}</small>
                </div>
                <div class="stats-grid">
                    <div class="stat-card stat-blue">
                        <h6>{{ $branch['totalDoctors'] ?? 0 }}</h6>
                        <p>Doctors</p>
                    </div>
                    <div class="stat-card stat-green">
                        <h6>{{ $branch['totalPatients'] ?? 0 }}</h6>
                        <p>Patients</p>
                    </div>
                    <div class="stat-card stat-cyan">
                        <h6>{{ $branch['totalCheckups'] ?? 0 }}</h6>
                        <p>Checkups</p>
                    </div>
                    <div class="stat-card stat-orange">
                        <h6>{{ $branch['totalSessionsToday'] ?? 0 }}</h6>
                        <p>Sessions</p>
                    </div>
                    <div class="stat-card stat-total">
                        <h6>{{ number_format(($branch['checkupPaymentsToday'] ?? 0) + ($branch['sessionPaymentsToday'] ?? 0), 0) }}</h6>
                        <p>Total Payments</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Overall Stats Card -->
    @php
        $overallDoctors = collect($branchStats)->sum('totalDoctors');
        $overallPatients = collect($branchStats)->sum('totalPatients');
        $overallCheckups = collect($branchStats)->sum('totalCheckups');
        $overallSessions = collect($branchStats)->sum('totalSessionsToday');
        $overallPayments = collect($branchStats)->sum(fn($b) => ($b['checkupPaymentsToday'] ?? 0) + ($b['sessionPaymentsToday'] ?? 0));
    @endphp

    <!-- Overall Heading -->
    <div class="overall-title">
        Overall Branches
    </div>

    <div class="overall-box">
        <div class="overall-header">
            <h4>Overall Branches</h4>
        </div>
        <div class="stats-grid">
            <div class="stat-card stat-blue">
                <h6>{{ $overallDoctors }}</h6>
                <p>Doctors</p>
            </div>
            <div class="stat-card stat-green">
                <h6>{{ $overallPatients }}</h6>
                <p>Patients</p>
            </div>
            <div class="stat-card stat-cyan">
                <h6>{{ $overallCheckups }}</h6>
                <p>Checkups</p>
            </div>
            <div class="stat-card stat-orange">
                <h6>{{ $overallSessions }}</h6>
                <p>Sessions</p>
            </div>
            <div class="stat-card stat-total">
                <h6>{{ number_format($overallPayments, 0) }}</h6>
                <p>Total Payments</p>
            </div>
        </div>
    </div>
@endsection

@push('script')

<!-- Bootstrap JS Bundle with Popper -->

    <script src="{{ URL::asset('build/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
    <script src="{{ URL::asset('build/plugins/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/main.js') }}"></script>

@endpush
