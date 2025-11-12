@extends('layouts.app')

@section('content')
<div class="container mt-4">

    <h4>🧾 Ledger Report</h4>

    <!-- 🔍 Filter Form -->
    <form action="{{ route('ledger.filter') }}" method="GET" class="row g-3 mb-3">
        <div class="col-md-3">
            <label>Branch</label>
            <select name="branch_id" class="form-control">
                <option value="all">All Branches</option>
                @foreach($branches as $branch)
                    <option value="{{ $branch->id }}" {{ isset($branch_id) && $branch_id == $branch->id ? 'selected' : '' }}>
                        {{ $branch->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <label>From Date</label>
            <input type="date" name="from_date" value="{{ $from_date ?? '' }}" class="form-control">
        </div>

        <div class="col-md-3">
            <label>To Date</label>
            <input type="date" name="to_date" value="{{ $to_date ?? '' }}" class="form-control">
        </div>

        <div class="col-md-3 align-self-end">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
        </div>
    </form>

    <!-- 📋 Ledger Table -->
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Date</th>
                <th>Payment Method</th>
                <th>Branch</th>
                <th>Type</th>
                <th>Amount</th>
                <th>Post Branch Balance</th>
                <th>Remarks</th>
            </tr>
        </thead>

        <tbody>
            @php
                $totalIncome = 0;
                $totalExpense = 0;
                $totalTransferIn = 0;
                $totalTransferOut = 0;

                // 🔹 Split opening balance and other transactions
                $openingTransactions = $transactions->where('payment_type', 0); // opening balance type = 0
                $otherTransactions   = $transactions->where('payment_type', '!=', 0)->sortBy('id'); // oldest first
            @endphp

            {{-- 🔹 Opening Balance on top --}}
            @foreach($openingTransactions as $t)
                @php
                    $branchName = $branches->firstWhere('id', $t->branch_id)->name ?? 'N/A';
                @endphp
                <tr>
                    <td>{{ \Carbon\Carbon::parse($t->created_at)->format('d-m-Y') }}</td>
                    <td>{{ ucfirst($t->payment_method ?? 'N/A') }}</td>
                    <td>{{ $branchName }}</td>
                    <td>Opening Balance</td>
                    <td class="text-secondary">{{ number_format($t->amount, 2) }}</td>
                    <td>{{ number_format($t->post_branch_balance ?? 0, 2) }}</td>
                    <td>{{ $t->Remx ?? '—' }}</td>
                </tr>
            @endforeach

            {{-- 🔹 Other transactions below opening balance --}}
            @foreach($otherTransactions as $t)
                @php
                    if (in_array($t->payment_type, [1,2])) {
                        $typeLabel = 'Income';
                        $totalIncome += (float)$t->amount;
                    } elseif (in_array($t->payment_type, [3,4,5])) {
                        $typeLabel = 'Expense';
                        $totalExpense += (float)$t->amount;
                    } elseif ($t->payment_type == 6) {
                        if ($t->type == '+') {
                            $typeLabel = 'Transfer In';
                            $totalTransferIn += (float)$t->amount;
                        } elseif ($t->type == '-') {
                            $typeLabel = 'Transfer Out';
                            $totalTransferOut += (float)$t->amount;
                        } else {
                            $typeLabel = 'Transfer';
                        }
                    } else {
                        $typeLabel = 'Other';
                    }

                    $branchName = $branches->firstWhere('id', $t->branch_id)->name ?? 'N/A';
                @endphp
                <tr>
                    <td>{{ \Carbon\Carbon::parse($t->created_at)->format('d-m-Y') }}</td>
                    <td>{{ ucfirst($t->payment_method ?? 'N/A') }}</td>
                    <td>{{ $branchName }}</td>
                    <td>{{ $typeLabel }}</td>
                    <td>
                        @if($typeLabel == 'Income')
                            <span class="text-success">+ {{ number_format($t->amount, 2) }}</span>
                        @elseif($typeLabel == 'Expense')
                            <span class="text-danger">- {{ number_format($t->amount, 2) }}</span>
                        @elseif($typeLabel == 'Transfer In')
                            <span class="text-primary">+ {{ number_format($t->amount, 2) }}</span>
                        @elseif($typeLabel == 'Transfer Out')
                            <span class="text-danger">- {{ number_format($t->amount, 2) }}</span>
                        @else
                            {{ number_format($t->amount, 2) }}
                        @endif
                    </td>
                    <td>{{ number_format($t->post_branch_balance ?? 0, 2) }}</td>
                    <td>{{ $t->Remx ?? '—' }}</td>
                </tr>
            @endforeach
        </tbody>

        {{-- ✅ Totals without Opening Balance --}}
        @if($otherTransactions->count() > 0)
        <tfoot class="table-light fw-bold">
            <tr>
                <td colspan="4" class="text-end">Total Income:</td>
                <td class="text-success">+ {{ number_format($totalIncome, 2) }}</td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td colspan="4" class="text-end">Total Expense:</td>
                <td class="text-danger">- {{ number_format($totalExpense, 2) }}</td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td colspan="4" class="text-end">Total Transfer In:</td>
                <td class="text-primary">+ {{ number_format($totalTransferIn, 2) }}</td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td colspan="4" class="text-end">Total Transfer Out:</td>
                <td class="text-danger">- {{ number_format($totalTransferOut, 2) }}</td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td colspan="4" class="text-end">Net Balance:</td>
                <td class="{{ ($totalIncome + $totalTransferIn - $totalExpense - $totalTransferOut) >= 0 ? 'text-success' : 'text-danger' }}">
                    {{ number_format($totalIncome + $totalTransferIn - $totalExpense - $totalTransferOut, 2) }}
                </td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
        @endif
    </table>

</div>
@endsection
