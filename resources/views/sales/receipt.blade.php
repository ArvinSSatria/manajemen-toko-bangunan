<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk {{ $sale->invoice_number }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Courier+Prime:ital,wght@0,400;0,700;1,400;1,700&display=swap');
        
        body {
            font-family: 'Courier Prime', monospace;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
        }
        
        .receipt {
            background: white;
            width: 80mm; /* Standard thermal printer width */
            padding: 15px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        @media print {
            body {
                background-color: white;
                padding: 0;
            }
            .receipt {
                width: 100%;
                box-shadow: none;
                padding: 0;
                margin: 0;
            }
            .no-print {
                display: none;
            }
        }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .font-bold { font-weight: bold; }
        .text-sm { font-size: 12px; }
        .text-xs { font-size: 10px; }
        
        hr {
            border: none;
            border-top: 1px dashed #000;
            margin: 10px 0;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        td {
            vertical-align: top;
            padding: 2px 0;
        }
        
        .product-name {
            display: block;
            margin-bottom: 2px;
        }
    </style>
</head>
<body>

    <div class="no-print" style="position: fixed; top: 20px; right: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #4f46e5; color: white; border: none; border-radius: 5px; cursor: pointer; font-family: sans-serif; font-weight: bold;">
            🖨️ Cetak Struk
        </button>
    </div>

    <div class="receipt">
        <div class="text-center">
            <h2 style="margin: 0 0 5px 0;">{{ $sale->store->name }}</h2>
            <div class="text-sm">{{ $sale->store->address }}</div>
            <div class="text-sm">Telp: {{ $sale->store->phone ?? '-' }}</div>
        </div>
        
        <hr>
        
        <table class="text-sm">
            <tr>
                <td>Tgl</td>
                <td>: {{ $sale->created_at->format('d/m/y H:i') }}</td>
            </tr>
            <tr>
                <td>No</td>
                <td>: {{ $sale->invoice_number }}</td>
            </tr>
            <tr>
                <td>Ksr</td>
                <td>: {{ substr($sale->creator->name ?? 'System', 0, 15) }}</td>
            </tr>
            @if($sale->customer)
            <tr>
                <td>Plg</td>
                <td>: {{ substr($sale->customer->name, 0, 15) }}</td>
            </tr>
            @endif
        </table>
        
        <hr>
        
        <table class="text-sm">
            @foreach($sale->items as $item)
            <tr>
                <td colspan="3">
                    <span class="product-name font-bold">
                        {{ $item->product->name }}
                        @if($item->product->is_consignment && $item->product->bo)
                            <span style="font-size: 10px; font-weight: normal;">(Titipan)</span>
                        @endif
                    </span>
                </td>
            </tr>
            <tr>
                <td style="width: 25%;">{{ $item->qty }} {{ $item->product->unit }}</td>
                <td style="width: 35%;">x {{ number_format($item->price, 0, ',', '.') }}</td>
                <td class="text-right" style="width: 40%;">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </table>
        
        <hr>
        
        <table class="text-sm font-bold">
            <tr>
                <td>Total</td>
                <td class="text-right">{{ number_format($sale->total, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Bayar</td>
                <td class="text-right">{{ number_format($sale->paid_amount, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="font-weight: normal; font-size: 11px;">Metode: {{ $sale->payment_method }}</td>
                <td></td>
            </tr>
            @if($sale->remaining_balance > 0)
            <tr>
                <td>Sisa Tagihan</td>
                <td class="text-right">{{ number_format($sale->remaining_balance, 0, ',', '.') }}</td>
            </tr>
            @else
            <tr>
                <td>Kembali</td>
                <td class="text-right">{{ number_format($sale->paid_amount - $sale->total, 0, ',', '.') }}</td>
            </tr>
            @endif
        </table>
        
        <hr>
        
        <div class="text-center text-xs" style="margin-top: 15px;">
            <p style="margin: 0 0 5px 0;">Terima Kasih Atas Kunjungan Anda</p>
            <p style="margin: 0;">Barang yang sudah dibeli tidak dapat ditukar/dikembalikan.</p>
        </div>
    </div>

    <script>
        // Auto print on load
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        }
    </script>
</body>
</html>
