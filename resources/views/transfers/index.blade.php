<x-app-layout>
    <x-slot name="header">Mutasi Stok</x-slot>
    <x-slot name="title">Riwayat Mutasi Stok</x-slot>

    <div class="card p-6">
        <div class="mb-6 space-y-4">
            <h2 class="text-section-title text-gray-900 dark:text-white">Riwayat Transfer Antar Toko</h2>
            
            <form action="{{ route('transfers.index') }}" method="GET" class="flex flex-wrap items-center gap-3 w-full filter-bar">
                <div class="w-full sm:w-auto flex-grow sm:flex-grow-0">
                    <select name="status" class="input w-full" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="PENDING" {{ request('status') === 'PENDING' ? 'selected' : '' }}>Menunggu (Pending)</option>
                        <option value="COMPLETED" {{ request('status') === 'COMPLETED' ? 'selected' : '' }}>Selesai</option>
                        <option value="CANCELLED" {{ request('status') === 'CANCELLED' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>

                <div class="flex items-stretch">
                    <div class="relative w-full sm:w-72 lg:w-80 shrink-0">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari No. Referensi..." class="input w-full rounded-r-none border-r-0">
                    </div>
                    <button type="submit" class="btn-secondary rounded-l-none border-l-0 px-4 h-auto shrink-0 shadow-none m-0 focus:z-10 focus:ring-primary-500" title="Cari">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </button>
                </div>
                
                <div class="w-full sm:w-auto flex gap-2 justify-end ml-auto">
                    <button type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', 'create-transfer')" class="btn-primary w-full sm:w-auto shrink-0">
                        <svg class="w-5 h-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                        Buat Transfer
                    </button>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>No. Referensi</th>
                        <th>Toko Asal &rarr; Tujuan</th>
                        <th>Jml Item</th>
                        <th class="text-center">Status</th>
                        <th>Dibuat Oleh</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transfers as $transfer)
                        <tr>
                            <td>{{ $transfer->date->format('d/m/Y') }} <br><span class="text-xs text-slate-400">{{ $transfer->created_at->format('H:i') }}</span></td>
                            <td class="font-mono text-sm text-slate-600 dark:text-slate-400">TRF-{{ str_pad($transfer->id, 5, '0', STR_PAD_LEFT) }}</td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <span class="badge-{{ $transfer->from_store_id == auth()->user()->store_id ? 'primary' : 'secondary' }} text-xs">{{ $transfer->fromStore->name }}</span>
                                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                                    <span class="badge-{{ $transfer->to_store_id == auth()->user()->store_id ? 'primary' : 'secondary' }} text-xs">{{ $transfer->toStore->name }}</span>
                                </div>
                            </td>
                            <td><span class="font-bold text-slate-800 dark:text-white">{{ $transfer->items_count }}</span> <span class="text-xs text-slate-500">Jenis Barang</span></td>
                            <td class="text-center">
                                <span class="badge-{{ $transfer->status->color() }}">
                                    {{ $transfer->status->label() }}
                                </span>
                            </td>
                            <td class="text-sm text-slate-600 dark:text-slate-400">{{ $transfer->creator->name ?? '-' }}</td>
                            <td>
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('transfers.show', $transfer) }}" class="btn-icon bg-slate-100 text-slate-600 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-slate-700" title="Lihat Detail">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-slate-500">
                                Tidak ada riwayat mutasi stok.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $transfers->links() }}
        </div>
    </div>

    {{-- Create Transfer Modal --}}
    <x-modal name="create-transfer" focusable maxWidth="3xl">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-[15px] font-semibold text-slate-800 dark:text-white">Buat Transfer Stok Baru</h2>
                <button type="button" x-on:click="$dispatch('close')" class="p-1.5 hover:bg-slate-200/50 rounded text-slate-400 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            
            <form action="{{ route('transfers.store') }}" method="POST" class="flex-1 flex flex-col min-h-0">
                @csrf
                <div class="flex-1 overflow-y-auto flex flex-col space-y-5" x-data="{ items: [{ product_id: '', qty: 1 }] }">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="from_store_id" class="label">Toko Asal <span class="text-rose-500">*</span></label>
                            <select id="from_store_id" name="from_store_id" class="input @error('from_store_id') border-rose-500 @enderror" required>
                                <option value="">Pilih Toko Asal...</option>
                                @foreach($stores as $store)
                                    <option value="{{ $store->id }}" {{ old('from_store_id', auth()->user()->store_id) == $store->id ? 'selected' : '' }}>
                                        {{ $store->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('from_store_id') <p class="text-rose-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="to_store_id" class="label">Toko Tujuan <span class="text-rose-500">*</span></label>
                            <select id="to_store_id" name="to_store_id" class="input @error('to_store_id') border-rose-500 @enderror" required>
                                <option value="">Pilih Toko Tujuan...</option>
                                @foreach($stores as $store)
                                    <option value="{{ $store->id }}" {{ old('to_store_id') == $store->id ? 'selected' : '' }}>
                                        {{ $store->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('to_store_id') <p class="text-rose-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="border-t border-slate-100 dark:border-slate-800 pt-5 mt-5">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-medium text-slate-800 dark:text-white">Daftar Barang</h3>
                            <button type="button" @click="items.push({ product_id: '', qty: 1 })" class="btn-secondary text-sm">
                                + Tambah Baris
                            </button>
                        </div>
                        
                        <div class="space-y-3">
                            <template x-for="(item, index) in items" :key="index">
                                <div class="flex items-start gap-3 bg-slate-50 dark:bg-slate-800/50 p-3 rounded-lg border border-slate-200 dark:border-slate-700">
                                    <div class="flex-1">
                                        <label class="label text-[11px] mb-1">Pilih Produk</label>
                                        <select x-model="item.product_id" :name="'items['+index+'][product_id]'" class="input py-1.5 text-sm" required>
                                            <option value="">Pilih Produk...</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}">
                                                    {{ $product->name }} ({{ $product->product_code }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="w-32">
                                        <label class="label text-[11px] mb-1">Kuantitas</label>
                                        <input type="number" x-model="item.qty" :name="'items['+index+'][qty]'" min="1" class="input py-1.5 text-sm" required>
                                    </div>
                                    <div class="pt-6">
                                        <button type="button" @click="items.splice(index, 1)" x-show="items.length > 1" class="p-1.5 text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-500/10 rounded transition-colors" title="Hapus Baris">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-5 mt-5 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" x-on:click="$dispatch('close')" class="btn-secondary">Batal</button>
                    <button type="submit" class="btn-primary">Proses Transfer</button>
                </div>
            </form>
        </div>
    </x-modal>
</x-app-layout>
