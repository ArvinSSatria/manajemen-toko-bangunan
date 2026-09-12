<x-app-layout>
    <x-slot name="header">Laporan Bisnis</x-slot>
    <x-slot name="title">Laporan Analisa</x-slot>

    <div class="max-w-4xl mx-auto space-y-6">
        
        <div class="card p-6 bg-gradient-to-r from-primary-600 to-indigo-700 text-white rounded-3xl overflow-hidden relative border-none">
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
            <div class="relative z-10">
                <h2 class="text-2xl font-bold mb-2">Pusat Laporan & Analisa</h2>
                <p class="text-primary-100">Ekspor data transaksi, laba/rugi, dan mutasi barang ke dalam format PDF atau Excel.</p>
            </div>
        </div>

        <div class="card p-6">
            <form action="{{ route('reports.generate') }}" method="GET" target="_blank" class="space-y-6">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Tipe Laporan -->
                    <div>
                        <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-4">Pilih Jenis Laporan</h3>
                        
                        <div class="space-y-3">
                            <label class="flex items-center p-4 border border-slate-200 dark:border-slate-700 rounded-xl cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                <input type="radio" name="report_type" value="sales" class="w-5 h-5 text-primary-600" checked>
                                <div class="ml-3">
                                    <span class="block font-semibold text-slate-800 dark:text-white">Laporan Penjualan</span>
                                    <span class="block text-sm text-slate-500">Ringkasan transaksi penjualan per periode</span>
                                </div>
                            </label>

                            <label class="flex items-center p-4 border border-slate-200 dark:border-slate-700 rounded-xl cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                <input type="radio" name="report_type" value="profit_loss" class="w-5 h-5 text-primary-600">
                                <div class="ml-3">
                                    <span class="block font-semibold text-slate-800 dark:text-white">Laba / Rugi (Profit & Loss)</span>
                                    <span class="block text-sm text-slate-500">Pendapatan vs Pengeluaran & Harga Pokok</span>
                                </div>
                            </label>

                            <label class="flex items-center p-4 border border-slate-200 dark:border-slate-700 rounded-xl cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                <input type="radio" name="report_type" value="inventory" class="w-5 h-5 text-primary-600">
                                <div class="ml-3">
                                    <span class="block font-semibold text-slate-800 dark:text-white">Laporan Nilai Stok</span>
                                    <span class="block text-sm text-slate-500">Evaluasi total nilai persediaan barang</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Parameter -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-4">Parameter Laporan</h3>
                        
                        @if(auth()->user()->isSuperAdmin())
                        <div>
                            <label class="label">Pilih Toko / Cabang</label>
                            <select name="store_id" class="input">
                                <option value="all">Semua Toko (Konsolidasi)</option>
                                @foreach($stores as $store)
                                    <option value="{{ $store->id }}">{{ $store->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="label">Dari Tanggal</label>
                                <input type="date" name="start_date" value="{{ date('Y-m-01') }}" class="input" required>
                            </div>
                            <div>
                                <label class="label">Sampai Tanggal</label>
                                <input type="date" name="end_date" value="{{ date('Y-m-t') }}" class="input" required>
                            </div>
                        </div>

                        <div>
                            <label class="label">Format Output</label>
                            <div class="flex gap-4">
                                <label class="flex items-center bg-rose-50 dark:bg-rose-900/20 px-4 py-2.5 rounded-xl border border-rose-200 dark:border-rose-900/50 cursor-pointer w-full justify-center text-rose-700 dark:text-rose-400 font-medium hover:bg-rose-100 dark:hover:bg-rose-900/40">
                                    <input type="radio" name="format" value="pdf" class="mr-2" checked>
                                    <svg class="w-5 h-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                                    Cetak PDF
                                </label>
                                
                                <label class="flex items-center bg-emerald-50 dark:bg-emerald-900/20 px-4 py-2.5 rounded-xl border border-emerald-200 dark:border-emerald-900/50 cursor-pointer w-full justify-center text-emerald-700 dark:text-emerald-400 font-medium hover:bg-emerald-100 dark:hover:bg-emerald-900/40">
                                    <input type="radio" name="format" value="excel" class="mr-2">
                                    <svg class="w-5 h-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                    Unduh Excel
                                </label>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="pt-6 border-t border-slate-100 dark:border-slate-800 flex justify-end">
                    <button type="submit" class="btn btn-primary py-3 px-8 text-lg shadow-lg">
                        Buat Laporan
                    </button>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>
