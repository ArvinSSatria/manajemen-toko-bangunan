<x-app-layout>
    <x-slot name="header">Detail Piutang</x-slot>
    <x-slot name="title">Piutang {{ $receivable->customer->name }}</x-slot>

    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('receivables.index') }}" class="btn-secondary">
            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <!-- Payment History -->
            <div class="card p-6 shadow-sm">
                <h3 class="text-[15px] font-semibold text-slate-800 dark:text-white mb-5">Riwayat Pembayaran</h3>
                
                @if($receivable->payments->count() > 0)
                <div class="space-y-3">
                    @foreach($receivable->payments as $payment)
                    <div class="flex items-start justify-between p-4 bg-slate-50/80 dark:bg-slate-800/50 rounded-xl border border-slate-100 dark:border-slate-700 transition-colors hover:bg-slate-50 dark:hover:bg-slate-800/80">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <span class="font-bold text-[14px] text-slate-800 dark:text-white">{{ $payment->date->format('d M Y') }}</span>
                            </div>
                            @if($payment->notes)
                                <p class="text-[13px] text-slate-600 mt-1.5">{{ $payment->notes }}</p>
                            @endif
                            <div class="flex items-center gap-2 mt-2">
                                <div class="w-5 h-5 rounded-full bg-slate-200 dark:bg-slate-700 text-slate-500 flex items-center justify-center text-[9px] font-bold">
                                    {{ substr($payment->creator->name ?? 'S', 0, 1) }}
                                </div>
                                <p class="text-[11px] font-medium text-slate-500">Diterima oleh: <span class="text-slate-600 dark:text-slate-400">{{ $payment->creator->name ?? 'System' }}</span></p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="font-bold text-[16px] text-emerald-600 dark:text-emerald-400">+ Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-8 text-slate-500">
                    <svg class="w-12 h-12 mx-auto text-slate-300 dark:text-slate-600 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <p class="text-[14px]">Belum ada riwayat pembayaran.</p>
                </div>
                @endif
            </div>

            <!-- Sale Details -->
            <div class="card p-0 overflow-hidden shadow-sm">
                <div class="p-5 sm:p-6 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center bg-slate-50/50 dark:bg-slate-800/20">
                    <h3 class="text-[15px] font-semibold text-slate-800 dark:text-white">Detail Transaksi Penjualan</h3>
                    <a href="{{ route('sales.show', $receivable->sale_id) }}" class="text-[13px] text-primary-600 hover:text-primary-700 font-semibold inline-flex items-center gap-1 transition-colors">
                        Lihat Invoice <span aria-hidden="true">&rarr;</span>
                    </a>
                </div>
                
                <table class="w-full text-[14px]">
                    <thead class="bg-white dark:bg-transparent">
                        <tr>
                            <th class="py-3 px-6 text-left label border-b border-slate-100 dark:border-slate-800">Produk</th>
                            <th class="py-3 px-6 text-center label border-b border-slate-100 dark:border-slate-800">Qty</th>
                            <th class="py-3 px-6 text-right label border-b border-slate-100 dark:border-slate-800">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 dark:divide-slate-800/50">
                        @foreach($receivable->sale->items as $item)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/20 transition-colors">
                            <td class="py-4 px-6 font-medium text-slate-800 dark:text-white">{{ $item->product->name }}</td>
                            <td class="py-4 px-6 text-center">
                                <span class="font-bold text-slate-800 dark:text-white">{{ $item->qty }}</span>
                                <span class="text-[12px] text-slate-500 font-medium ml-0.5">{{ $item->product->unit }}</span>
                            </td>
                            <td class="py-4 px-6 text-right font-bold text-slate-800 dark:text-white">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="space-y-6">
            <!-- Payment Form -->
            <div class="card p-0 overflow-hidden shadow-md border-0 ring-1 {{ $receivable->status->value === 'PAID' ? 'ring-emerald-500/30' : ($receivable->isOverdue() ? 'ring-rose-500/30' : 'ring-slate-200 dark:ring-slate-800') }}">
                <div class="p-6 {{ $receivable->status->value === 'PAID' ? 'bg-gradient-to-br from-emerald-500 to-emerald-600' : ($receivable->isOverdue() ? 'bg-gradient-to-br from-rose-500 to-rose-600' : 'bg-gradient-to-br from-primary-500 to-primary-600') }} text-white">
                    <div class="flex justify-between items-start mb-5">
                        <div>
                            <p class="text-white/80 text-[12px] uppercase tracking-wider font-semibold mb-1">Status Piutang</p>
                            <h2 class="text-[22px] font-bold">{{ $receivable->status->label() }}</h2>
                        </div>
                        <div class="text-right">
                            <p class="text-white/80 text-[12px] uppercase tracking-wider font-semibold mb-1">Jatuh Tempo</p>
                            <p class="font-bold text-[15px]">{{ $receivable->due_date->format('d M Y') }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-6 pt-5 border-t border-white/20">
                        <div class="flex justify-between items-center mb-2.5">
                            <span class="text-[14px] text-white/90">Tanggal Piutang Masuk</span>
                            <span class="font-bold text-[15px]">{{ $receivable->sale->date->format('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center mb-2.5">
                            <span class="text-[14px] text-white/90">Total Tagihan Awal</span>
                            <span class="font-bold text-[15px]">Rp {{ number_format($receivable->total, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center mb-2.5">
                            <span class="text-[14px] text-white/90">Total Dibayar</span>
                            <span class="font-bold text-[15px] text-white">Rp {{ number_format($receivable->paid, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center mt-5 pt-3 border-t border-white/20">
                            <span class="text-[15px] font-bold text-white/90">Sisa Hutang</span>
                            <span class="text-[20px] font-bold tracking-tight">Rp {{ number_format($receivable->remaining, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
                
                @if($receivable->status->value !== 'PAID')
                <div class="p-6 bg-white dark:bg-slate-900">
                    <form action="{{ route('receivables.pay', $receivable) }}" method="POST" class="space-y-5">
                        @csrf
                        <div x-data="{
                            rawAmount: '{{ $receivable->remaining }}',
                            maxAmount: {{ $receivable->remaining }},
                            get formattedAmount() {
                                if (!this.rawAmount) return '';
                                return parseInt(this.rawAmount).toLocaleString('id-ID');
                            },
                            set formattedAmount(value) {
                                let num = value.toString().replace(/[^0-9]/g, '');
                                if (num !== '' && parseInt(num) > this.maxAmount) {
                                    num = this.maxAmount.toString();
                                }
                                this.rawAmount = num;
                            }
                        }">
                            <label for="amount_text" class="label">Jumlah Pembayaran Baru <span class="text-rose-500">*</span></label>
                            <div class="relative mt-1">
                                <span class="absolute left-3.5 top-3 text-slate-500 font-bold">Rp</span>
                                <input type="text" id="amount_text" x-model="formattedAmount" class="input pl-11 text-[16px] py-2.5 font-bold" required>
                                <input type="hidden" name="amount" x-model="rawAmount">
                            </div>
                            <p class="text-[12px] text-slate-500 font-medium mt-1.5">Maksimal: Rp {{ number_format($receivable->remaining, 0, ',', '.') }}</p>
                        </div>
                        
                        <div>
                            <label for="notes" class="label">Catatan Tambahan (Opsional)</label>
                            <input type="text" id="notes" name="notes" class="input mt-1" placeholder="Misal: Transfer BCA, Titip via supir, dll">
                        </div>
                        
                        <button type="submit" class="btn {{ $receivable->isOverdue() ? 'bg-rose-500 hover:bg-rose-600 border-rose-500 text-white' : 'btn-primary' }} w-full justify-center py-3.5 text-[15px] shadow-lg mt-2" onclick="return confirm('Proses pembayaran ini? Tindakan ini akan otomatis membuat catatan Pemasukan (Income).')">
                            Proses Pembayaran
                        </button>
                    </form>
                </div>
                @else
                <div class="p-8 bg-white dark:bg-slate-900 text-center">
                    <div class="w-16 h-16 bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                    </div>
                    <h3 class="text-[18px] font-bold text-slate-800 dark:text-white mb-2">Lunas</h3>
                    <p class="text-[14px] text-slate-500">Piutang ini telah dilunasi sepenuhnya.</p>
                </div>
                @endif
            </div>

            <!-- Customer Card -->
            <div class="card p-6 shadow-sm">
                <h3 class="text-[15px] font-semibold text-slate-800 dark:text-white mb-5">Informasi Pelanggan</h3>
                
                <div class="flex items-center gap-4 mb-5">
                    <div class="w-14 h-14 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-500 flex items-center justify-center text-[22px] font-bold shrink-0 border border-slate-200 dark:border-slate-700">
                        {{ substr($receivable->customer->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="font-bold text-[16px] text-slate-800 dark:text-white">{{ $receivable->customer->name }}</p>
                        <p class="text-[13px] text-slate-500 flex items-center gap-1.5 mt-1 font-medium">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                            {{ $receivable->customer->phone ?? 'Tidak ada telepon' }}
                        </p>
                    </div>
                </div>
                @if($receivable->customer->address)
                <div class="bg-slate-50/80 dark:bg-slate-800/50 p-4 rounded-xl border border-slate-100 dark:border-slate-800">
                    <p class="label mb-1.5">Alamat Lengkap:</p>
                    <p class="text-[13.5px] text-slate-700 dark:text-slate-300 leading-relaxed">{{ $receivable->customer->address }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
