<x-app-layout>
    <x-slot name="header">Point of Sale (POS)</x-slot>
    <x-slot name="title">Point of Sale (POS)</x-slot>

    <!-- Note: POS interface is usually full screen, but we'll keep it within the layout for consistency -->
    <div x-data="posApp()" class="grid grid-cols-1 lg:grid-cols-3 gap-6 h-[calc(100vh-140px)] -mt-2">
        
        <!-- Left Side: Product Catalog -->
        <div class="lg:col-span-2 flex flex-col h-full bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
            <!-- Search & Filter -->
            <div class="p-4 border-b border-slate-100 dark:border-slate-800">
                <div class="relative">
                    <input type="text" x-model="searchQuery" @input="filterProducts" @keydown.enter.prevent="handleBarcodeSubmit" placeholder="Cari barang atau scan barcode..." class="w-full pl-11 pr-4 py-2.5 rounded-md bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 focus:ring-2 focus:ring-primary-500 text-sm">
                    <svg class="w-6 h-6 text-slate-400 absolute left-3 top-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                
                <!-- Categories -->
                <div 
                    x-data="{ isDown: false, isDragged: false, startX: 0, scrollLeft: 0 }"
                    x-on:mousedown="isDown = true; isDragged = false; startX = $event.pageX - $el.offsetLeft; scrollLeft = $el.scrollLeft; $el.classList.add('cursor-grabbing'); $el.classList.remove('cursor-grab')"
                    x-on:mouseleave="isDown = false; $el.classList.remove('cursor-grabbing'); $el.classList.add('cursor-grab')"
                    x-on:mouseup="isDown = false; $el.classList.remove('cursor-grabbing'); $el.classList.add('cursor-grab')"
                    x-on:mousemove="if(!isDown) return; $event.preventDefault(); isDragged = true; const x = $event.pageX - $el.offsetLeft; const walk = (x - startX) * 2; $el.scrollLeft = scrollLeft - walk;"
                    class="flex gap-2 mt-4 overflow-x-auto pb-2 scrollbar-hide cursor-grab select-none"
                >
                    <button @click.prevent="if(!isDragged) setCategory('')" :class="{'bg-primary-600 text-white border-primary-600': activeCategory === '', 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700 dark:hover:bg-slate-700': activeCategory !== ''}" class="px-4 py-2 rounded-md text-sm font-medium border whitespace-nowrap transition-colors">
                        Semua Kategori
                    </button>
                    @php
                        $uniqueCategories = $products->pluck('category.name')->unique();
                    @endphp
                    @foreach($uniqueCategories as $cat)
                    <button @click.prevent="if(!isDragged) setCategory('{{ $cat }}')" :class="{'bg-primary-600 text-white border-primary-600': activeCategory === '{{ $cat }}', 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700 dark:hover:bg-slate-700': activeCategory !== '{{ $cat }}'}" class="px-4 py-2 rounded-md text-sm font-medium border whitespace-nowrap transition-colors">
                        {{ $cat }}
                    </button>
                    @endforeach
                </div>
            </div>

            <!-- Products Grid -->
            <div class="flex-1 overflow-y-auto p-4 bg-slate-50/50 dark:bg-slate-900/50">
                <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-4">
                    <template x-for="product in filteredProducts" :key="product.id">
                        <div @click="addToCart(product)" class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg p-4 cursor-pointer hover:shadow-md hover:border-slate-300 dark:hover:border-slate-600 transition-all group relative overflow-hidden flex flex-col">
                            <div class="absolute top-0 right-0 bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300 border-l border-b border-slate-200 dark:border-slate-600 text-xs font-semibold px-2 py-1 rounded-bl-md transition-colors">
                                Stok: <span x-text="product.current_stock"></span>
                            </div>
                            <div x-show="product.is_consignment" class="absolute top-8 right-0 bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-400 border-l border-b border-amber-200 dark:border-amber-700 text-[10px] font-bold px-2 py-1 rounded-bl-md shadow-sm transition-colors">
                                Titipan BO
                            </div>
                            
                            <div class="w-10 h-10 bg-slate-50 dark:bg-slate-700 rounded-md border border-slate-100 dark:border-slate-600 flex items-center justify-center mb-3 text-slate-400 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                            </div>
                            
                            <h3 class="font-medium text-sm text-slate-800 dark:text-white line-clamp-2 mb-1 transition-colors" x-text="product.name"></h3>
                            <p class="text-xs text-slate-500 mb-2" x-text="product.product_code"></p>
                            
                            <div class="mt-auto pt-3 flex justify-between items-end">
                                <p class="font-semibold text-slate-900 dark:text-white transition-colors" x-text="formatRupiah(product.selling_price)"></p>
                                <span class="text-[10px] text-slate-400" x-text="'/' + product.unit"></span>
                            </div>
                        </div>
                    </template>
                    
                    <div x-show="filteredProducts.length === 0" class="col-span-full py-12 text-center text-slate-500">
                        Produk tidak ditemukan.
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Cart -->
        <div class="lg:col-span-1 flex flex-col h-[500px] lg:h-full bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
            <!-- Customer Select -->
            <div class="p-4 bg-slate-50 dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700">
                <div class="flex items-center justify-between mb-2">
                    <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Pelanggan</label>
                    <a href="{{ route('customers.index') }}" target="_blank" class="text-[12px] text-primary-600 hover:text-primary-700 font-medium">Kelola Pelanggan</a>
                </div>
                <select x-model="customerId" class="w-full rounded-md border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-sm focus:ring-primary-500 focus:border-primary-500">
                    <option value="">-- Pelanggan Umum --</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->phone ?? 'Tidak ada no' }})</option>
                    @endforeach
                </select>
            </div>

            <div class="flex-1 overflow-y-auto p-3">
                <template x-for="(item, index) in cart" :key="index">
                    <div class="p-3 mb-2 bg-white dark:bg-slate-800 rounded-md flex gap-3 border border-slate-200 dark:border-slate-700 hover:border-slate-300 dark:hover:border-slate-600">
                        <div class="flex-1">
                            <h4 class="font-medium text-sm text-slate-800 dark:text-white line-clamp-1" x-text="item.name"></h4>
                            <div class="flex items-center justify-between mt-2">
                                <span class="font-semibold text-slate-900 dark:text-white" x-text="formatRupiah(item.price)"></span>
                                
                                <div class="flex items-center gap-1 bg-slate-50 dark:bg-slate-900 rounded-md p-1 border border-slate-200 dark:border-slate-600">
                                    <button @click="updateQty(index, -1)" class="w-6 h-6 flex items-center justify-center rounded-md hover:bg-slate-100 dark:hover:bg-slate-600 text-slate-500">-</button>
                                    <input type="number" x-model.number="item.qty" @change="validateQty(index)" class="w-10 h-6 text-center text-sm font-semibold bg-transparent border-none focus:ring-0 p-0 m-0 dark:text-white" min="1">
                                    <button @click="updateQty(index, 1)" class="w-6 h-6 flex items-center justify-center rounded-md hover:bg-slate-100 dark:hover:bg-slate-600 text-slate-500">+</button>
                                </div>
                            </div>
                        </div>
                        <button @click="removeFromCart(index)" class="self-start p-1.5 text-rose-400 hover:bg-rose-100 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        </button>
                    </div>
                </template>
                
                <div x-show="cart.length === 0" class="h-full flex flex-col items-center justify-center text-slate-400 space-y-3">
                    <svg class="w-16 h-16 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                    <p>Keranjang kosong</p>
                </div>
            </div>

            <!-- Totals & Payment -->
            <div class="p-3 bg-slate-50 dark:bg-slate-800 border-t border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200">
                <div class="flex justify-between items-center mb-1.5">
                    <span class="text-sm text-slate-500 dark:text-slate-400">Total Item</span>
                    <span class="font-semibold" x-text="totalItems"></span>
                </div>
                <div class="flex justify-between items-center mb-3 pb-3 border-b border-slate-200 dark:border-slate-700">
                    <span class="text-slate-600 dark:text-slate-300 font-medium">Total Tagihan</span>
                    <span class="text-xl font-bold text-slate-900 dark:text-white" x-text="formatRupiah(totalAmount)"></span>
                </div>
                
                <div class="grid grid-cols-2 gap-3 mb-3">
                    <div>
                        <label class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1 block">Metode</label>
                        <select x-model="paymentMethod" class="w-full px-2.5 py-2 rounded-md bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-600 text-sm font-semibold focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                            <option value="Tunai">Tunai</option>
                            <option value="QRIS">QRIS</option>
                            <option value="Transfer/M-Banking">Transfer</option>
                            <option value="Deposit">Potong Deposit</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1 block">Jml Bayar</label>
                        <div class="relative">
                            <span class="absolute left-2.5 top-2 text-slate-500 dark:text-slate-400 font-medium text-sm">Rp</span>
                            <input type="text" x-model="formattedPaidAmount" class="w-full pl-8 pr-2 py-2 rounded-md bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-600 text-sm font-semibold focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        </div>
                    </div>
                </div>
                
                <p x-show="paymentMethod === 'QRIS'" class="text-[10px] text-slate-500 mb-2 italic -mt-1">*Biaya QRIS Rp 500 ditanggung toko</p>

                <div class="flex justify-between items-center mb-3">
                    <span class="text-sm font-medium text-slate-600 dark:text-slate-300" x-text="kembalian >= 0 ? 'Kembalian' : 'Sisa Piutang'"></span>
                    <span class="text-lg font-bold" :class="kembalian >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400'" x-text="formatRupiah(Math.abs(kembalian))"></span>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <button @click="clearCart" class="py-2.5 rounded-md font-medium bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 dark:bg-slate-700 dark:border-slate-600 dark:text-slate-300 dark:hover:bg-slate-600 transition-colors">
                        Batal
                    </button>
                    <button @click="processPayment" :disabled="cart.length === 0 || processing" class="py-2.5 rounded-md font-medium bg-primary-600 hover:bg-primary-700 transition-colors text-white disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center">
                        <span x-show="!processing">Proses Bayar</span>
                        <svg x-show="processing" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('posApp', () => ({
                products: @json($products),
                filteredProducts: [],
                searchQuery: '',
                activeCategory: '',
                
                cart: [],
                customerId: '',
                paidAmount: 0,
                formattedPaidAmount: '0',
                paymentMethod: 'Tunai',
                processing: false,

                init() {
                    this.filteredProducts = this.products;
                    // Auto calculate paidAmount when total changes (default to exact amount)
                    this.$watch('totalAmount', value => {
                        this.paidAmount = value;
                        this.formattedPaidAmount = this.formatRupiah(value);
                    });

                    // Format payment input as user types
                    this.$watch('formattedPaidAmount', value => {
                        if (value === '' || value === null) {
                            this.paidAmount = 0;
                            return;
                        }
                        let num = parseInt(value.toString().replace(/[^0-9]/g, '')) || 0;
                        let formatted = num === 0 ? '0' : this.formatRupiah(num);
                        if (value !== formatted) {
                            this.formattedPaidAmount = formatted;
                        }
                        this.paidAmount = num;
                    });
                },

                filterProducts() {
                    let result = this.products;
                    
                    if (this.searchQuery) {
                        const q = this.searchQuery.toLowerCase();
                        result = result.filter(p => 
                            p.name.toLowerCase().includes(q) || 
                            p.product_code.toLowerCase().includes(q)
                        );
                    }
                    
                    if (this.activeCategory) {
                        result = result.filter(p => p.category.name === this.activeCategory);
                    }
                    
                    this.filteredProducts = result;
                },

                setCategory(cat) {
                    this.activeCategory = cat;
                    this.filterProducts();
                },

                handleBarcodeSubmit() {
                    // Jika setelah discan/dicari hanya ada 1 barang spesifik ATAU kode barangnya sama persis
                    const matchedProduct = this.filteredProducts.find(p => p.product_code.toLowerCase() === this.searchQuery.toLowerCase());
                    
                    if (matchedProduct) {
                        this.addToCart(matchedProduct);
                        this.searchQuery = '';
                        this.filterProducts();
                    } else if (this.filteredProducts.length === 1) {
                        this.addToCart(this.filteredProducts[0]);
                        this.searchQuery = '';
                        this.filterProducts();
                    }
                },

                addToCart(product) {
                    if (product.current_stock <= 0) {
                        Swal.fire({ icon: 'error', title: 'Stok Habis', text: 'Produk ini tidak tersedia di toko Anda.', timer: 2500, showConfirmButton: false, showCloseButton: true, timerProgressBar: true });
                        return;
                    }

                    const index = this.cart.findIndex(item => item.product_id === product.id);
                    
                    if (index > -1) {
                        if (this.cart[index].qty >= product.current_stock) {
                            Swal.fire({ icon: 'warning', title: 'Stok Terbatas', text: 'Stok tidak mencukupi untuk menambah qty.', timer: 2500, showConfirmButton: false, showCloseButton: true, timerProgressBar: true });
                            return;
                        }
                        this.cart[index].qty++;
                    } else {
                        this.cart.push({
                            product_id: product.id,
                            name: product.name,
                            price: product.selling_price,
                            qty: 1,
                            max_qty: product.current_stock
                        });
                    }
                    
                    // Small haptic/visual feedback
                    if (window.navigator.vibrate) window.navigator.vibrate(50);
                },

                updateQty(index, change) {
                    const item = this.cart[index];
                    const newQty = item.qty + change;
                    
                    if (newQty > 0 && newQty <= item.max_qty) {
                        item.qty = newQty;
                    } else if (newQty > item.max_qty) {
                        Swal.fire({ icon: 'warning', title: 'Stok Terbatas', text: `Hanya ada ${item.max_qty} item tersedia.`, timer: 2500, showConfirmButton: false, showCloseButton: true, timerProgressBar: true });
                    }
                },

                validateQty(index) {
                    const item = this.cart[index];
                    if (item.qty > item.max_qty) item.qty = item.max_qty;
                    if (item.qty < 1) item.qty = 1;
                },

                removeFromCart(index) {
                    this.cart.splice(index, 1);
                },

                clearCart() {
                    if (this.cart.length > 0) {
                        Swal.fire({
                            title: 'Batalkan Transaksi?',
                            text: 'Semua item di keranjang akan dihapus.',
                            icon: 'warning',
                            showCancelButton: true,
                            showCloseButton: true,
                            confirmButtonText: 'Ya, Batalkan',
                            cancelButtonText: 'Kembali',
                            customClass: { popup: 'swal-danger-confirm' },
                            reverseButtons: true
                        }).then((result) => {
                            if (result.isConfirmed) {
                                this.cart = [];
                                this.customerId = '';
                            }
                        });
                    }
                },

                get totalItems() {
                    return this.cart.reduce((total, item) => total + parseInt(item.qty), 0);
                },

                get totalAmount() {
                    return this.cart.reduce((total, item) => total + (item.price * item.qty), 0);
                },

                get kembalian() {
                    return (this.paidAmount || 0) - this.totalAmount;
                },

                formatRupiah(amount) {
                    return new Intl.NumberFormat('id-ID', { minimumFractionDigits: 0 }).format(amount);
                },

                async processPayment() {
                    if (this.cart.length === 0) return;
                    
                    const isCredit = this.kembalian < 0;
                    
                    if (isCredit && !this.customerId) {
                        Swal.fire({ icon: 'error', title: 'Pelanggan Wajib Diisi', text: 'Transaksi yang belum lunas penuh (Piutang) mewajibkan Anda memilih Pelanggan.', showCloseButton: true });
                        return;
                    }

                    if (isCredit) {
                        const confirm = await Swal.fire({
                            title: 'Catat Sebagai Piutang?',
                            html: `Pembayaran yang diterima lebih kecil dari total tagihan.<br><br>Sisa pembayaran sebesar <b>Rp ${this.formatRupiah(Math.abs(this.kembalian))}</b> akan otomatis dicatat sebagai piutang pelanggan.`,
                            icon: 'warning',
                            showCancelButton: true,
                            showCloseButton: true,
                            confirmButtonText: 'Catat Piutang',
                            cancelButtonText: 'Kembali',
                            customClass: { popup: 'swal-warning-confirm' },
                            reverseButtons: true
                        });
                        
                        if (!confirm.isConfirmed) return;
                    }

                    this.processing = true;

                    try {
                        const response = await fetch('{{ route("pos.store") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                store_id: {{ $storeId }},
                                customer_id: this.customerId || null,
                                paid_amount: this.paidAmount || 0,
                                payment_method: this.paymentMethod,
                                items: this.cart.map(item => ({
                                    product_id: item.product_id,
                                    qty: item.qty,
                                    price: item.price
                                }))
                            })
                        });

                        const data = await response.json();

                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Transaksi Berhasil',
                                text: isCredit ? 'Data berhasil disimpan dengan sisa piutang.' : 'Pembayaran telah lunas dan tercatat.',
                                showConfirmButton: false,
                                showCloseButton: true,
                                timer: 2000,
                                timerProgressBar: true
                            }).then(() => {
                                // Open receipt in new window for printing
                                window.open(data.redirect, '_blank', 'width=400,height=600');
                                // Reset cart
                                this.cart = [];
                                this.customerId = '';
                                // We should reload the page to get updated stock
                                window.location.reload();
                            });
                        } else {
                            throw new Error(data.message || 'Terjadi kesalahan sistem');
                        }
                    } catch (error) {
                        Swal.fire({ icon: 'error', title: 'Gagal Menyimpan Data', text: error.message || 'Terjadi kesalahan saat memproses permintaan. Silakan coba kembali.', showCloseButton: true, confirmButtonText: 'Coba Lagi' });
                        this.processing = false;
                    }
                }
            }));
        });
    </script>
    @endpush
</x-app-layout>
