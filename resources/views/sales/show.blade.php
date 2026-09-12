<x-app-layout>
    <x-slot name="header">Detail Transaksi</x-slot>
    <x-slot name="title">Invoice {{ $sale->invoice_number }}</x-slot>

    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('sales.index') }}" class="btn-secondary">
            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            Kembali
        </a>
        <button onclick="window.open('{{ route('sales.receipt', $sale) }}', '_blank', 'width=400,height=600')" class="btn-primary">
            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
            Cetak Struk
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <!-- Items Card -->
            <div class="card p-0 overflow-hidden shadow-sm">
                <div class="p-5 sm:p-6 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center bg-slate-50/50 dark:bg-slate-800/20">
                    <h3 class="text-[15px] font-semibold text-slate-800 dark:text-white">Rincian Belanja</h3>
                    <span class="badge-{{ $sale->status->color() }} font-medium px-2.5 py-1">{{ $sale->status->label() }}</span>
                </div>
                
                <table class="table w-full">
                    <thead class="bg-white dark:bg-transparent">
                        <tr>
                            <th class="py-3 px-6 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800">Produk</th>
                            <th class="py-3 px-6 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800">Qty</th>
                            <th class="py-3 px-6 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800">Harga</th>
                            <th class="py-3 px-6 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 dark:divide-slate-800/50">
                        @foreach($sale->items as $item)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/20 transition-colors">
                            <td class="py-4 px-6">
                                <p class="font-semibold text-[14px] text-slate-800 dark:text-white flex items-center flex-wrap gap-2">
                                    {{ $item->product->name }}
                                    @if($item->product->is_consignment && $item->product->bo)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100/80 text-amber-800 dark:bg-amber-900/40 dark:text-amber-400 border border-amber-200/50 dark:border-amber-800">
                                            Titipan: {{ $item->product->bo->name }}
                                        </span>
                                    @endif
                                </p>
                                <p class="text-[13px] text-slate-500 mt-0.5">{{ $item->product->product_code }}</p>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <div class="inline-flex items-baseline gap-1.5 justify-center">
                                    <span class="font-bold text-[16px] text-slate-800 dark:text-white">{{ $item->qty }}</span>
                                    <span class="text-[13px] text-slate-500 font-medium">{{ $item->product->unit }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-right text-[14px] text-slate-700 dark:text-slate-300">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td class="py-4 px-6 text-right font-bold text-[15px] text-slate-800 dark:text-white">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-slate-50/50 dark:bg-slate-800/20">
                        <tr>
                            <td colspan="3" class="py-4 px-6 text-right text-[13px] font-semibold text-slate-600 dark:text-slate-400">Total Tagihan</td>
                            <td class="py-4 px-6 text-right text-[18px] font-bold text-primary-600 dark:text-primary-400">Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="py-3 px-6 text-right text-[13px] text-slate-500 font-medium">Telah Dibayar</td>
                            <td class="py-3 px-6 text-right font-semibold text-[15px] text-emerald-600 dark:text-emerald-400">Rp {{ number_format($sale->paid_amount, 0, ',', '.') }}</td>
                        </tr>
                        @if($sale->remaining_balance > 0)
                        <tr>
                            <td colspan="3" class="py-3 px-6 text-right text-[13px] text-slate-500 font-medium border-t border-slate-100 dark:border-slate-800/50">Sisa Tagihan (Piutang)</td>
                            <td class="py-3 px-6 text-right font-semibold text-[15px] text-rose-500 border-t border-slate-100 dark:border-slate-800/50">Rp {{ number_format($sale->remaining_balance, 0, ',', '.') }}</td>
                        </tr>
                        @endif
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="space-y-6">
            <!-- Info Card -->
            <div class="card p-6 shadow-sm">
                <h3 class="text-[15px] font-semibold text-slate-800 dark:text-white mb-5">Informasi Transaksi</h3>
                
                <div class="space-y-5">
                    <div>
                        <p class="label mb-1.5">No. Invoice</p>
                        <p class="font-mono text-[14px] font-medium text-slate-800 dark:text-white">{{ $sale->invoice_number }}</p>
                    </div>
                    <div>
                        <p class="label mb-1.5">Tanggal & Waktu</p>
                        <p class="text-[14px] font-medium text-slate-800 dark:text-white">{{ $sale->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div>
                        <p class="label mb-1.5">Metode Pembayaran</p>
                        <p class="text-[14px] font-medium text-slate-800 dark:text-white">
                            {{ $sale->payment_method }}
                            @if($sale->payment_fee > 0)
                                <span class="text-[12px] text-slate-500 italic font-normal ml-1">(Biaya admin Rp {{ number_format($sale->payment_fee, 0, ',', '.') }})</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="label mb-2">Kasir</p>
                        <p class="font-medium text-slate-800 dark:text-white">{{ $sale->creator->name ?? 'System' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Toko / Cabang</p>
                        <p class="font-medium text-slate-800 dark:text-white"><span class="badge-primary">{{ $sale->store->name }}</span></p>
                    </div>
                </div>
            </div>

            <!-- Customer Card -->
            <div class="card p-6">
                <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-4 border-b border-slate-100 dark:border-slate-800 pb-2">Informasi Pelanggan</h3>
                
                @if($sale->customer)
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-500 flex items-center justify-center text-lg font-bold">
                            {{ substr($sale->customer->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="font-bold text-slate-800 dark:text-white">{{ $sale->customer->name }}</p>
                            <p class="text-sm text-slate-500 flex items-center gap-1 mt-0.5">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                                {{ $sale->customer->phone ?? 'Tidak ada telepon' }}
                            </p>
                        </div>
                    </div>
                    @if($sale->customer->address)
                    <div class="bg-slate-50 dark:bg-slate-800/50 p-3 rounded-xl">
                        <p class="text-xs text-slate-500 mb-1">Alamat Lengkap:</p>
                        <p class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed">{{ $sale->customer->address }}</p>
                    </div>
                    @endif

                    @if($sale->status->value === 'UNPAID')
                        <a href="{{ route('receivables.show', $sale->receivable) }}" class="btn btn-primary w-full mt-4 justify-center">Lihat Detail Piutang</a>
                    @endif
                </div>
                @else
                <div class="text-center py-6 text-slate-500">
                    <svg class="w-12 h-12 mx-auto text-slate-300 dark:text-slate-600 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    <p class="font-medium">Pelanggan Umum</p>
                    <p class="text-xs mt-1">Data tidak direkam.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
