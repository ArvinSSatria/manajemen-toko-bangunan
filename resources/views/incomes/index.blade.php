<x-app-layout>
    <x-slot name="header">Kelola Pemasukan</x-slot>
    <x-slot name="title">Daftar Pemasukan</x-slot>

    <div class="card p-6">
        <div class="mb-6 space-y-4">
            <div>
                <h2 class="text-section-title text-gray-900 dark:text-white">Riwayat Pemasukan (Income)</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400">Data pemasukan otomatis tercatat dari setiap transaksi penjualan tunai dan pelunasan piutang.</p>
            </div>
            
            <form action="{{ route('incomes.index') }}" method="GET" class="flex flex-wrap items-center gap-3 w-full filter-bar">
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
                    <select name="type" class="input w-full" onchange="this.form.submit()">
                        <option value="">Semua Pemasukan</option>
                        <option value="revenue" {{ request('type') == 'revenue' ? 'selected' : '' }}>Pendapatan Riil (Penjualan & Piutang)</option>
                        <option value="deposit" {{ request('type') == 'deposit' ? 'selected' : '' }}>Titipan Dana / Deposit</option>
                    </select>
                </div>

                <div class="flex items-center gap-2 w-full sm:w-auto flex-grow sm:flex-grow-0">
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="input w-full" title="Dari Tanggal">
                    <span class="text-slate-400 font-medium">-</span>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="input w-full" title="Sampai Tanggal">
                </div>
                
                <div class="w-full sm:w-auto flex justify-end flex-grow">
                    <button type="submit" class="btn btn-primary w-full sm:w-auto shrink-0">Filter</button>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Toko / Cabang</th>
                        <th>Tanggal</th>
                        <th>Keterangan / Sumber</th>
                        <th>Tipe Sumber</th>
                        <th>Metode</th>
                        <th class="text-right">Nominal</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($incomes as $income)
                        <tr>
                            <td><span class="badge-primary">{{ $income->store->name }}</span></td>
                            <td>{{ $income->date->format('d/m/Y') }}</td>
                            <td class="font-medium text-slate-800 dark:text-white">{{ $income->description }}</td>
                            <td>
                                @if(str_contains($income->source_type, 'Sale'))
                                    <span class="badge-success">Penjualan (Kasir)</span>
                                @elseif(str_contains($income->source_type, 'ReceivablePayment'))
                                    <span class="badge-info">Pelunasan Piutang</span>
                                @elseif(str_contains($income->source_type, 'Customer'))
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-400 border border-amber-200 dark:border-amber-800">Titipan Deposit</span>
                                @else
                                    <span class="text-slate-500">-</span>
                                @endif
                            </td>
                            <td>
                                @if(str_contains($income->source_type, 'Sale') && $income->source)
                                    {{ $income->source->payment_method }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-right font-bold text-emerald-600 dark:text-emerald-400">
                                + Rp {{ number_format($income->amount, 0, ',', '.') }}
                            </td>
                            <td class="text-center">
                                @if(str_contains($income->source_type, 'Sale') && $income->source_id)
                                    <button onclick="window.open('{{ route('sales.receipt', $income->source_id) }}', '_blank', 'width=400,height=600')" class="btn-icon text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800" title="Cetak Struk">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                                    </button>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-slate-500">
                                Tidak ada data pemasukan pada periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $incomes->links() }}
        </div>
    </div>
</x-app-layout>
