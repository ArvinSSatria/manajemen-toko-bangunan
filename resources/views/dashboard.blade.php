<x-app-layout>
    <x-slot name="header">Dashboard Analytics</x-slot>
    <x-slot name="title">Dashboard</x-slot>

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h1 class="text-page-title text-gray-900 dark:text-white">Overview</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Ringkasan performa bisnis {{ session('active_store_name') ? session('active_store_name') : 'semua cabang' }}.
            </p>
        </div>
        <a href="{{ route('pos.index') }}" class="btn-primary">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Buka Kasir
        </a>
    </div>

    <!-- Key Metrics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 mb-8">
        
        <!-- Penjualan Hari Ini -->
        <div class="card p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-meta text-gray-500 dark:text-gray-400 font-medium">Penjualan Hari Ini</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-2">Rp {{ number_format($stats['today_sales'], 0, ',', '.') }}</h3>
                </div>
                <div class="w-10 h-10 rounded-full bg-blue-50 dark:bg-blue-500/10 flex items-center justify-center text-blue-600 dark:text-blue-400 flex-shrink-0">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                    </svg>
                </div>
            </div>
            <p class="text-meta text-gray-400 dark:text-gray-500 mt-2">{{ $stats['today_transactions'] }} transaksi</p>
        </div>

        <!-- Penjualan Bulan Ini -->
        <div class="card p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-meta text-gray-500 dark:text-gray-400 font-medium">Penjualan Bulan Ini</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-2">Rp {{ number_format($stats['monthly_sales'], 0, ',', '.') }}</h3>
                </div>
                <div class="w-10 h-10 rounded-full bg-primary-50 dark:bg-primary-500/10 flex items-center justify-center text-primary-600 dark:text-primary-400 flex-shrink-0">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                    </svg>
                </div>
            </div>
            <p class="text-meta text-gray-400 dark:text-gray-500 mt-2">Total akumulasi bulan ini</p>
        </div>

        <!-- Estimasi Laba Kotor -->
        <div class="card p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-meta text-gray-500 dark:text-gray-400 font-medium">Estimasi Laba Kotor</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-2">Rp {{ number_format($stats['estimated_gross_profit'], 0, ',', '.') }}</h3>
                </div>
                <div class="w-10 h-10 rounded-full bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center text-emerald-600 dark:text-emerald-400 flex-shrink-0">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941" />
                    </svg>
                </div>
            </div>
            <p class="text-meta text-gray-400 dark:text-gray-500 mt-2">Penjualan - Modal (bulan ini)</p>
        </div>

        <!-- Total Aset Stok -->
        <div class="card p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-meta text-gray-500 dark:text-gray-400 font-medium">Nilai Total Aset Stok</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-2">Rp {{ number_format($stats['total_inventory_value'], 0, ',', '.') }}</h3>
                </div>
                <div class="w-10 h-10 rounded-full bg-purple-50 dark:bg-purple-500/10 flex items-center justify-center text-purple-600 dark:text-purple-400 flex-shrink-0">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                    </svg>
                </div>
            </div>
            <p class="text-meta text-gray-400 dark:text-gray-500 mt-2">Modal barang di gudang</p>
        </div>

        <!-- Pengeluaran Bulan Ini -->
        <div class="card p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-meta text-gray-500 dark:text-gray-400 font-medium">Pengeluaran Bulan Ini</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-2">Rp {{ number_format($stats['monthly_expense'], 0, ',', '.') }}</h3>
                </div>
                <div class="w-10 h-10 rounded-full bg-pink-50 dark:bg-pink-500/10 flex items-center justify-center text-pink-600 dark:text-pink-400 flex-shrink-0">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
                    </svg>
                </div>
            </div>
            <p class="text-meta text-gray-400 dark:text-gray-500 mt-2">Beban operasional & lain</p>
        </div>

        <!-- Total Uang Masuk (Riil) -->
        <div class="card p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-meta text-gray-500 dark:text-gray-400 font-medium">Total Uang Masuk (Riil)</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-2">Rp {{ number_format($stats['monthly_income'], 0, ',', '.') }}</h3>
                </div>
                <div class="w-10 h-10 rounded-full bg-teal-50 dark:bg-teal-500/10 flex items-center justify-center text-teal-600 dark:text-teal-400 flex-shrink-0">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
                    </svg>
                </div>
            </div>
            <p class="text-meta text-gray-400 dark:text-gray-500 mt-2">Penjualan + Pelunasan (set. pot. admin)</p>
        </div>

        <!-- Piutang Pelanggan -->
        <div class="card p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-meta text-gray-500 dark:text-gray-400 font-medium">Piutang Pelanggan</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-2">Rp {{ number_format($stats['total_receivable'], 0, ',', '.') }}</h3>
                </div>
                <div class="w-10 h-10 rounded-full bg-orange-50 dark:bg-orange-500/10 flex items-center justify-center text-orange-600 dark:text-orange-400 flex-shrink-0">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                    </svg>
                </div>
            </div>
            <p class="text-meta text-gray-400 dark:text-gray-500 mt-2">{{ $stats['active_receivables'] }} tagihan aktif</p>
        </div>

        <!-- Hutang BO (Supplier) -->
        <div class="card p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-meta text-gray-500 dark:text-gray-400 font-medium">Hutang BO / Supplier</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-2">Rp {{ number_format($stats['total_bo_debt'], 0, ',', '.') }}</h3>
                </div>
                <div class="w-10 h-10 rounded-full bg-red-50 dark:bg-red-500/10 flex items-center justify-center text-red-600 dark:text-red-400 flex-shrink-0">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z" />
                    </svg>
                </div>
            </div>
            <p class="text-meta text-gray-400 dark:text-gray-500 mt-2">Belum lunas</p>
        </div>

    </div>
    <!-- Charts & Tables Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        
        <!-- Sales Chart -->
        <div class="lg:col-span-2 card p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-card-title text-gray-900 dark:text-white">Tren Penjualan</h3>
                    <p class="text-meta text-gray-500 dark:text-gray-400 mt-1">Grafik penjualan 30 hari terakhir</p>
                </div>
            </div>
            <div class="relative h-72 w-full">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <!-- Best Selling Products -->
        <div class="card p-6 flex flex-col">
            <div class="mb-6">
                <h3 class="text-card-title text-gray-900 dark:text-white">Produk Terlaris</h3>
                <p class="text-meta text-gray-500 dark:text-gray-400 mt-1">Bulan ini berdasarkan kuantitas</p>
            </div>
            
            <div class="space-y-3 flex-1">
                @forelse($bestSelling as $item)
                    <div class="flex items-center gap-3 py-1">
                        <div class="flex-shrink-0 w-7 h-7 rounded-md bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-xs font-medium text-gray-500 dark:text-gray-400">
                            {{ $loop->iteration }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                {{ $item->product->name }}
                            </p>
                            <p class="text-meta text-gray-400 dark:text-gray-500 truncate">
                                {{ $item->total_qty }} {{ $item->product->unit }} terjual
                            </p>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                Rp {{ number_format($item->total_revenue, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="h-full flex flex-col items-center justify-center text-gray-400 space-y-2">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        <span class="text-sm">Belum ada data penjualan.</span>
                    </div>
                @endforelse
            </div>
    </div>
    </div>

    <!-- Bottom Row: Transactions & Low Stock -->
    <div class="mt-5 grid grid-cols-1 lg:grid-cols-3 gap-5">
        
        <!-- Latest Transactions -->
        <div class="lg:col-span-2 card p-0 overflow-hidden flex flex-col">
            <div class="p-6 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                <div>
                    <h3 class="text-card-title text-gray-900 dark:text-white">Transaksi Terakhir</h3>
                    <p class="text-meta text-gray-500 dark:text-gray-400 mt-1">5 riwayat transaksi kasir terbaru</p>
                </div>
                <a href="{{ route('sales.index') }}" class="text-sm font-medium text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300">
                    Lihat Semua &rarr;
                </a>
            </div>
            
            <div class="overflow-x-auto flex-1">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-500 uppercase bg-gray-50 dark:bg-gray-800/50 dark:text-gray-400 border-b border-gray-200 dark:border-gray-700">
                        <tr>
                            <th class="px-6 py-3 font-medium">No. Invoice</th>
                            <th class="px-6 py-3 font-medium">Waktu</th>
                            <th class="px-6 py-3 font-medium">Pelanggan</th>
                            <th class="px-6 py-3 font-medium text-right">Total</th>
                            <th class="px-6 py-3 font-medium text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse($latestTransactions as $trx)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                    {{ $trx->invoice_number }}
                                </td>
                                <td class="px-6 py-4 text-gray-500 dark:text-gray-400">
                                    {{ $trx->created_at->diffForHumans() }}
                                </td>
                                <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                                    {{ $trx->customer ? $trx->customer->name : 'Umum' }}
                                </td>
                                <td class="px-6 py-4 text-right font-semibold text-gray-900 dark:text-white">
                                    Rp {{ number_format($trx->total, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="badge-{{ $trx->status->color() }}">{{ $trx->status->label() }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                    Belum ada transaksi penjualan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Low Stock Alerts -->
        <div class="card p-0 overflow-hidden flex flex-col border border-red-100 dark:border-red-900/30">
            <div class="p-6 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between bg-red-50/30 dark:bg-red-900/10">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-red-100 dark:bg-red-500/20 flex items-center justify-center text-red-600 dark:text-red-400">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-card-title text-gray-900 dark:text-white">Stok Menipis</h3>
                        <p class="text-meta text-gray-500 dark:text-gray-400 mt-1">Perlu segera restock</p>
                    </div>
                </div>
            </div>
            
            <div class="overflow-y-auto flex-1 p-6">
                <div class="space-y-4">
                    @forelse($lowStockProducts as $inv)
                        <div class="flex items-center justify-between">
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                    {{ $inv->product->name }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 truncate">
                                    {{ $inv->store->name }}
                                </p>
                            </div>
                            <div class="ml-4 flex-shrink-0 flex items-center gap-2">
                                <span class="text-xs text-gray-500 dark:text-gray-400">Sisa:</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 dark:bg-red-500/20 dark:text-red-400">
                                    {{ $inv->stock }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="h-full flex flex-col items-center justify-center text-gray-400 space-y-2 py-6">
                            <svg class="w-8 h-8 text-emerald-500/50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-sm text-center">Semua stok aman.</span>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('salesChart').getContext('2d');
            const isDark = document.documentElement.classList.contains('dark');
            
            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(37, 99, 235, 0.08)');
            gradient.addColorStop(1, 'rgba(37, 99, 235, 0.0)');
            
            const gridColor = 'rgba(148, 163, 184, 0.2)'; // slate-400 at 20% opacity works for both modes
            const textColor = 'rgba(148, 163, 184, 0.8)'; // slate-400 at 80% opacity

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($chartData['labels']) !!},
                    datasets: [{
                        label: 'Pendapatan (Rp)',
                        data: {!! json_encode($chartData['sales']) !!},
                        backgroundColor: '#3b82f6', // blue-500
                        borderRadius: 6,
                        borderWidth: 0,
                        barThickness: 'flex',
                        maxBarThickness: 40
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: isDark ? '#1e293b' : '#ffffff',
                            titleColor: isDark ? '#f8fafc' : '#0f172a',
                            bodyColor: isDark ? '#cbd5e1' : '#475569',
                            borderColor: isDark ? '#334155' : '#e2e8f0',
                            borderWidth: 1,
                            padding: 12,
                            boxPadding: 6,
                            usePointStyle: true,
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false,
                                drawBorder: false,
                            },
                            ticks: {
                                color: textColor,
                                font: {
                                    family: "'Inter', sans-serif",
                                    size: 11
                                },
                                maxTicksLimit: 10
                            }
                        },
                        y: {
                            border: { display: false },
                            grid: {
                                color: gridColor,
                                drawTicks: false,
                            },
                            ticks: {
                                color: textColor,
                                padding: 10,
                                font: {
                                    family: "'Inter', sans-serif",
                                    size: 11
                                },
                                callback: function(value) {
                                    if(value >= 1000000) {
                                        return (value / 1000000) + ' Jt';
                                    }
                                    if(value >= 1000) {
                                        return (value / 1000) + ' Rb';
                                    }
                                    return value;
                                }
                            },
                            beginAtZero: true
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index',
                    },
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
