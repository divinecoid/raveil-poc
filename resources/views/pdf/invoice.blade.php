<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        .header { text-align: center; margin-bottom: 30px; }
        .details { margin-bottom: 20px; }
        .details table { width: 100%; }
        .details td { padding: 5px; }
        .items { width: 100%; border-collapse: collapse; }
        .items th, .items td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .items th { background-color: #f2f2f2; }
        .totals { margin-top: 20px; text-align: right; }
        .totals table { float: right; width: 300px; }
        .totals td { padding: 5px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>INVOICE</h1>
    </div>

    <div class="details">
        <table>
            <tr>
                <td><strong>Invoice No:</strong> {{ $invoice->invoice_number }}</td>
                <td style="text-align: right;"><strong>Date:</strong> {{ $invoice->issue_date ? \Carbon\Carbon::parse($invoice->issue_date)->format('d M Y') : 'N/A' }}</td>
            </tr>
            <tr>
                <td><strong>Customer:</strong> {{ $invoice->customer ? $invoice->customer->name : 'N/A' }}</td>
                <td style="text-align: right;"><strong>Due Date:</strong> {{ $invoice->due_date ? \Carbon\Carbon::parse($invoice->due_date)->format('d M Y') : 'N/A' }}</td>
            </tr>
            <tr>
                <td><strong>Status:</strong> <span style="text-transform: capitalize;">{{ $invoice->status }}</span></td>
                <td></td>
            </tr>
        </table>
    </div>

    @php
        $products = $invoice->items->where('type', 'product');
        $services = $invoice->items->where('type', 'service');
    @endphp

    @if($products->count() > 0)
    <h3 style="margin-bottom: 10px;">Products / Parts</h3>
    <table class="items" style="margin-bottom: 20px;">
        <thead>
            <tr>
                <th>Description</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $item)
            <tr>
                <td>{{ $item->description }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->unit_price, 2) }}</td>
                <td>{{ number_format($item->subtotal, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    @if($services->count() > 0)
    <h3 style="margin-bottom: 10px;">Services / Labor</h3>
    <table class="items">
        <thead>
            <tr>
                <th>Description</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($services as $item)
            <tr>
                <td>{{ $item->description }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->unit_price, 2) }}</td>
                <td>{{ number_format($item->subtotal, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="totals">
        <table>
            <tr>
                <td><strong>Subtotal:</strong></td>
                <td>{{ number_format($invoice->subtotal, 2) }}</td>
            </tr>
            <tr>
                <td><strong>Tax:</strong></td>
                <td>{{ number_format($invoice->tax, 2) }}</td>
            </tr>
            <tr>
                <td><strong>Total:</strong></td>
                <td><strong>{{ number_format($invoice->total, 2) }}</strong></td>
            </tr>
        </table>
    </div>
</body>
</html>
