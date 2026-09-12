<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan - {{ config('app.name') }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { margin: 0; font-size: 20px; color: #1e293b; }
        .header p { margin: 5px 0 0 0; color: #64748b; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { padding: 8px 10px; border: 1px solid #e2e8f0; text-align: left; }
        th { background-color: #f8fafc; font-weight: bold; color: #475569; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .total-row { background-color: #f1f5f9; font-weight: bold; }
        
        .summary-box { width: 48%; display: inline-block; vertical-align: top; margin-bottom: 20px; }
        .summary-box table { width: auto; margin: 0; }
        .summary-box th { background-color: transparent; border: none; padding: 4px 10px 4px 0; }
        .summary-box td { border: none; padding: 4px 0; }
    </style>
</head>
<body>

    <div class="header">
        <h1>LAPORAN {{ strtoupper(str_replace('_', ' ', $reportType)) }}</h1>
        <p>Periode: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</p>
        <p>Toko: {{ $store ? $store->name : 'Semua Cabang (Konsolidasi)' }}</p>
    </div>

    @if($reportType === 'sales')
        
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>No. Invoice</th>
                    <th>Toko</th>
                    <th>Pelanggan</th>
                    <th class="text-right">Total Transaksi</th>
                    <th class="text-right">Laba Kotor</th>
                </tr>
            </thead>
            <tbody>
                @php 
                    $totalSales = 0; 
                    $totalProfit = 0;
                @endphp
                @foreach($data as $sale)
                    @php
                        // Estimate profit = selling price - purchase price
                        $profit = $sale->items->sum(function($item) {
                            $margin = $item->price - $item->product->purchase_price;
                            return $margin * $item->qty;
                        });
                        
                        $totalSales += $sale->total;
                        $totalProfit += $profit;
                    @endphp
                    <tr>
                        <td>{{ $sale->date->format('d/m/Y') }}</td>
                        <td>{{ $sale->invoice_number }}</td>
                        <td>{{ $sale->store->name }}</td>
                        <td>{{ $sale->customer->name ?? 'Umum' }}</td>
                        <td class="text-right">Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($profit, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="4" class="text-right">TOTAL</td>
                    <td class="text-right">Rp {{ number_format($totalSales, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($totalProfit, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

    @elseif($reportType === 'profit_loss')

        <div class="summary-box" style="width: 100%;">
            <table>
                <tr>
                    <th style="font-size: 14px;">Total Pendapatan (Penjualan KOTOR)</th>
                    <td style="font-size: 14px; font-weight: bold; text-align: right;">Rp {{ number_format($data['gross_revenue'], 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Harga Pokok Penjualan (HPP)</th>
                    <td style="text-align: right;">(Rp {{ number_format($data['cogs'], 0, ',', '.') }})</td>
                </tr>
                <tr style="border-bottom: 2px solid #ccc;">
                    <th style="font-size: 14px;">Laba Kotor (Gross Profit)</th>
                    <td style="font-size: 14px; font-weight: bold; text-align: right;">Rp {{ number_format($data['gross_profit'], 0, ',', '.') }}</td>
                </tr>
                
                <tr><td colspan="2" style="height: 10px;"></td></tr>
                
                <tr>
                    <th colspan="2">Pengeluaran Operasional (Beban)</th>
                </tr>
                @foreach($data['expenses'] as $expense)
                <tr>
                    <td style="padding-left: 20px; font-size: 11px;">- {{ $expense->description }}</td>
                    <td style="text-align: right; font-size: 11px;">(Rp {{ number_format($expense->amount, 0, ',', '.') }})</td>
                </tr>
                @endforeach
                <tr style="border-bottom: 2px solid #ccc;">
                    <th>Total Pengeluaran</th>
                    <td style="font-weight: bold; text-align: right;">(Rp {{ number_format($data['total_expense'], 0, ',', '.') }})</td>
                </tr>
                
                <tr><td colspan="2" style="height: 10px;"></td></tr>
                
                <tr style="background-color: #e2e8f0;">
                    <th style="font-size: 16px; padding: 10px;">LABA BERSIH (NET PROFIT)</th>
                    <td style="font-size: 16px; font-weight: bold; text-align: right; padding: 10px; color: {{ $data['net_profit'] >= 0 ? '#16a34a' : '#dc2626' }}">
                        Rp {{ number_format($data['net_profit'], 0, ',', '.') }}
                    </td>
                </tr>
            </table>
        </div>

    @elseif($reportType === 'inventory')

        <table>
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th class="text-center">Total Stok</th>
                    <th class="text-right">Harga Beli Rata-rata</th>
                    <th class="text-right">Total Nilai Persediaan</th>
                </tr>
            </thead>
            <tbody>
                @php $totalValue = 0; @endphp
                @foreach($data as $product)
                    @php
                        $totalStock = $product->inventories->sum('stock');
                        // Simple valuation based on product's set purchase price
                        $value = $totalStock * $product->purchase_price;
                        $totalValue += $value;
                    @endphp
                    @if($totalStock > 0)
                    <tr>
                        <td>{{ $product->product_code }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->category->name }}</td>
                        <td class="text-center">{{ $totalStock }} {{ $product->unit }}</td>
                        <td class="text-right">Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($value, 0, ',', '.') }}</td>
                    </tr>
                    @endif
                @endforeach
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="5" class="text-right">TOTAL NILAI ASET PERSEDIAAN</td>
                    <td class="text-right">Rp {{ number_format($totalValue, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

    @endif

</body>
</html>
