<x-app-layout>
    <x-slot name="header">Buat Mutasi Stok</x-slot>
    <x-slot name="title">Transfer Antar Toko</x-slot>

    <div x-data="transferApp()" class="max-w-5xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <a href="{{ route('transfers.index') }}" class="btn bg-white text-slate-700 hover:bg-slate-50 dark:bg-slate-800 dark:text-slate-300 shadow-sm border border-slate-200 dark:border-slate-700">
                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Batal
            </a>
            <h2 class="text-section-title text-gray-900 dark:text-white">Form Transfer Stok</h2>
        </div>

        <form id="transfer-form" action="{{ route('transfers.store') }}" method="POST" @submit.prevent="submitTransfer">
            @csrf
            
            <!-- Info Dasar -->
            <div class="card p-6 mb-6">
                <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-4 border-b border-slate-100 dark:border-slate-800 pb-2">Informasi Transfer</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                    <div>
                        <label class="label">Toko Asal (Pengirim) <span class="text-rose-500">*</span></label>
                        <select name="from_store_id" x-model="fromStore" class="input" required @change="onStoreChange">
                            <option value="">Pilih Toko Asal...</option>
                            @foreach($stores as $store)
                                @if(auth()->user()->isSuperAdmin() || auth()->user()->store_id == $store->id)
                                    <option value="{{ $store->id }}">{{ $store->name }}</option>
                                @endif
                            @endforeach
                        </select>
                        <p class="text-xs text-slate-500 mt-1">Stok akan dikurangi dari toko ini.</p>
                    </div>
                    
                    <div>
                        <label class="label">Toko Tujuan (Penerima) <span class="text-rose-500">*</span></label>
                        <select name="to_store_id" x-model="toStore" class="input" required>
                            <option value="">Pilih Toko Tujuan...</option>
                            @foreach($stores as $store)
                                <option value="{{ $store->id }}">{{ $store->name }}</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-slate-500 mt-1">Stok akan ditambahkan ke toko ini.</p>
                    </div>
                </div>

                <div>
                    <label class="label">Keterangan Tambahan / Alasan Transfer</label>
                    <textarea name="notes" x-model="notes" rows="2" class="input" placeholder="Misal: Permintaan dari cabang pusat untuk memenuhi pesanan pelanggan..."></textarea>
                </div>
            </div>

            <!-- Pilih Barang -->
            <div class="card p-6 mb-6" x-show="fromStore">
                <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-4 border-b border-slate-100 dark:border-slate-800 pb-2">Pilih Barang yang Ditransfer</h3>
                
                <div class="flex gap-4 mb-6">
                    <div class="flex-1 relative">
                        <select x-model="selectedProduct" class="input pl-10">
                            <option value="">Pilih produk untuk ditambahkan...</option>
                            <template x-for="product in availableProducts" :key="product.id">
                                <option :value="product.id" x-text="`${product.product_code} - ${product.name} (Stok: ${product.stock})`"></option>
                            </template>
                        </select>
                        <svg class="w-5 h-5 text-slate-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <button type="button" @click="addItem" class="btn btn-primary shrink-0" :disabled="!selectedProduct">
                        Tambahkan
                    </button>
                </div>

                <!-- Item List -->
                <div x-show="items.length > 0">
                    <table class="table w-full mb-4">
                        <thead class="bg-slate-50 dark:bg-slate-800">
                            <tr>
                                <th class="py-2 px-4 text-left text-xs text-slate-500 uppercase">Produk</th>
                                <th class="py-2 px-4 text-center text-xs text-slate-500 uppercase w-32">Stok Saat Ini</th>
                                <th class="py-2 px-4 text-center text-xs text-slate-500 uppercase w-40">Qty Transfer</th>
                                <th class="py-2 px-4 text-right text-xs text-slate-500 uppercase w-16"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            <template x-for="(item, index) in items" :key="item.product_id">
                                <tr>
                                    <td class="py-3 px-4">
                                        <p class="font-medium text-slate-800 dark:text-white" x-text="item.name"></p>
                                        <p class="text-xs text-slate-500" x-text="item.code"></p>
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <span class="font-bold text-slate-700 dark:text-slate-300" x-text="item.max_stock"></span>
                                        <span class="text-xs" x-text="item.unit"></span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="flex items-center gap-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg p-1">
                                            <input type="number" x-model.number="item.qty" @change="validateQty(index)" class="w-full text-center text-sm font-bold bg-transparent border-none focus:ring-0 p-1" min="1" :max="item.max_stock" required>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 text-right">
                                        <button type="button" @click="removeItem(index)" class="text-rose-500 hover:text-rose-600 p-1 bg-rose-50 hover:bg-rose-100 dark:bg-rose-900/30 dark:hover:bg-rose-900/50 rounded-md">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <div x-show="items.length === 0" class="text-center py-8 text-slate-500 border-2 border-dashed border-slate-200 dark:border-slate-700 rounded-xl">
                    Pilih produk di atas untuk ditambahkan ke daftar transfer.
                </div>
            </div>

            <!-- Submit -->
            <div class="flex justify-end gap-3" x-show="fromStore">
                <button type="submit" class="btn btn-primary shadow-lg py-3 px-6 text-lg" :disabled="items.length === 0 || fromStore === toStore || !toStore || processing">
                    <span x-show="!processing">Proses Transfer Stok</span>
                    <span x-show="processing">Memproses...</span>
                </button>
            </div>
            
            <div x-show="fromStore === toStore && fromStore !== ''" class="mt-4 p-3 bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 rounded-lg border border-rose-100 dark:border-rose-900/50 text-sm text-center">
                Toko asal dan toko tujuan tidak boleh sama.
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        // Inject inventory data grouped by store
        const inventories = @json($inventoriesByStore);
        
        document.addEventListener('alpine:init', () => {
            Alpine.data('transferApp', () => ({
                fromStore: '{{ auth()->user()->isSuperAdmin() ? "" : auth()->user()->store_id }}',
                toStore: '',
                notes: '',
                
                selectedProduct: '',
                availableProducts: [],
                items: [],
                processing: false,

                init() {
                    if (this.fromStore) {
                        this.loadProducts();
                    }
                },

                onStoreChange() {
                    this.items = [];
                    this.selectedProduct = '';
                    this.loadProducts();
                },

                loadProducts() {
                    if (!this.fromStore || !inventories[this.fromStore]) {
                        this.availableProducts = [];
                        return;
                    }
                    
                    this.availableProducts = inventories[this.fromStore]
                        .filter(inv => inv.stock > 0)
                        .map(inv => ({
                            id: inv.product_id,
                            code: inv.product.product_code,
                            name: inv.product.name,
                            unit: inv.product.unit,
                            stock: inv.stock
                        }));
                },

                addItem() {
                    if (!this.selectedProduct) return;
                    
                    const product = this.availableProducts.find(p => p.id == this.selectedProduct);
                    if (!product) return;

                    // Check if already in items
                    const exists = this.items.find(i => i.product_id == product.id);
                    if (exists) {
                        if (exists.qty < exists.max_stock) {
                            exists.qty++;
                        }
                    } else {
                        this.items.push({
                            product_id: product.id,
                            code: product.code,
                            name: product.name,
                            unit: product.unit,
                            max_stock: product.stock,
                            qty: 1
                        });
                    }
                    
                    this.selectedProduct = '';
                },

                removeItem(index) {
                    this.items.splice(index, 1);
                },

                validateQty(index) {
                    const item = this.items[index];
                    if (item.qty > item.max_stock) item.qty = item.max_stock;
                    if (item.qty < 1) item.qty = 1;
                },

                async submitTransfer() {
                    if (this.fromStore === this.toStore) return;
                    if (this.items.length === 0) return;
                    
                    this.processing = true;

                    try {
                        const response = await fetch('{{ route("transfers.store") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                from_store_id: this.fromStore,
                                to_store_id: this.toStore,
                                notes: this.notes,
                                items: this.items.map(i => ({
                                    product_id: i.product_id,
                                    qty: i.qty
                                }))
                            })
                        });

                        const data = await response.json();

                        if (response.ok && data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Transfer Berhasil',
                                text: 'Transfer stok berhasil dibuat dengan status PENDING.',
                                showConfirmButton: false,
                                showCloseButton: true,
                                timer: 2000,
                                timerProgressBar: true
                            }).then(() => {
                                window.location.href = data.redirect;
                            });
                        } else {
                            throw new Error(data.message || 'Terjadi kesalahan');
                        }
                    } catch (error) {
                        Swal.fire({ icon: 'error', title: 'Gagal Memproses Transfer', text: error.message || 'Terjadi kesalahan saat memproses permintaan.', showCloseButton: true, confirmButtonText: 'Coba Lagi' });
                        this.processing = false;
                    }
                }
            }));
        });
    </script>
    @endpush
</x-app-layout>
