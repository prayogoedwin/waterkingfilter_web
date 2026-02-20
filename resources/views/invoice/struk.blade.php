<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Struk {{ $invoice->invoice_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            width: 80mm;
            padding: 8mm 6mm;
            color: #000;
            background: #fff;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        .toko-name {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            letter-spacing: 1px;
        }

        .toko-info {
            font-size: 10px;
            text-align: center;
            color: #444;
            margin-top: 2px;
        }

        .divider {
            border: none;
            border-top: 1px dashed #000;
            margin: 8px 0;
        }

        .row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
        }

        .item-name {
            margin-bottom: 1px;
        }

        .item-detail {
            display: flex;
            justify-content: space-between;
            padding-left: 4px;
            color: #333;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            font-weight: bold;
            margin-top: 4px;
        }

        .discount {
            color: #c00;
        }

        .footer-text {
            text-align: center;
            font-size: 10px;
            color: #555;
            margin-top: 10px;
        }

        @media print {
            body {
                padding: 4mm;
            }

            .no-print {
                display: none;
            }

            @page {
                size: 80mm auto;
                margin: 0;
            }
        }
    </style>
</head>

<body>

    {{-- Tombol Print --}}
    <div class="no-print" style="margin-bottom:12px; display:flex; gap:6px;">
        <button onclick="window.print()"
            style="padding:6px 14px; background:#222; color:#fff; border:none; border-radius:4px; cursor:pointer; font-size:12px;">
            🖨️ Cetak Struk
        </button>
        <button onclick="window.close()"
            style="padding:6px 14px; background:#eee; color:#333; border:none; border-radius:4px; cursor:pointer; font-size:12px;">
            ✕ Tutup
        </button>
    </div>

    {{-- Header Toko --}}
    <div class="toko-name">{{ str_replace('-', ' ', config('app.name')) }}</div>
    <div class="toko-info">Jl. Letda Sujono 142, (Simpang Jl. Pancing), Medan</div>
    <div class="toko-info">Telp: 0813 6203 3888</div>

    <hr class="divider">

    {{-- Info Invoice --}}
    <div class="row">
        <span>No</span>
        <span>{{ $invoice->invoice_number }}</span>
    </div>
    <div class="row">
        <span>Tgl</span>
        <span>{{ $invoice->created_at->format('d/m/Y H:i') }}</span>
    </div>
    @if ($invoice->member)
        <div class="row">
            <span>Member</span>
            <span>{{ $invoice->member->name }}</span>
        </div>
    @endif

    <hr class="divider">

    {{-- Items --}}
    @foreach ($invoice->items as $item)
        <div class="item-name bold">{{ $item->product->nama ?? '-' }}</div>
        <div class="item-detail">
            <span>{{ $item->qty }} x Rp {{ number_format($item->price, 0, ',', '.') }}</span>
            <span>Rp {{ number_format($item->total, 0, ',', '.') }}</span>
        </div>
    @endforeach

    <hr class="divider">

    {{-- Summary --}}
    <div class="summary-row">
        <span>Subtotal</span>
        <span>Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</span>
    </div>
    @if ($invoice->discount > 0)
        <div class="summary-row discount">
            <span>Diskon @if ($invoice->voucher)
                    ({{ $invoice->voucher->name }})
                @endif
            </span>
            <span>- Rp {{ number_format($invoice->discount, 0, ',', '.') }}</span>
        </div>
    @endif
    <div class="total-row">
        <span>TOTAL</span>
        <span>Rp {{ number_format($invoice->total, 0, ',', '.') }}</span>
    </div>

    <hr class="divider">

    {{-- Footer --}}
    <div class="footer-text">Terima kasih telah berbelanja!</div>
    <div class="footer-text">Barang yang sudah dibeli</div>
    <div class="footer-text">tidak dapat dikembalikan</div>

</body>

</html>
