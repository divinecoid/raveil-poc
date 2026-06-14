<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Finance Report</title>
    <style>
        body { font-family: sans-serif; font-size: 13px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .summary-table { width: 100%; margin-bottom: 30px; border-collapse: collapse; }
        .summary-table td { padding: 10px; border: 1px solid #ddd; font-size: 14px; }
        .summary-table th { padding: 10px; border: 1px solid #ddd; background-color: #f8f9fa; font-size: 14px; text-align: left; }
        .data-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .data-table th, .data-table td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        .data-table th { background-color: #f2f2f2; }
        .text-right { text-align: right; }
        .text-success { color: green; }
        .text-danger { color: red; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Finance Report</h1>
        <p>Period: {{ \Carbon\Carbon::parse($start)->format('d M Y') }} to {{ \Carbon\Carbon::parse($end)->format('d M Y') }}</p>
    </div>

    <table class="summary-table">
        <tr>
            <th>Total Income</th>
            <td class="text-right text-success">Rp {{ number_format($totalIncome, 0, ',', '.') }}</td>
            <th>Total Expenses</th>
            <td class="text-right text-danger">Rp {{ number_format($totalExpense, 0, ',', '.') }}</td>
            <th>Net Profit</th>
            <td class="text-right {{ $netProfit >= 0 ? 'text-success' : 'text-danger' }}">
                <strong>Rp {{ number_format($netProfit, 0, ',', '.') }}</strong>
            </td>
        </tr>
    </table>

    <table class="summary-table">
        <tr>
            <th>Account Receivable (Piutang)</th>
            <td class="text-right text-success">Rp {{ number_format($totalReceivable, 0, ',', '.') }}</td>
            <th>Account Payable (Hutang)</th>
            <td class="text-right text-danger">Rp {{ number_format($totalPayable, 0, ',', '.') }}</td>
        </tr>
    </table>

    <h3>Income Breakdown (Paid Invoices)</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Invoice No</th>
                <th>Customer</th>
                <th class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            @forelse($invoices as $inv)
            <tr>
                <td>{{ \Carbon\Carbon::parse($inv->issue_date)->format('d M Y') }}</td>
                <td>{{ $inv->invoice_number }}</td>
                <td>{{ $inv->customer ? $inv->customer->name : 'N/A' }}</td>
                <td class="text-right">Rp {{ number_format($inv->total, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align: center;">No income recorded in this period.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <h3>Expenses Breakdown (Paid)</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Category</th>
                <th>Description</th>
                <th class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            @forelse($expenses as $exp)
            <tr>
                <td>{{ \Carbon\Carbon::parse($exp->date)->format('d M Y') }}</td>
                <td>{{ $exp->category }}</td>
                <td>{{ $exp->description ?: '-' }}</td>
                <td class="text-right">Rp {{ number_format($exp->amount, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align: center;">No expenses recorded in this period.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <h3>Account Receivable (Piutang / Unpaid Invoices)</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Invoice No</th>
                <th>Customer</th>
                <th class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            @forelse($receivables as $inv)
            <tr>
                <td>{{ \Carbon\Carbon::parse($inv->issue_date)->format('d M Y') }}</td>
                <td>{{ $inv->invoice_number }}</td>
                <td>{{ $inv->customer ? $inv->customer->name : 'N/A' }}</td>
                <td class="text-right">Rp {{ number_format($inv->total, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align: center;">No unpaid invoices in this period.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <h3>Account Payable (Hutang / Unpaid Expenses)</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Category</th>
                <th>Description</th>
                <th class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payables as $exp)
            <tr>
                <td>{{ \Carbon\Carbon::parse($exp->date)->format('d M Y') }}</td>
                <td>{{ $exp->category }}</td>
                <td>{{ $exp->description ?: '-' }}</td>
                <td class="text-right">Rp {{ number_format($exp->amount, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align: center;">No unpaid expenses in this period.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
