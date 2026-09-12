<x-app-layout>
    <x-slot name="header">Kelola Pengeluaran</x-slot>
    <x-slot name="title">Daftar Pengeluaran</x-slot>

    <div class="card p-6">
        <div class="mb-6 space-y-4">
            <h2 class="text-section-title text-gray-900 dark:text-white">Riwayat Pengeluaran Operasional</h2>
            
            <form action="{{ route('expenses.index') }}" method="GET" class="flex flex-wrap items-center gap-3 w-full filter-bar">
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

                <div class="flex items-center gap-2 w-full sm:w-auto flex-grow sm:flex-grow-0">
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="input w-full" title="Dari Tanggal">
                    <span class="text-slate-400 font-medium">-</span>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="input w-full" title="Sampai Tanggal">
                </div>
                
                <div class="w-full sm:w-auto flex gap-2 justify-end flex-grow">
                    <button type="submit" class="btn btn-primary w-full sm:w-auto shrink-0">Filter</button>
                    
                    <a href="{{ route('expenses.create') }}" class="btn bg-rose-500 text-white hover:bg-rose-600 w-full sm:w-auto shrink-0">
                        <svg class="w-5 h-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Catat Pengeluaran
                    </a>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Toko / Cabang</th>
                        <th>Tanggal</th>
                        <th>Keterangan Pengeluaran</th>
                        <th class="text-right">Nominal</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenses as $expense)
                        <tr>
                            <td><span class="badge-primary">{{ $expense->store->name }}</span></td>
                            <td>{{ $expense->date->format('d/m/Y') }}</td>
                            <td class="font-medium text-slate-800 dark:text-white">{{ $expense->description }}</td>
                            <td class="text-right font-bold text-rose-500">
                                - Rp {{ number_format($expense->amount, 0, ',', '.') }}
                            </td>
                            <td>
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('expenses.edit', $expense) }}" class="btn-icon text-amber-500 hover:bg-amber-50 dark:hover:bg-amber-500/10" title="Edit">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </a>
                                    <form action="{{ route('expenses.destroy', $expense) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pengeluaran ini?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-icon text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-500/10" title="Hapus">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-8 text-slate-500">
                                Tidak ada data pengeluaran pada periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $expenses->links() }}
        </div>
    </div>
</x-app-layout>
