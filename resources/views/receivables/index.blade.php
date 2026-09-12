<x-app-layout>
    <x-slot name="header">Kelola Piutang</x-slot>
    <x-slot name="title">Daftar Piutang</x-slot>

    <div class="card p-6">
        <div class="mb-6 space-y-4">
            <h2 class="text-section-title text-gray-900 dark:text-white">Daftar Piutang Pelanggan</h2>
            
            <form action="{{ route('receivables.index') }}" method="GET" class="flex flex-wrap items-center gap-3 w-full filter-bar">
                @if(auth()->user()->isSuperAdmin())
                <div class="w-full sm:w-auto flex-grow sm:flex-grow-0">
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

                <div class="w-full sm:w-auto flex-grow sm:flex-grow-0">
                    <select name="status" class="input w-full" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="UNPAID" {{ request('status') === 'UNPAID' ? 'selected' : '' }}>Belum Lunas</option>
                        <option value="OVERDUE" {{ request('status') === 'OVERDUE' ? 'selected' : '' }}>Jatuh Tempo</option>
                        <option value="PAID" {{ request('status') === 'PAID' ? 'selected' : '' }}>Lunas</option>
                    </select>
                </div>

                <div class="relative w-full sm:w-72 lg:w-80 shrink-0">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama pelanggan..." class="input w-full">
                </div>
                
                <div class="w-full sm:w-auto ml-auto">
                    <button type="submit" class="btn btn-primary w-full sm:w-auto">Filter</button>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Pelanggan</th>
                        <th>Toko</th>
                        <th>No. Invoice</th>
                        <th>Jatuh Tempo</th>
                        <th class="text-right">Total Piutang</th>
                        <th class="text-right">Sisa Tagihan</th>
                        <th class="text-center">Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($receivables as $receivable)
                        <tr class="{{ $receivable->isOverdue() ? 'bg-rose-50/50 dark:bg-rose-900/10' : '' }}">
                            <td>
                                <div class="font-medium text-slate-800 dark:text-white">{{ $receivable->customer->name }}</div>
                                <div class="text-xs text-slate-500">{{ $receivable->customer->phone }}</div>
                            </td>
                            <td><span class="badge-primary">{{ $receivable->store->name }}</span></td>
                            <td>
                                <a href="{{ route('sales.show', $receivable->sale_id) }}" class="font-mono text-sm text-primary-600 hover:underline">
                                    {{ $receivable->sale->invoice_number }}
                                </a>
                            </td>
                            <td>
                                <span class="{{ $receivable->isOverdue() ? 'text-rose-600 font-bold' : '' }}">
                                    {{ $receivable->due_date->format('d/m/Y') }}
                                </span>
                            </td>
                            <td class="text-right font-medium text-slate-800 dark:text-white">Rp {{ number_format($receivable->total, 0, ',', '.') }}</td>
                            <td class="text-right font-bold text-rose-500">Rp {{ number_format($receivable->remaining, 0, ',', '.') }}</td>
                            <td class="text-center">
                                <span class="badge-{{ $receivable->status->color() }}">
                                    {{ $receivable->status->label() }}
                                </span>
                            </td>
                            <td>
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('receivables.show', $receivable) }}" class="btn-icon {{ $receivable->status->value !== 'PAID' ? 'bg-primary-100 text-primary-600 hover:bg-primary-200 dark:bg-primary-900/30 dark:hover:bg-primary-900/50' : 'text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800' }}" title="Detail & Bayar">
                                        @if($receivable->status->value !== 'PAID')
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                        @else
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        @endif
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-8 text-slate-500">
                                Tidak ada data piutang.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $receivables->links() }}
        </div>
    </div>
</x-app-layout>
