<x-app-layout>
    <x-slot name="header">Detail Pelanggan & Riwayat Deposit</x-slot>
    <x-slot name="title">Riwayat Deposit: {{ $customer->name }}</x-slot>

    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('customers.index') }}" class="btn-secondary">
            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Customer Info & Balance Card -->
        <div class="lg:col-span-1 space-y-6">
            <div class="card p-0 overflow-hidden shadow-md ring-1 ring-slate-200 dark:ring-slate-800">
                <div class="p-6 bg-gradient-to-br from-primary-600 to-primary-700 text-white">
                    <div class="flex items-center gap-4 mb-5">
                        <div class="w-16 h-16 rounded-2xl bg-white/20 text-white flex items-center justify-center text-[28px] font-bold shrink-0 shadow-inner">
                            {{ substr($customer->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="font-bold text-[18px]">{{ $customer->name }}</p>
                            <p class="text-[14px] text-primary-100 flex items-center gap-1.5 mt-1">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                                {{ $customer->phone ?? 'Tidak ada telepon' }}
                            </p>
                        </div>
                    </div>
                    
                    <div class="mt-6 pt-5 border-t border-white/20">
                        <p class="text-white/80 text-[12px] uppercase tracking-wider font-semibold mb-1">Total Saldo Deposit Saat Ini</p>
                        <h2 class="text-[32px] font-bold tracking-tight">Rp {{ number_format($customer->deposit_balance, 0, ',', '.') }}</h2>
                    </div>
                </div>
                
                @if($customer->address)
                <div class="p-6 bg-white dark:bg-slate-900">
                    <p class="text-[12px] uppercase tracking-wider font-semibold text-slate-500 mb-2">Alamat Lengkap</p>
                    <p class="text-[14px] text-slate-700 dark:text-slate-300 leading-relaxed">{{ $customer->address }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Deposit History Ledger -->
        <div class="lg:col-span-2">
            <div class="card p-6 shadow-sm">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-[16px] font-bold text-slate-800 dark:text-white">Buku Riwayat Mutasi Deposit</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-[14px]">
                        <thead class="bg-slate-50 dark:bg-slate-800/50">
                            <tr>
                                <th class="py-3 px-4 text-left font-semibold text-slate-600 dark:text-slate-300 border-b border-slate-200 dark:border-slate-700">Tanggal</th>
                                <th class="py-3 px-4 text-left font-semibold text-slate-600 dark:text-slate-300 border-b border-slate-200 dark:border-slate-700">Keterangan / Referensi</th>
                                <th class="py-3 px-4 text-right font-semibold text-slate-600 dark:text-slate-300 border-b border-slate-200 dark:border-slate-700">Mutasi (Rp)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse($deposits as $deposit)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                                <td class="py-4 px-4 whitespace-nowrap">
                                    <div class="font-medium text-slate-800 dark:text-white">{{ $deposit->created_at->format('d M Y') }}</div>
                                    <div class="text-[12px] text-slate-500">{{ $deposit->created_at->format('H:i') }}</div>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-2">
                                        @if($deposit->type === 'deposit')
                                            <span class="inline-flex px-2 py-0.5 rounded text-[11px] font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400">MASUK</span>
                                        @else
                                            <span class="inline-flex px-2 py-0.5 rounded text-[11px] font-bold bg-rose-100 text-rose-700 dark:bg-rose-500/20 dark:text-rose-400">KELUAR</span>
                                        @endif
                                        
                                        @if($deposit->reference_id)
                                            <span class="font-mono text-[12px] text-primary-600 dark:text-primary-400 font-semibold">{{ $deposit->reference_id }}</span>
                                        @endif
                                    </div>
                                    <p class="text-[13px] text-slate-600 dark:text-slate-400 mt-1.5 leading-relaxed">{{ $deposit->notes ?? '-' }}</p>
                                </td>
                                <td class="py-4 px-4 text-right whitespace-nowrap font-bold text-[15px] {{ $deposit->type === 'deposit' ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                                    {{ $deposit->type === 'deposit' ? '+' : '-' }} {{ number_format($deposit->amount, 0, ',', '.') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-10 text-slate-500">
                                    <svg class="w-12 h-12 mx-auto text-slate-300 dark:text-slate-600 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                                    <p>Belum ada riwayat mutasi deposit.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($deposits->hasPages())
                <div class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-800">
                    {{ $deposits->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
