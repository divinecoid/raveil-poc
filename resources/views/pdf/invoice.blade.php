<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        @font-face {
            font-family: 'Barlow';
            src: url('{{ public_path('fonts/barlow/BarlowCondensed-Regular.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        @font-face {
            font-family: 'Barlow';
            src: url('{{ public_path('fonts/barlow/BarlowCondensed-Bold.ttf') }}') format('truetype');
            font-weight: bold;
            font-style: normal;
        }
        @font-face {
            font-family: 'Barlow-Black';
            src: url('{{ public_path('fonts/barlow/BarlowCondensed-Black.ttf') }}') format('truetype');
            font-weight: normal;
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
            font-family: 'Barlow', 'DejaVu Sans', sans-serif;
            font-size: 11pt;
            width: 100%;
        }

        /* ─── FIXED FOOTER (DomPDF renders fixed elements on every page) ─── */
        /* We only want one page so this is fine */
        #footer-payment {
            position: fixed;
            bottom: 32pt;
            left: 52pt;
            right: 52pt;
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

        /* ─── MAIN CONTENT ─── */
        /* bottom padding = footer area height (payment ~70pt + brand 46pt + gap) */
        .content {
            padding: 44pt 52pt 140pt 52pt;
        }

        /* ─── TITLE ─── */
        .title {
            font-family: 'Barlow', 'DejaVu Sans', sans-serif;
            font-size: 54pt;
            font-weight: normal;
            letter-spacing: 4pt;
            text-transform: uppercase;
            line-height: 1;
            margin-bottom: 6pt;
        }

        /* ─── META ROW ─── */
        .meta {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 32pt;
        }
        .meta td { padding: 0; vertical-align: top; }
        .inv-num {
            font-size: 11.5pt;
            font-weight: 700;
            letter-spacing: 0.5pt;
        }
        .cust-name {
            font-family: 'Barlow', 'DejaVu Sans', sans-serif;
            font-size: 13.5pt;
            font-weight: bold;
            letter-spacing: 1.5pt;
            text-transform: uppercase;
            text-align: right;
        }
        .inv-date {
            font-size: 10pt;
            color: #bbbbbb;
            text-align: center;
            margin-top: 2pt;
        }

        /* ─── ITEMS TABLE ─── */
        .items {
            width: 100%;
            border-collapse: collapse;
        }

        .items thead th {
            font-size: 9.5pt;
            font-weight: 700;
            letter-spacing: 2pt;
            text-transform: uppercase;
            color: #888888;
            padding-bottom: 6pt;
            border-bottom: 0.5pt solid #444444;
            text-align: left;
        }
        .items thead th.r { text-align: right; }

        .items tbody td {
            padding: 8pt 0;
            font-size: 11.5pt;
            font-weight: 400;
            color: #eeeeee;
            border-bottom: 0.5pt solid #2c2c2c;
        }
        .items tbody td.r { text-align: right; }
        .items tbody td.nr { border-bottom: none; }

        /* summary label/value */
        .s-lbl {
            font-size: 9.5pt;
            font-weight: 700;
            letter-spacing: 2pt;
            text-transform: uppercase;
            color: #888888;
            text-align: right;
            padding-right: 14pt;
            border-bottom: none !important;
        }
        .s-val {
            font-size: 11.5pt;
            color: #cccccc;
            text-align: right;
            border-bottom: none !important;
        }

        /* grand total */
        .g-lbl {
            font-family: 'Barlow', 'DejaVu Sans', sans-serif;
            font-size: 11pt;
            font-weight: bold;
            letter-spacing: 2pt;
            text-transform: uppercase;
            color: #ffffff;
            text-align: right;
            padding-right: 14pt;
            border-top: 0.5pt solid #555555;
            border-bottom: none !important;
            padding-top: 8pt;
        }
        .g-val {
            font-family: 'Barlow', 'DejaVu Sans', sans-serif;
            font-size: 14.5pt;
            font-weight: bold;
            color: #ffffff;
            text-align: right;
            border-top: 0.5pt solid #555555;
            border-bottom: none !important;
            padding-top: 8pt;
        }

        .col-item  { width: 46%; }
        .col-qty   { width: 8%;  }
        .col-price { width: 23%; }
        .col-total { width: 23%; }

        /* ─── FOOTER PAYMENT ─── */
        .pay-text {
            font-size: 10pt;
            color: #cccccc;
            line-height: 1.8;
        }
        .pay-text strong { color: #ffffff; font-weight: 700; }
        .pay-thanks {
            margin-top: 8pt;
            font-size: 10pt;
            font-weight: 700;
            color: #ffffff;
        }
        .qr-label {
            font-size: 8.5pt;
            font-weight: 700;
            letter-spacing: 2pt;
            text-transform: uppercase;
            color: #aaaaaa;
            margin-top: 3pt;
            text-align: center;
        }
    </style>
</head>
<body>

{{-- Watermark --}}
<div id="watermark">
    <img src="{{ public_path('images/logo-carbonized-clean.png') }}" style="width: 100%; height: auto;" alt="Watermark">
</div>

{{-- Footer payment (fixed, above brand bar) --}}
<div id="footer-payment">
    <table width="100%" style="border-collapse:collapse;">
        <tr>
            <td style="vertical-align:bottom; width:65%;">
                <div class="pay-text">
                    Please make payment to the following account:<br>
                    <strong>Bank Name:</strong> Bank Central Asia (BCA)<br>
                    <strong>Account Name:</strong> William Neilson Likamto<br>
                    <strong>Account Number:</strong> 6042123672
                </div>
                <div class="pay-thanks">Thank you for your purchase!</div>
            </td>
            <td style="vertical-align:bottom; text-align:right; width:35%;">
                <img src="{{ public_path('images/qr-linktree.png') }}" width="80" height="80" alt="QR" style="display:inline-block;">
                <div class="qr-label">LINKTREE</div>
            </td>
        </tr>
    </table>
</div>

{{-- Brand bar --}}
{{-- removed --}}

{{-- Main content --}}
<div class="content">

    <div class="title">INVOICE</div>

    <table class="meta">
        <tr>
            <td style="width:50%;">
                <div class="inv-num">#{{ $invoice->invoice_number }}</div>
            </td>
            <td style="width:50%;">
                <div class="cust-name">{{ strtoupper($invoice->customer?->name ?? 'N/A') }}</div>
                <div class="inv-date">
                    {{ $invoice->issue_date ? \Carbon\Carbon::parse($invoice->issue_date)->format('m/Y') : '' }}
                </div>
            </td>
        </tr>
    </table>

    @php $allItems = $invoice->items; @endphp

    <table class="items">
        <thead>
            <tr>
                <th class="col-item">Item</th>
                <th class="col-qty r">QTY</th>
                <th class="col-price r">Unit Price</th>
                <th class="col-total r">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($allItems as $item)
            <tr>
                <td class="col-item">{{ $item->description }}</td>
                <td class="col-qty r">{{ $item->quantity }}</td>
                <td class="col-price r">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                <td class="col-total r">Rp {{ number_format($item->subtotal ?? $item->unit_price * $item->quantity, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="color:#555; padding:10pt 0; border-bottom:none;">No items.</td>
            </tr>
            @endforelse

            {{-- spacer --}}
            <tr><td colspan="4" style="height:16pt; border-bottom:none;"></td></tr>

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
