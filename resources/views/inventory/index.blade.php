<x-app-layout>
    <x-slot name="header">Inventory & Stok</x-slot>
    <x-slot name="title">Stok Barang</x-slot>

    <div class="card p-6">
        <div class="mb-6 space-y-4">
            <h2 class="text-section-title text-gray-900 dark:text-white">Inventaris Toko</h2>
            
            <form action="{{ route('inventory.index') }}" method="GET" class="flex flex-wrap items-center gap-3 w-full filter-bar">
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
                    <label class="flex items-center gap-2 text-[15px] text-slate-800 dark:text-white whitespace-nowrap bg-[#F2F2F7] dark:bg-[#3A3A3C] px-4 py-2 rounded-[12px] border border-transparent cursor-pointer h-10 w-full sm:w-auto transition-all duration-200">
                        <input type="checkbox" name="low_stock" value="1" onchange="this.form.submit()" {{ request('low_stock') ? 'checked' : '' }} class="rounded text-rose-500 focus:ring-rose-500 bg-white dark:bg-slate-900 border-slate-300 dark:border-slate-600">
                        Hanya Stok Menipis
                    </label>
                </div>

                <div class="flex items-stretch">
                    <div class="relative w-full sm:w-72 lg:w-80 shrink-0">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode/nama produk..." class="input w-full rounded-r-none border-r-0">
                    </div>
                    <button type="submit" class="btn-secondary rounded-l-none border-l-0 px-4 h-auto shrink-0 shadow-none m-0 focus:z-10 focus:ring-primary-500" title="Cari">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </button>
                </div>
                
                <div class="w-full sm:w-auto flex gap-2 justify-end ml-auto">
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Toko / Cabang</th>
                        <th>Kode</th>
                        <th>Produk & Kategori</th>
                        <th>Satuan</th>
                        <th class="text-center">Min. Stok</th>
                        <th class="text-center">Stok Tersedia</th>
                        <th>Status</th>
                        <th class="text-right">Update Manual</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($inventories as $inv)
                        <tr class="{{ $inv->isLowStock() ? 'bg-rose-50/50 dark:bg-rose-900/10' : '' }}">
                            <td class="whitespace-nowrap"><span class="badge-primary">{{ $inv->store->name }}</span></td>
                            <td class="font-mono text-sm text-slate-500">{{ $inv->product->product_code }}</td>
                            <td>
                                <div class="font-medium text-slate-800 dark:text-white">
                                    {{ $inv->product->name }}
                                    @if($inv->product->is_consignment && $inv->product->bo)
                                        <div class="mt-1">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-400 border border-amber-200 dark:border-amber-800">
                                                Titipan: {{ $inv->product->bo->name }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                <div class="text-xs text-slate-500 mt-0.5">{{ $inv->product->category->name }}</div>
                            </td>
                            <td>{{ $inv->product->unit }}</td>
                            <td class="text-center text-slate-500">{{ $inv->minimum_stock }}</td>
                            <td class="text-center">
                                <span class="font-bold text-lg {{ $inv->isLowStock() ? 'text-rose-600 dark:text-rose-400' : 'text-slate-800 dark:text-white' }}">
                                    {{ $inv->stock }}
                                </span>
                            </td>
                            <td>
                                @if($inv->stock <= 0)
                                    <span class="badge-danger">Habis</span>
                                @elseif($inv->isLowStock())
                                    <span class="badge-warning">Menipis</span>
                                @else
                                    <span class="badge-success">Aman</span>
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('inventory.update', $inv) }}" method="POST" class="flex items-center justify-end gap-2">
                                    @csrf
                                    @method('PUT')
                                    
                                    <div class="flex items-center gap-1 w-24">
                                        <input type="number" name="stock" value="{{ $inv->stock }}" class="input !py-1 !px-2 text-center text-sm w-full font-bold" min="0" required title="Update Stok">
                                    </div>
                                    <input type="hidden" name="minimum_stock" value="{{ $inv->minimum_stock }}">
                                    
                                    <button type="submit" class="btn-icon text-primary-600 hover:bg-primary-50 dark:text-primary-400 dark:hover:bg-primary-900/30" title="Simpan Perubahan">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-8 text-slate-500">
                                Tidak ada data inventory.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $inventories->links() }}
        </div>
    </div>
</x-app-layout>
