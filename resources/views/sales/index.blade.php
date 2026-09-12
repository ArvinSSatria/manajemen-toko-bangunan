<x-app-layout>
    <x-slot name="header">Daftar Penjualan</x-slot>
    <x-slot name="title">Riwayat Penjualan</x-slot>

    <div class="card p-6">
        <div class="mb-6 space-y-4">
            <h2 class="text-section-title text-gray-900 dark:text-white">Riwayat Transaksi</h2>
            
            <form action="{{ route('sales.index') }}" method="GET" class="flex flex-wrap items-center gap-3 w-full filter-bar">
                @if(auth()->user()->isSuperAdmin())
                <div class="w-full sm:w-36 lg:w-44 shrink-0">
                    <select name="store_id" class="input w-full" onchange="this.form.submit()">
                        <option value="">Semua Toko</option>
                        @foreach($stores as $store)
                            <option value="{{ $store->id }}" {{ $storeId == $store->id ? 'selected' : '' }}>
                                {{ $store->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div class="w-full sm:w-40 lg:w-48 shrink-0">
                    <select name="status" class="input w-full" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="PAID" {{ request('status') === 'PAID' ? 'selected' : '' }}>Lunas</option>
                        <option value="UNPAID" {{ request('status') === 'UNPAID' ? 'selected' : '' }}>Belum Lunas (Piutang)</option>
                    </select>
                </div>

                <div class="flex items-center gap-2 w-full sm:w-auto shrink-0">
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="input w-full sm:w-[135px]" title="Dari Tanggal">
                    <span class="text-slate-400 font-medium">-</span>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="input w-full sm:w-[135px]" title="Sampai Tanggal">
                </div>

                <div class="flex items-stretch w-full sm:flex-1 min-w-[200px]">
                    <div class="relative flex-1">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari No. Invoice..." class="input w-full rounded-r-none border-r-0">
                    </div>
                    <button type="submit" class="btn-secondary rounded-l-none border-l-0 px-4 h-auto shrink-0 shadow-none m-0 focus:z-10 focus:ring-primary-500" title="Cari & Filter">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </button>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Toko</th>
                        <th>Tanggal</th>
                        <th>No. Invoice</th>
                        <th>Pelanggan</th>
                        <th class="text-right">Metode</th>
                        <th class="text-right">Total Transaksi</th>
                        <th class="text-right">Sisa Tagihan</th>
                        <th class="text-center">Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $sale)
                        <tr>
                            <td class="whitespace-nowrap"><span class="badge-primary">{{ $sale->store->name }}</span></td>
                            <td class="whitespace-nowrap">{{ $sale->date->format('d/m/Y') }}</td>
                            <td class="font-mono text-sm text-slate-600 dark:text-slate-400">{{ $sale->invoice_number }}</td>
                            <td class="font-medium text-slate-800 dark:text-white">{{ $sale->customer->name ?? 'Pelanggan Umum' }}</td>
                            <td class="text-right whitespace-nowrap text-sm text-slate-500">{{ $sale->payment_method }}</td>
                            <td class="text-right font-bold text-slate-800 dark:text-white">Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
                            <td class="text-right text-rose-500">
                                {{ $sale->remaining_balance > 0 ? 'Rp ' . number_format($sale->remaining_balance, 0, ',', '.') : '-' }}
                            </td>
                            <td class="text-center">
                                <span class="badge-{{ $sale->status->color() }}">
                                    {{ $sale->status->label() }}
                                </span>
                            </td>
                            <td>
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('sales.show', $sale) }}" class="btn-icon text-primary-500 hover:bg-primary-50 dark:hover:bg-primary-500/10" title="Detail">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    </a>
                                    <button onclick="window.open('{{ route('sales.receipt', $sale) }}', '_blank', 'width=400,height=600')" class="btn-icon text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800" title="Cetak Struk">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-8 text-slate-500">
                                Tidak ada data penjualan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $sales->links() }}
        </div>
    </div>
</x-app-layout>
