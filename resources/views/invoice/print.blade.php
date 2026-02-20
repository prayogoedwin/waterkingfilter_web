<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 13px;
            color: #222;
            background: #fff;
            padding: 40px;
        }

        /* ── HEADER ── */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 32px;
            padding-bottom: 20px;
            border-bottom: 2px solid #222;
        }

        .company-name {
            font-size: 26px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .company-sub {
            font-size: 12px;
            color: #666;
            margin-top: 4px;
        }

        .invoice-meta {
            text-align: right;
        }

        .invoice-meta h2 {
            font-size: 22px;
            font-weight: bold;
            color: #333;
        }

        .invoice-meta p {
            font-size: 12px;
            color: #555;
            margin-top: 4px;
        }

        .badge-paid {
            display: inline-block;
            margin-top: 8px;
            padding: 3px 12px;
            background: #16a34a;
            color: #fff;
            border-radius: 99px;
            font-size: 11px;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        /* ── MEMBER INFO ── */
        .member-box {
            background: #f8f8f8;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            padding: 14px 18px;
            margin-bottom: 28px;
        }

        .member-box .label {
            font-size: 11px;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .member-box .value {
            font-size: 14px;
            font-weight: bold;
        }

        /* ── TABLE ── */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }

        thead tr {
            background: #222;
            color: #fff;
        }

        thead th {
            padding: 10px 12px;
            text-align: left;
            font-size: 12px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        thead th:last-child,
        tbody td:last-child,
        tfoot td:last-child {
            text-align: right;
        }

        thead th:nth-child(2),
        tbody td:nth-child(2),
        thead th:nth-child(3),
        tbody td:nth-child(3) {
            text-align: center;
        }

        tbody tr:nth-child(even) {
            background: #f9f9f9;
        }

        tbody td {
            padding: 9px 12px;
            border-bottom: 1px solid #eee;
        }

        /* ── SUMMARY ── */
        .summary {
            margin-left: auto;
            width: 280px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
            border-bottom: 1px solid #eee;
            font-size: 13px;
        }

        .summary-row.total {
            font-size: 15px;
            font-weight: bold;
            border-bottom: none;
            padding-top: 10px;
        }

        .summary-row .discount-label {
            color: #dc2626;
        }

        .summary-row .discount-value {
            color: #dc2626;
        }

        /* ── FOOTER ── */
        .footer {
            margin-top: 48px;
            padding-top: 16px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 11px;
            color: #aaa;
        }

        /* ── PRINT ── */
        @media print {
            body {
                padding: 20px;
            }

            .no-print {
                display: none;
            }

            @page {
                size: A4;
                margin: 10mm;
            }
        }
    </style>
</head>

<body>

    {{-- Tombol Print (tidak ikut cetak) --}}
    <div class="no-print" style="margin-bottom:20px; display:flex; gap:8px;">
        <button onclick="window.print()"
            style="padding:8px 20px; background:#222; color:#fff; border:none; border-radius:5px; cursor:pointer; font-size:13px;">
            🖨️ Cetak Invoice
        </button>
        <button onclick="window.close()"
            style="padding:8px 20px; background:#eee; color:#333; border:none; border-radius:5px; cursor:pointer; font-size:13px;">
            ✕ Tutup
        </button>
    </div>

    {{-- Header --}}
    <div class="header">
        <div>
            <div class="company-name">{{ str_replace('-', ' ', config('app.name')) }}</div>
            <div class="company-sub">Jl. Letda Sujono 142, (Simpang Jl. Pancing), Medan</div>
            <div class="company-sub">Telp: 0813 6203 3888</div>
        </div>
        <div class="invoice-meta">
            <h2>INVOICE</h2>
            <p>{{ $invoice->invoice_number }}</p>
            <p>{{ $invoice->created_at->format('d F Y, H:i') }}</p>
            <span class="badge-paid">{{ strtoupper($invoice->status) }}</span>
        </div>
    </div>

    {{-- Member --}}
    @if ($invoice->member)
        <div class="member-box">
            <div class="label">Kepada</div>
            <div class="value">{{ $invoice->member->name }}</div>
            @if ($invoice->voucher)
                <div style="margin-top:6px; font-size:12px; color:#555;">
                    Voucher: <strong>{{ $invoice->voucher->name }}</strong>
                </div>
            @endif
        </div>
    @endif

    {{-- Tabel Produk --}}
    <table>
        <thead>
            <tr>
                <th style="width:40px">#</th>
                <th>Produk</th>
                <th style="width:60px">Qty</th>
                <th style="width:110px">Harga</th>
                <th style="width:120px">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoice->items as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $item->product->nama ?? '-' }}</td>
                    <td style="text-align:center">{{ $item->qty }}</td>
                    <td style="text-align:right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Summary --}}
    <div class="summary">
        <div class="summary-row">
            <span>Subtotal</span>
            <span>Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</span>
        </div>
        @if ($invoice->discount > 0)
            <div class="summary-row">
                <span class="discount-label">Diskon</span>
                <span class="discount-value">- Rp {{ number_format($invoice->discount, 0, ',', '.') }}</span>
            </div>
        @endif
        <div class="summary-row total">
            <span>TOTAL</span>
            <span>Rp {{ number_format($invoice->total, 0, ',', '.') }}</span>
        </div>
    </div>

    {{-- Footer --}}
    <div class="footer">
        Terima kasih telah berbelanja! &nbsp;·&nbsp; Invoice ini sah tanpa tanda tangan
    </div>

</body>

</html>
