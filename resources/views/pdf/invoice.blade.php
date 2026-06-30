<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        @font-face {
            font-family: 'Michroma';
            src: url('{{ public_path('fonts/michroma/Michroma-Regular.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        @font-face {
            font-family: 'Inter';
            src: url('{{ public_path('fonts/inter/Inter.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        @font-face {
            font-family: 'Inter';
            src: url('{{ public_path('fonts/inter/Inter-Bold.ttf') }}') format('truetype');
            font-weight: bold;
            font-style: normal;
        }
        /* DomPDF: @page margin-bottom leaves space for the fixed footer */
        @page {
            margin: 0;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        html, body {
            background-color: #1c1c1c;
            color: #ffffff;
            font-family: 'Inter', sans-serif;
            font-size: 11pt;
            width: 100%;
        }

        #footer-payment {
            position: fixed;
            bottom: 130pt;
            left: 32pt;
            right: 32pt;
        }



        /* ─── WATERMARK ─── */
        #watermark {
            position: fixed;
            bottom: -110pt;
            left: -110pt;
            width: 600pt;
            opacity: 0.04;
            z-index: -1;
        }

        /* ─── BRAND BAR (FIXED BOTTOM) ─── */
        #brand-bar {
            position: fixed;
            bottom: 16pt;
            left: 0;
            right: 0;
            text-align: center;
        }

        /* ─── MAIN CONTENT ─── */
        .content {
            padding: 28pt 32pt 260pt 32pt;
        }

        /* ─── TITLE ─── */
        .title {
            margin-bottom: 6pt;
        }
        .title img {
            width: 60%;
            display: block;
        }

        /* ─── META ROW ─── */
        .meta {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 32pt;
        }
        .meta td { padding: 0; vertical-align: top; }
        .inv-num {
            font-family: 'Michroma', sans-serif;
            font-size: 12pt;
            letter-spacing: 0.5pt;
        }
        .cust-name {
            font-family: 'Michroma', sans-serif;
            font-size: 14pt;
            letter-spacing: 1.5pt;
            text-transform: uppercase;
            text-align: right;
        }
        .vehicle-info {
            font-family: 'Michroma', sans-serif;
            font-size: 12pt;
            letter-spacing: 1.5pt;
            text-transform: uppercase;
            text-align: right;
            margin-top: 6pt;
        }

        /* ─── ITEMS TABLE ─── */
        .items {
            width: 100%;
            border-collapse: collapse;
        }

        .items thead th {
            font-family: 'Michroma', sans-serif;
            font-size: 11pt;
            font-weight: normal;
            letter-spacing: 2pt;
            text-transform: uppercase;
            color: #ffffff;
            padding-bottom: 6pt;
            border-bottom: 0.5pt solid #ffffff;
            text-align: left;
        }
        .items thead th.r { text-align: right; }
        .items thead th.c { text-align: center; }

        .items tbody td {
            padding: 8pt 0;
            font-size: 11.5pt;
            font-weight: 400;
            color: #eeeeee;
            border-bottom: 0.5pt solid #ffffff;
        }
        .items tbody td.col-item {
            text-transform: capitalize;
        }
        .items tbody td.r { 
            font-family: 'Inter', sans-serif;
            font-size: 11pt;
            text-align: right; 
        }
        .items tbody td.c {
            font-family: 'Inter', sans-serif;
            font-size: 11pt;
            text-align: center;
        }
        .items tbody td.nr { border-bottom: none; }

        /* summary label/value */
        .s-lbl {
            font-family: 'Michroma', sans-serif;
            font-size: 11pt;
            letter-spacing: 2pt;
            text-transform: uppercase;
            color: #888888;
            text-align: right;
            padding-right: 14pt;
            border-bottom: none !important;
        }
        .s-val {
            font-family: 'Inter', sans-serif;
            font-size: 11pt;
            color: #cccccc;
            text-align: right;
            border-bottom: none !important;
        }

        /* grand total */
        .g-lbl {
            font-family: 'Michroma', sans-serif;
            font-size: 11pt;
            letter-spacing: 2pt;
            text-transform: uppercase;
            color: #ffffff;
            text-align: right;
            padding-right: 14pt;
            border-top: 0.5pt solid #ffffff;
            border-bottom: none !important;
            padding-top: 8pt;
            white-space: nowrap;
        }
        .g-val {
            font-family: 'Inter', sans-serif;
            font-size: 11pt;
            font-weight: bold;
            color: #ffffff;
            text-align: right;
            border-top: 0.5pt solid #ffffff;
            border-bottom: none !important;
            padding-top: 8pt;
        }

        .col-item  { width: 46%; }
        .col-qty   { width: 8%;  }
        .col-price { width: 23%; }
        .col-total { width: 23%; }

        /* ─── FOOTER PAYMENT ─── */
        .pay-text {
            font-family: 'Inter', sans-serif;
            font-size: 11pt;
            color: #cccccc;
            line-height: 1.4;
        }
        .pay-text strong { color: #ffffff; font-weight: bold; }
        .pay-thanks {
            font-family: 'Inter', sans-serif;
            margin-top: 16pt;
            font-size: 11pt;
            font-weight: bold;
            color: #ffffff;
        }

    </style>
</head>
<body>

{{-- Watermark --}}
<div id="watermark">
    <img src="{{ public_path('images/logo-carbonized-clean.png') }}" style="width: 100%; height: auto;" alt="Watermark">
</div>

{{-- Brand bar (fixed bottom) --}}
<div id="brand-bar">
    <img src="{{ public_path('images/logo-bottom.png') }}" style="width: 65%; height: auto; display: inline-block;" alt="Raveil Logo">
</div>

{{-- Footer payment (fixed above brand bar) --}}
<div id="footer-payment">
    <table width="100%" style="border-collapse:collapse;">
        <tr>
            <td style="vertical-align:top; width:65%;">
                <div class="pay-text">
                    Please make payment to the following account:<br>
                    <strong>Bank Name:</strong> Bank Central Asia (BCA)<br>
                    <strong>Account Name:</strong> William Neilson Likamto<br>
                    <strong>Account Number:</strong> 6042123672
                </div>
                <div class="pay-thanks">Thank you for your purchase!</div>
            </td>
            <td style="vertical-align:top; text-align:right; width:35%;">
                <div style="display: inline-block; text-align: center; width: 200px;">
                    <img src="{{ public_path('images/qr-linktree.png') }}" style="width: 100%; height: auto; display: block; margin: 0 auto;" alt="QR">
                </div>
            </td>
        </tr>
    </table>
</div>


{{-- removed --}}

{{-- Main content --}}
<div class="content">

    <div class="title">
        <img src="{{ public_path('images/invoice-title.png') }}" alt="INVOICE">
    </div>

    <table class="meta">
        <tr>
            <td style="width:50%;">
                <div class="inv-num">#{{ $invoice->invoice_number }}</div>
            </td>
            <td style="width:50%;">
                <div class="cust-name">{{ strtoupper($invoice->customer?->name ?? 'N/A') }}</div>
                <div class="vehicle-info">
                    {{ strtoupper($invoice->salesOrder?->vehicle?->license_plate ?? '') }}
                </div>
            </td>
        </tr>
    </table>

    @php $allItems = $invoice->items; @endphp

    <table class="items">
        <thead>
            <tr>
                <th class="col-item">Item</th>
                <th class="col-qty c">QTY</th>
                <th class="col-price r">Unit Price</th>
                <th class="col-total r">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($allItems as $item)
            <tr>
                <td class="col-item">{{ $item->description }}</td>
                <td class="col-qty c">{{ $item->quantity }}</td>
                <td class="col-price r">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                <td class="col-total r">Rp {{ number_format($item->subtotal ?? $item->unit_price * $item->quantity, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="color:#555; padding:10pt 0; border-bottom:none;">No items.</td>
            </tr>
            @endforelse



            {{-- SUBTOTAL --}}
            <tr>
                <td colspan="2" class="nr"></td>
                <td class="s-lbl">Subtotal</td>
                <td class="s-val">Rp {{ number_format($invoice->subtotal ?? 0, 0, ',', '.') }}</td>
            </tr>

            {{-- SHIPPING --}}
            <tr>
                <td colspan="2" class="nr"></td>
                <td class="s-lbl">Shipping</td>
                <td class="s-val">-</td>
            </tr>

            {{-- TAX --}}
            <tr>
                <td colspan="2" class="nr"></td>
                <td class="s-lbl">Tax</td>
                <td class="s-val">Rp {{ number_format($invoice->tax ?? 0, 0, ',', '.') }}</td>
            </tr>

            {{-- GRAND TOTAL --}}
            <tr>
                <td colspan="2" style="border:none; padding:0;"></td>
                <td class="g-lbl">Grand Total</td>
                <td class="g-val">Rp {{ number_format($invoice->total ?? 0, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

</div>{{-- .content --}}

</body>
</html>
