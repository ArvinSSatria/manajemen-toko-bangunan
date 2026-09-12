<x-app-layout>
    <x-slot name="header">Kelola Nota BO</x-slot>
    <x-slot name="title">BO: {{ $bo->name }}</x-slot>

    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('bo.index') }}" class="btn bg-white text-slate-700 hover:bg-slate-50 dark:bg-slate-800 dark:text-slate-300 shadow-sm border border-slate-200 dark:border-slate-700">
                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Kembali
            </a>
            <h2 class="text-section-title text-gray-900 dark:text-white">{{ $bo->name }}</h2>
        </div>
        
        <button onclick="document.getElementById('modal-add-order').classList.remove('hidden')" class="btn btn-primary">
            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Tambah Nota Baru
        </button>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="card p-6 border-l-4 border-slate-500">
            <p class="text-sm font-medium text-slate-500">Total Transaksi</p>
            <h3 class="text-2xl font-bold text-slate-800 dark:text-white mt-1">{{ $bo->orders->count() }} Nota</h3>
        </div>
        <div class="card p-6 border-l-4 border-primary-500">
            <p class="text-sm font-medium text-slate-500">Total Nominal Pembelian</p>
            <h3 class="text-2xl font-bold text-slate-800 dark:text-white mt-1">Rp {{ number_format($bo->orders->sum('total'), 0, ',', '.') }}</h3>
        </div>
        <div class="card p-6 border-l-4 border-rose-500">
            <p class="text-sm font-medium text-slate-500">Total Hutang Berjalan</p>
            <h3 class="text-2xl font-bold text-rose-500 mt-1">Rp {{ number_format($bo->total_debt, 0, ',', '.') }}</h3>
        </div>
    </div>

    <!-- Order List -->
    <div class="card p-6">
        <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-6 border-b border-slate-100 dark:border-slate-800 pb-2">Riwayat Nota / Pesanan</h3>
        
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Toko / Cabang</th>
                        <th>Tanggal Nota</th>
                        <th>No. Nota</th>
                        <th>Keterangan Barang</th>
                        <th class="text-right">Total Tagihan</th>
                        <th class="text-right">Sisa Hutang</th>
                        <th class="text-center">Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bo->orders()->latest()->get() as $order)
                        <tr class="{{ $order->remaining_balance > 0 ? 'bg-amber-50/30 dark:bg-amber-900/10' : '' }}">
                            <td><span class="badge-primary">{{ $order->store->name }}</span></td>
                            <td>{{ $order->date->format('d M Y') }}</td>
                            <td class="font-mono text-sm text-slate-600 dark:text-slate-400">{{ $order->invoice_number }}</td>
                            <td class="max-w-xs truncate" title="{{ $order->description }}">{{ $order->description }}</td>
                            <td class="text-right font-medium text-slate-800 dark:text-white">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                            <td class="text-right font-bold text-rose-500">
                                {{ $order->remaining_balance > 0 ? 'Rp ' . number_format($order->remaining_balance, 0, ',', '.') : '-' }}
                            </td>
                            <td class="text-center">
                                <span class="badge-{{ $order->status->color() }}">{{ $order->status->label() }}</span>
                            </td>
                            <td>
                                @if($order->remaining_balance > 0)
                                    <button onclick="openPaymentModal({{ $order->id }}, '{{ $order->invoice_number }}', {{ $order->remaining_balance }})" class="btn bg-rose-100 text-rose-600 hover:bg-rose-200 dark:bg-rose-900/30 dark:text-rose-400 py-1.5 px-3 text-xs w-full justify-center">
                                        Bayar Hutang
                                    </button>
                                @else
                                    <span class="inline-flex items-center justify-center w-full py-1.5 text-xs font-medium text-emerald-600 dark:text-emerald-400">
                                        <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                        Lunas
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-8 text-slate-500">
                                Belum ada riwayat nota untuk BO ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Add Order -->
    <div id="modal-add-order" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div class="relative inline-block w-full max-w-lg p-6 overflow-hidden text-left align-middle bg-white dark:bg-slate-800 rounded-2xl shadow-xl transition-all sm:my-8">
                <div class="flex justify-between items-center border-b border-slate-100 dark:border-slate-700 pb-4 mb-4">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white">Tambah Nota BO Baru</h3>
                    <button onclick="document.getElementById('modal-add-order').classList.add('hidden')" class="text-slate-400 hover:text-slate-500">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <form action="{{ route('bo.orders.store', $bo) }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <div>
                        <label for="store_id" class="label">Untuk Toko / Cabang <span class="text-rose-500">*</span></label>
                        <select id="store_id" name="store_id" class="input" required {{ !auth()->user()->isSuperAdmin() ? 'readonly pointer-events-none' : '' }}>
                            @foreach(\App\Models\Store::where('is_active', true)->get() as $store)
                                <option value="{{ $store->id }}" {{ auth()->user()->store_id == $store->id ? 'selected' : '' }}>
                                    {{ $store->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="invoice_number" class="label">Nomor Nota <span class="text-rose-500">*</span></label>
                            <input type="text" id="invoice_number" name="invoice_number" class="input" required>
                        </div>
                        <div>
                            <label for="order_date" class="label">Tanggal Nota <span class="text-rose-500">*</span></label>
                            <input type="date" id="order_date" name="order_date" value="{{ date('Y-m-d') }}" class="input" required>
                        </div>
                    </div>

                    <div>
                        <label for="description" class="label">Keterangan Barang <span class="text-rose-500">*</span></label>
                        <textarea id="description" name="description" rows="2" class="input" placeholder="Misal: 100 Sak Semen, Besi Beton..." required></textarea>
                    </div>

                    <div>
                        <label for="total_amount" class="label">Total Tagihan (Rp) <span class="text-rose-500">*</span></label>
                        <input type="number" id="total_amount" name="total_amount" class="input text-lg font-bold" required min="1">
                    </div>

                    <div>
                        <label for="paid_amount" class="label">Telah Dibayar (Rp) - <span class="text-slate-500 font-normal">Kosongkan jika berhutang penuh</span></label>
                        <input type="number" id="paid_amount" name="paid_amount" class="input" min="0" value="0">
                        <p class="text-xs text-slate-500 mt-1">Pembayaran otomatis akan tercatat sebagai Pengeluaran.</p>
                    </div>

                    <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-700">
                        <button type="button" onclick="document.getElementById('modal-add-order').classList.add('hidden')" class="btn bg-slate-100 text-slate-700 hover:bg-slate-200">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Nota</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Pay Order -->
    <div id="modal-pay-order" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div class="relative inline-block w-full max-w-md p-6 overflow-hidden text-left align-middle bg-white dark:bg-slate-800 rounded-2xl shadow-xl transition-all sm:my-8">
                <div class="flex justify-between items-center border-b border-slate-100 dark:border-slate-700 pb-4 mb-4">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white">Bayar Hutang Nota</h3>
                    <button onclick="document.getElementById('modal-pay-order').classList.add('hidden')" class="text-slate-400 hover:text-slate-500">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <div class="mb-6 p-4 bg-rose-50 dark:bg-rose-900/20 rounded-xl border border-rose-100 dark:border-rose-900/50">
                    <p class="text-sm text-slate-500 mb-1">Nota: <span id="pay-invoice-no" class="font-bold text-slate-800 dark:text-white"></span></p>
                    <p class="text-sm text-slate-500">Sisa Hutang: <span id="pay-remaining-text" class="font-bold text-rose-600 dark:text-rose-400 text-xl"></span></p>
                </div>

                <form id="form-pay-order" method="POST" class="space-y-4">
                    @csrf
                    
                    <div>
                        <label for="pay_amount" class="label">Nominal Pembayaran (Rp) <span class="text-rose-500">*</span></label>
                        <input type="number" id="pay_amount" name="amount" class="input text-lg font-bold" required min="1">
                        <p class="text-xs text-slate-500 mt-1">Pembayaran ini akan otomatis tercatat sebagai Pengeluaran (Expense).</p>
                    </div>

                    <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-700">
                        <button type="button" onclick="document.getElementById('modal-pay-order').classList.add('hidden')" class="btn bg-slate-100 text-slate-700 hover:bg-slate-200">Batal</button>
                        <button type="submit" class="btn bg-rose-500 text-white hover:bg-rose-600">Proses Pembayaran</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function openPaymentModal(orderId, invoiceNo, remaining) {
            document.getElementById('pay-invoice-no').innerText = invoiceNo;
            document.getElementById('pay-remaining-text').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(remaining);
            
            const amountInput = document.getElementById('pay_amount');
            amountInput.max = remaining;
            amountInput.value = remaining;
            
            const form = document.getElementById('form-pay-order');
            // Assuming route bo.orders.pay
            form.action = `/bo-orders/${orderId}/pay`;
            
            document.getElementById('modal-pay-order').classList.remove('hidden');
        }
    </script>
    @endpush
</x-app-layout>
