<x-app-layout>
    <x-slot name="header">Kelola Produk</x-slot>
    <x-slot name="title">Daftar Produk</x-slot>

    <div class="card p-6">
        <div class="mb-6 space-y-4">
            <h2 class="text-section-title text-gray-900 dark:text-white">Katalog Produk</h2>
            
            <div class="filter-bar">
                <form action="{{ route('products.index') }}" method="GET" class="flex flex-wrap items-center gap-3 w-full">
                    <div class="w-full sm:w-auto flex-grow sm:flex-grow-0">
                        <select name="category_id" class="input w-full" onchange="this.form.submit()">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="w-full sm:w-auto flex-grow sm:flex-grow-0">
                        <select name="bo_id" class="input w-full" onchange="this.form.submit()">
                            <option value="">Semua Kepemilikan</option>
                            <option value="own" {{ request('bo_id') === 'own' ? 'selected' : '' }}>Milik Sendiri</option>
                            <optgroup label="Titipan BO (Konsinyasi)">
                                @foreach($bos as $bo)
                                    <option value="{{ $bo->id }}" {{ request('bo_id') == $bo->id ? 'selected' : '' }}>
                                        {{ $bo->name }}
                                    </option>
                                @endforeach
                            </optgroup>
                        </select>
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
                        <button type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', 'create-product')" class="btn btn-primary w-full sm:w-auto shrink-0">
                            <svg class="w-5 h-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="overflow-x-auto min-h-[500px]">
            <table class="table table-fixed w-full min-w-[900px] !border-separate" style="border-spacing: 0;">
                <thead class="bg-slate-50 dark:bg-slate-800/50">
                    <tr>
                        <th class="w-[6%] text-left">Foto</th>
                        <th class="w-[10%] text-left">Kode</th>
                        <th class="w-[28%] text-left">Nama Produk</th>
                        <th class="text-left w-[15%]">Kategori</th>
                        <th class="text-left w-[8%]">Satuan</th>
                        <th class="text-right w-[14%]">Harga Beli</th>
                        <th class="text-right w-[14%]">Harga Jual</th>
                        <th class="text-right w-[5%]"></th> <!-- For chevron -->
                    </tr>
                </thead>
                <tbody x-data="{ expandedId: null }">
                    @forelse($products as $product)
                        <tr :class="{ 'bg-slate-50 dark:bg-slate-800/30': expandedId === {{ $product->id }} }" class="transition-colors cursor-pointer group hover:bg-slate-50 dark:hover:bg-slate-800/30" @click="expandedId = expandedId === {{ $product->id }} ? null : {{ $product->id }}">
                            <td class="p-3 transition-colors" :class="{ 'rounded-tl-2xl border-l border-t !border-b-0 !border-slate-300 dark:!border-slate-600': expandedId === {{ $product->id }} }">
                                @if($product->image_path)
                                    <img src="{{ Storage::url($product->image_path) }}" class="w-10 h-10 object-cover rounded-lg border border-slate-200 dark:border-slate-700">
                                @else
                                    <div class="w-10 h-10 rounded-lg bg-slate-100 dark:bg-slate-800 flex items-center justify-center border border-slate-200 dark:border-slate-700">
                                        <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    </div>
                                @endif
                            </td>
                            <td class="font-mono text-sm text-slate-500 text-left truncate transition-colors" :class="{ 'border-t !border-b-0 !border-slate-300 dark:!border-slate-600': expandedId === {{ $product->id }} }">{{ $product->product_code }}</td>
                            <td class="font-medium text-slate-800 dark:text-white text-left truncate transition-colors" :class="{ 'border-t !border-b-0 !border-slate-300 dark:!border-slate-600': expandedId === {{ $product->id }} }">
                                {{ $product->name }}
                                @if($product->is_consignment && $product->bo)
                                    <div class="mt-1">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-400 border border-amber-200 dark:border-amber-800">
                                            Titipan: {{ $product->bo->name }}
                                        </span>
                                    </div>
                                @endif
                            </td>
                            <td class="text-left transition-colors" :class="{ 'border-t !border-b-0 !border-slate-300 dark:!border-slate-600': expandedId === {{ $product->id }} }">
                                <span class="inline-block whitespace-nowrap px-3 py-1 rounded-full text-xs font-medium border border-primary-200 bg-primary-50 text-primary-700 dark:border-primary-800/50 dark:bg-primary-900/20 dark:text-primary-400">
                                    {{ $product->category->name }}
                                </span>
                            </td>
                            <td class="text-left text-slate-500 transition-colors" :class="{ 'border-t !border-b-0 !border-slate-300 dark:!border-slate-600': expandedId === {{ $product->id }} }">{{ $product->unit }}</td>
                            <td class="text-right text-slate-500 transition-colors" :class="{ 'border-t !border-b-0 !border-slate-300 dark:!border-slate-600': expandedId === {{ $product->id }} }">Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</td>
                            <td class="text-right font-semibold text-primary-600 dark:text-primary-400 transition-colors" :class="{ 'border-t !border-b-0 !border-slate-300 dark:!border-slate-600': expandedId === {{ $product->id }} }">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</td>
                            <td class="text-right p-3 transition-colors" :class="{ 'rounded-tr-2xl border-r border-t !border-b-0 !border-slate-300 dark:!border-slate-600': expandedId === {{ $product->id }} }">
                                <button type="button" class="p-1 rounded-full text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors" @click.stop="expandedId = expandedId === {{ $product->id }} ? null : {{ $product->id }}">
                                    <svg class="w-5 h-5 transform transition-transform" :class="{ 'rotate-180': expandedId === {{ $product->id }} }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </button>
                            </td>
                        </tr>
                        
                        <!-- Expandable Edit Panel -->
                        <tr x-show="expandedId === {{ $product->id }}" x-cloak>
                            <td colspan="8" class="!p-0 !border-b-0">
                                <div class="p-6 md:p-8 border-x border-b border-slate-300 dark:border-slate-600 border-t border-t-slate-200 dark:border-t-slate-700/50 bg-slate-50 dark:bg-slate-800/30 shadow-none rounded-b-2xl" x-data="{ 
                                    isConsignment: {{ $product->is_consignment ? 'true' : 'false' }}, 
                                    imagePreview: null,
                                    purchasePrice: '{{ number_format((int)$product->purchase_price, 0, '', '.') }}',
                                    sellingPrice: '{{ number_format((int)$product->selling_price, 0, '', '.') }}',
                                    formatRupiah(val) {
                                        let raw = val.toString().replace(/[^0-9]/g, '');
                                        return raw ? new Intl.NumberFormat('id-ID').format(raw) : '';
                                    },
                                    unformatRupiah(val) {
                                        return val.toString().replace(/[^0-9]/g, '');
                                    }
                                }">
                                    <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data" class="flex flex-col lg:flex-row gap-8">
                                        @csrf
                                        @method('PUT')
                                        
                                        <!-- Image Upload -->
                                        <div class="w-full lg:w-48 flex-shrink-0 flex flex-col gap-3">
                                            <label class="cursor-pointer group relative block w-full aspect-square rounded-2xl border-2 border-dashed border-slate-300 dark:border-slate-700 hover:border-primary-500 dark:hover:border-primary-500 overflow-hidden bg-[#F2F2F7] dark:bg-[#3A3A3C] transition-colors">
                                                <template x-if="imagePreview">
                                                    <img :src="imagePreview" class="w-full h-full object-cover">
                                                </template>
                                                <template x-if="!imagePreview">
                                                    @if($product->image_path)
                                                        <img src="{{ Storage::url($product->image_path) }}" class="w-full h-full object-cover">
                                                    @else
                                                        <div class="absolute inset-0 flex flex-col items-center justify-center text-slate-400 group-hover:text-primary-500 transition-colors">
                                                            <svg class="w-8 h-8 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                                            <span class="text-sm font-medium">+ Add image</span>
                                                        </div>
                                                    @endif
                                                </template>
                                                <input type="file" name="image" class="hidden" accept="image/*" @change="imagePreview = URL.createObjectURL($event.target.files[0])">
                                            </label>
                                        </div>

                                        <!-- Form Fields -->
                                        <div class="w-full flex-1">
                                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-5">
                                                <div>
                                                    <label class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Nama Produk / Display Name</label>
                                                    <input type="text" name="name" value="{{ $product->name }}" class="input w-full mt-1 bg-slate-50 dark:bg-slate-800 border-slate-300 dark:border-slate-600 focus:bg-white dark:focus:bg-slate-900 text-sm" required>
                                                </div>
                                                <div>
                                                    <label class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Kode / SKU / Barcode</label>
                                                    <input type="text" name="product_code" value="{{ $product->product_code }}" class="input w-full mt-1 bg-slate-50 dark:bg-slate-800 border-slate-300 dark:border-slate-600 focus:bg-white dark:focus:bg-slate-900 text-sm font-mono" required>
                                                </div>
                                                <div>
                                                    <label class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Kategori / Category</label>
                                                    <select name="category_id" class="input w-full mt-1 bg-slate-50 dark:bg-slate-800 border-slate-300 dark:border-slate-600 focus:bg-white dark:focus:bg-slate-900 text-sm" required>
                                                        @foreach($categories as $category)
                                                            <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Satuan / Unit</label>
                                                    <input type="text" name="unit" value="{{ $product->unit }}" class="input w-full mt-1 bg-slate-50 dark:bg-slate-800 border-slate-300 dark:border-slate-600 focus:bg-white dark:focus:bg-slate-900 text-sm" required>
                                                </div>
                                                <div>
                                                    <label class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Harga Beli / Cost</label>
                                                    <div class="relative mt-1">
                                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"><span class="text-slate-400 sm:text-sm">Rp</span></div>
                                                        <input type="hidden" name="purchase_price" :value="unformatRupiah(purchasePrice)">
                                                        <input type="text" x-model="purchasePrice" @input="purchasePrice = formatRupiah($event.target.value)" class="input w-full pl-9 bg-slate-50 dark:bg-slate-800 border-slate-300 dark:border-slate-600 focus:bg-white dark:focus:bg-slate-900 text-sm" required>
                                                    </div>
                                                </div>
                                                <div>
                                                    <label class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Harga Jual / Price</label>
                                                    <div class="relative mt-1">
                                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"><span class="text-slate-400 sm:text-sm">Rp</span></div>
                                                        <input type="hidden" name="selling_price" :value="unformatRupiah(sellingPrice)">
                                                        <input type="text" x-model="sellingPrice" @input="sellingPrice = formatRupiah($event.target.value)" class="input w-full pl-9 bg-slate-50 dark:bg-slate-800 border-slate-300 dark:border-slate-600 focus:bg-white dark:focus:bg-slate-900 text-sm font-semibold text-primary-600 dark:text-primary-400" required>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 gap-6 bg-slate-50 dark:bg-slate-800/50 p-4 sm:p-5 rounded-xl border border-slate-300 dark:border-slate-600">
                                                <!-- Kolom Kiri: Stok -->
                                                <div>
                                                    <div class="flex gap-8 sm:pt-2">
                                                        @foreach($stores as $store)
                                                            <div>
                                                                <div class="text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Stok {{ $store->name }}</div>
                                                                <div class="text-lg font-bold text-slate-800 dark:text-white">{{ $product->stockForStore($store->id) }}</div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>

                                                <!-- Kolom Kanan: Barang Titipan -->
                                                <div>
                                                    <label class="flex items-center gap-2 cursor-pointer sm:pt-2">
                                                        <input type="checkbox" name="is_consignment" value="1" x-model="isConsignment" class="w-5 h-5 rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                                                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Barang Titipan (Konsinyasi)</span>
                                                    </label>
                                                    
                                                    <div x-show="isConsignment" class="mt-3" x-cloak>
                                                        <label class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Vendor / BO</label>
                                                        <select name="bo_id" class="input w-full sm:w-3/4 lg:w-2/3 mt-1 bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-700 text-sm" :required="isConsignment">
                                                            <option value="">-- Pilih Vendor / BO --</option>
                                                            @foreach($bos as $bo)
                                                                <option value="{{ $bo->id }}" {{ $product->bo_id == $bo->id ? 'selected' : '' }}>{{ $bo->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Actions -->
                                            <div class="mt-6 pt-5 border-t border-slate-100 dark:border-slate-800 flex flex-wrap justify-between items-center gap-4">
                                                <button type="button" @click.stop="if(confirm('Hapus produk ini? Semua stok akan terhapus.')) { document.getElementById('delete-form-{{ $product->id }}').submit(); }" class="text-sm font-medium text-rose-500 hover:text-rose-600 flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                    Hapus Produk
                                                </button>
                                                <div class="flex items-center gap-3">
                                                    <span class="text-xs text-slate-400 mr-2 flex items-center gap-1"><svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> AUTO-SAVED ON SUBMIT</span>
                                                    <button type="button" @click="expandedId = null" class="btn bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <form id="delete-form-{{ $product->id }}" action="{{ route('products.destroy', $product) }}" method="POST" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-12 text-slate-500">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 mb-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                                    <p>Tidak ada data produk.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $products->links() }}
        </div>
    </div>

    {{-- Create Modal --}}
    <x-modal name="create-product" focusable maxWidth="2xl">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Tambah Produk Baru</h2>
                <button x-on:click="$dispatch('close')" class="text-gray-400 hover:text-gray-500">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8" x-data="{ imagePreview: null, isConsignment: {{ (old('form_mode') === 'create' && old('is_consignment')) ? 'true' : 'false' }} }">
                @csrf
                <input type="hidden" name="form_mode" value="create">

                <!-- Image Upload -->
                <div>
                    <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-4 border-b border-slate-100 dark:border-slate-800 pb-2">Foto Produk</h3>
                    <div class="flex items-center gap-6">
                        <label class="cursor-pointer group relative block w-32 h-32 flex-shrink-0 rounded-2xl border-2 border-dashed border-slate-300 dark:border-slate-700 hover:border-primary-500 dark:hover:border-primary-500 overflow-hidden bg-[#F2F2F7] dark:bg-[#3A3A3C] transition-colors">
                            <template x-if="imagePreview">
                                <img :src="imagePreview" class="w-full h-full object-cover">
                            </template>
                            <template x-if="!imagePreview">
                                <div class="absolute inset-0 flex flex-col items-center justify-center text-slate-400 group-hover:text-primary-500 transition-colors">
                                    <svg class="w-8 h-8 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    <span class="text-xs font-medium">Add image</span>
                                </div>
                            </template>
                            <input type="file" name="image" class="hidden" accept="image/*" @change="imagePreview = URL.createObjectURL($event.target.files[0])">
                        </label>
                        <div class="text-sm text-slate-500 dark:text-slate-400">
                            <p class="font-medium text-slate-700 dark:text-slate-300 mb-1">Unggah foto produk</p>
                            <p>Format yang didukung: JPG, PNG, GIF.</p>
                            <p>Maksimal ukuran file 2MB.</p>
                            @if(old('form_mode') === 'create') @error('image') <p class="text-rose-500 text-sm mt-2">{{ $message }}</p> @enderror @endif
                        </div>
                    </div>
                </div>

                <!-- Data Utama -->
                <div>
                    <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-4 border-b border-slate-100 dark:border-slate-800 pb-2">Data Utama</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="label">Nama Produk <span class="text-rose-500">*</span></label>
                            <input type="text" id="name" name="name" value="{{ old('form_mode') === 'create' ? old('name') : '' }}" class="input @error('name') border-rose-500 @enderror" required>
                            @if(old('form_mode') === 'create') @error('name') <p class="text-rose-500 text-sm mt-1">{{ $message }}</p> @enderror @endif
                        </div>

                        <div>
                            <label for="product_code" class="label">Kode Barang / Barcode <span class="text-rose-500">*</span></label>
                            <input type="text" id="product_code" name="product_code" value="{{ old('form_mode') === 'create' ? old('product_code') : '' }}" placeholder="Scan atau ketik kode..." class="input @error('product_code') border-rose-500 @enderror" required>
                            @if(old('form_mode') === 'create') @error('product_code') <p class="text-rose-500 text-sm mt-1">{{ $message }}</p> @enderror @endif
                        </div>

                        <div>
                            <label for="category_id" class="label">Kategori <span class="text-rose-500">*</span></label>
                            <select id="category_id" name="category_id" class="input @error('category_id') border-rose-500 @enderror" required>
                                <option value="">Pilih Kategori...</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ (old('form_mode') === 'create' && old('category_id') == $category->id) ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @if(old('form_mode') === 'create') @error('category_id') <p class="text-rose-500 text-sm mt-1">{{ $message }}</p> @enderror @endif
                        </div>

                        <div>
                            <label for="unit" class="label">Satuan <span class="text-rose-500">*</span></label>
                            <input type="text" id="unit" name="unit" value="{{ old('form_mode') === 'create' ? old('unit') : '' }}" placeholder="Contoh: sak, pcs, batang, m2" class="input @error('unit') border-rose-500 @enderror" required>
                            @if(old('form_mode') === 'create') @error('unit') <p class="text-rose-500 text-sm mt-1">{{ $message }}</p> @enderror @endif
                        </div>
                    </div>
                </div>

                <!-- Harga -->
                <div>
                    <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-4 border-b border-slate-100 dark:border-slate-800 pb-2">Pengaturan Harga</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="purchase_price" class="label">Harga Beli (Modal) <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-slate-500">Rp</span>
                                <input type="number" id="purchase_price" name="purchase_price" value="{{ old('form_mode') === 'create' ? old('purchase_price') : '' }}" class="input pl-10 @error('purchase_price') border-rose-500 @enderror" required min="0">
                            </div>
                            @if(old('form_mode') === 'create') @error('purchase_price') <p class="text-rose-500 text-sm mt-1">{{ $message }}</p> @enderror @endif
                        </div>

                        <div>
                            <label for="selling_price" class="label">Harga Jual <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-slate-500">Rp</span>
                                <input type="number" id="selling_price" name="selling_price" value="{{ old('form_mode') === 'create' ? old('selling_price') : '' }}" class="input pl-10 @error('selling_price') border-rose-500 @enderror" required min="0">
                            </div>
                            @if(old('form_mode') === 'create') @error('selling_price') <p class="text-rose-500 text-sm mt-1">{{ $message }}</p> @enderror @endif
                        </div>
                    </div>
                </div>

                <!-- Kepemilikan Barang -->
                <div>
                    <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-4 border-b border-slate-100 dark:border-slate-800 pb-2">Status Kepemilikan Barang</h3>
                    <div class="mb-4">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="is_consignment" value="1" x-model="isConsignment" class="w-5 h-5 text-primary-600 rounded border-slate-300 focus:ring-primary-500 dark:border-slate-600 dark:bg-slate-700">
                            <span class="text-slate-700 dark:text-slate-300 font-medium">Barang Konsinyasi (Titipan BO)</span>
                        </label>
                        <p class="text-sm text-slate-500 mt-1 ml-8">Centang jika barang ini adalah titipan dari BO.</p>
                    </div>

                    <div x-show="isConsignment" x-transition class="ml-8 mb-6 p-4 bg-slate-50 dark:bg-slate-800/50 rounded-lg border border-slate-200 dark:border-slate-700">
                        <label for="bo_id" class="label">Pilih BO / Supplier <span class="text-rose-500">*</span></label>
                        <select id="bo_id" name="bo_id" class="input @error('bo_id') border-rose-500 @enderror" :required="isConsignment">
                            <option value="">-- Pilih BO --</option>
                            @foreach($bos as $bo)
                                <option value="{{ $bo->id }}" {{ (old('form_mode') === 'create' && old('bo_id') == $bo->id) ? 'selected' : '' }}>
                                    {{ $bo->name }}
                                </option>
                            @endforeach
                        </select>
                        @if(old('form_mode') === 'create') @error('bo_id') <p class="text-rose-500 text-sm mt-1">{{ $message }}</p> @enderror @endif
                    </div>
                </div>

                <!-- Initial Stock -->
                <div class="bg-primary-50 dark:bg-primary-900/20 p-6 rounded-xl border border-primary-100 dark:border-primary-800/30">
                    <h3 class="text-lg font-semibold text-primary-900 dark:text-primary-300 mb-4 flex items-center gap-2">
                        Stok Awal
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="minimum_stock" class="label text-primary-900 dark:text-primary-300">Batas Stok Minimum (Global) <span class="text-rose-500">*</span></label>
                            <input type="number" id="minimum_stock" name="minimum_stock" value="{{ old('form_mode') === 'create' ? old('minimum_stock', 5) : 5 }}" class="input" required min="0">
                        </div>

                        @foreach($stores as $store)
                            <div>
                                <label for="initial_stock_{{ $store->id }}" class="label text-primary-900 dark:text-primary-300">Stok Awal di {{ $store->name }}</label>
                                <input type="number" id="initial_stock_{{ $store->id }}" name="initial_stock[{{ $store->id }}]" value="{{ old('form_mode') === 'create' ? old('initial_stock.'.$store->id, 0) : 0 }}" class="input" min="0">
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" x-on:click="$dispatch('close')" class="btn bg-slate-100 text-slate-700 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </x-modal>

    {{-- Error Handling Script --}}
    @if ($errors->any() && old('form_mode') === 'create')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                setTimeout(() => {
                    window.dispatchEvent(new CustomEvent('open-modal', { detail: 'create-product' }));
                }, 100);
            });
        </script>
    @endif
</x-app-layout>
