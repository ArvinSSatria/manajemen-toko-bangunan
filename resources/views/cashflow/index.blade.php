<x-app-layout>
    <x-slot name="header">Arus Kas (Cashflow)</x-slot>
    <x-slot name="title">Arus Kas</x-slot>

    <div class="card p-6 mb-6">
        <div class="space-y-4">
            <div>
                <h2 class="text-section-title text-gray-900 dark:text-white">Buku Kas Harian</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400">Pencatatan uang masuk dan keluar secara berurutan sesuai waktu kejadian.</p>
            </div>
            
            <form action="{{ route('cashflow.index') }}" method="GET" class="flex flex-wrap items-center gap-3 w-full filter-bar">
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
                    <input type="month" name="month" value="{{ request('month', date('Y-m')) }}" class="input w-full" onchange="this.form.submit()">
                </div>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="card p-6 border-l-4 border-emerald-500 bg-emerald-50 dark:bg-emerald-900/10">
            <p class="text-sm font-medium text-emerald-600 dark:text-emerald-400">Total Uang Masuk</p>
            <h3 class="text-2xl font-bold text-emerald-700 dark:text-emerald-300 mt-1">Rp {{ number_format($totalIn, 0, ',', '.') }}</h3>
        </div>
        <div class="card p-6 border-l-4 border-rose-500 bg-rose-50 dark:bg-rose-900/10">
            <p class="text-sm font-medium text-rose-600 dark:text-rose-400">Total Uang Keluar</p>
            <h3 class="text-2xl font-bold text-rose-700 dark:text-rose-300 mt-1">Rp {{ number_format($totalOut, 0, ',', '.') }}</h3>
        </div>
        <div class="card p-6 border-l-4 border-primary-500 bg-primary-50 dark:bg-primary-900/10">
            <p class="text-sm font-medium text-primary-600 dark:text-primary-400">Selisih (Net Cash)</p>
            <h3 class="text-2xl font-bold text-primary-700 dark:text-primary-300 mt-1">Rp {{ number_format($netCash, 0, ',', '.') }}</h3>
        </div>
    </div>

    <div class="card p-0 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table w-full">
                <thead class="bg-slate-50 dark:bg-slate-800">
                    <tr>
                        <th class="py-3 px-6 text-left">Tanggal</th>
                        <th class="py-3 px-6 text-left">Toko / Cabang</th>
                        <th class="py-3 px-6 text-left">Deskripsi Transaksi</th>
                        <th class="py-3 px-6 text-right">Uang Masuk (Debit)</th>
                        <th class="py-3 px-6 text-right">Uang Keluar (Kredit)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($cashflow as $row)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/20">
                            <td class="py-4 px-6 whitespace-nowrap">{{ \Carbon\Carbon::parse($row['date'])->format('d M Y') }}</td>
                            <td class="py-4 px-6"><span class="badge-primary">{{ $row['store_name'] }}</span></td>
                            <td class="py-4 px-6">
                                <span class="font-medium text-slate-800 dark:text-white block">{{ $row['description'] }}</span>
                                <span class="text-xs text-slate-500">{{ $row['type'] }}</span>
                            </td>
                            <td class="py-4 px-6 text-right font-bold text-emerald-600 dark:text-emerald-400">
                                {{ $row['is_in'] ? 'Rp ' . number_format($row['amount'], 0, ',', '.') : '-' }}
                            </td>
                            <td class="py-4 px-6 text-right font-bold text-rose-500">
                                {{ !$row['is_in'] ? 'Rp ' . number_format($row['amount'], 0, ',', '.') : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-8 text-slate-500">
                                Tidak ada aktivitas arus kas pada periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
