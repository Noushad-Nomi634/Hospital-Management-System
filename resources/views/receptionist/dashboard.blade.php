@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Receptionist Dashboard</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Today Consultations</th>
                <th>Today Sessions</th>
                <th>Total Payments in Hand</th>
                <th>Today Payment</th>
                <th>Last 30 Days Income</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <!-- Today Consultations -->
                <td>
                    Total: {{ $todayConsultations->count() }} <br>
                    Fees: {{ number_format($todayConsultationFee, 2) }}
                </td>

                <!-- Today Sessions -->
                <td>
                    Total: {{ $totalTodaySessions }} <br>
                    Fees: {{ number_format($todaySessionFee, 2) }}
                </td>

                <!-- Total Payments in Hand -->
                <td>
                    {{ number_format($totalPaymentsInHand, 2) }}
                </td>

                <!-- Today Payment Breakdown -->
                <td>
                    Cash: {{ number_format($todayCashPayments, 2) }} <br>
                    Online: {{ number_format($todayOnlinePayments, 2) }}
                </td>

                <!-- Last 30 Days Income -->
                <td>
                    {{ number_format($last30DaysIncome, 2) }}
                </td>
            </tr>
        </tbody>
    </table>
</div>
@endsection
