<x-app-layout>
    <x-slot name="header">Detail Transfer</x-slot>
    <x-slot name="title">Mutasi Stok #TRF-{{ str_pad($transfer->id, 5, '0', STR_PAD_LEFT) }}</x-slot>

    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('transfers.index') }}" class="btn-secondary">
            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            Kembali
        </a>

        @if($transfer->status->value === 'PENDING')
        <div class="flex items-center gap-3">
            <form action="{{ route('transfers.cancel', $transfer) }}" method="POST" onsubmit="return confirm('Batalkan transfer ini? Stok akan dikembalikan ke toko asal.');">
                @csrf
                <button type="submit" class="btn bg-white text-rose-600 border border-rose-200 hover:bg-rose-50 hover:border-rose-300 dark:bg-transparent dark:border-rose-900/50 dark:hover:bg-rose-900/20">
                    <svg class="w-5 h-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    Tolak / Batalkan
                </button>
            </form>

            <form action="{{ route('transfers.complete', $transfer) }}" method="POST" onsubmit="return confirm('Selesaikan transfer ini? Stok di toko tujuan akan bertambah sesuai dengan jumlah yang ditransfer.');">
                @csrf
                <button type="submit" class="btn-primary bg-emerald-600 hover:bg-emerald-700 dark:bg-emerald-600 dark:hover:bg-emerald-500 border-none">
                    <svg class="w-5 h-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    Terima Barang (Selesai)
                </button>
            </form>
        </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <!-- Items Details -->
            <div class="card p-0 overflow-hidden shadow-sm">
                <div class="p-5 sm:p-6 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center bg-slate-50/50 dark:bg-slate-800/20">
                    <h3 class="text-[15px] font-semibold text-slate-800 dark:text-white">Daftar Barang Transfer</h3>
                    <span class="badge-{{ $transfer->status->color() }} font-medium px-2.5 py-1">{{ $transfer->status->label() }}</span>
                </div>
                
                <table class="table w-full">
                    <thead class="bg-slate-50 dark:bg-slate-800/50">
                        <tr>
                            <th class="py-3 px-6 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800">Produk</th>
                            <th class="py-3 px-6 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800">Jumlah (Qty)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 dark:divide-slate-800/50">
                        @foreach($transfer->items as $item)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/20 transition-colors">
                            <td class="py-4 px-6">
                                <p class="font-semibold text-[14px] text-slate-800 dark:text-white">{{ $item->product->name }}</p>
                                <p class="text-[13px] text-slate-500 mt-0.5">{{ $item->product->product_code }}</p>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="inline-flex items-baseline gap-1.5 justify-end">
                                    <span class="font-bold text-[16px] text-slate-800 dark:text-white">{{ $item->qty }}</span>
                                    <span class="text-[13px] text-slate-500 font-medium">{{ $item->product->unit }}</span>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($transfer->status->value === 'PENDING')
                <div class="bg-blue-50/80 dark:bg-blue-900/20 p-5 rounded-xl border border-blue-100 dark:border-blue-900/50 flex items-start gap-3">
                    <svg class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <div class="text-[13.5px] text-blue-900 dark:text-blue-200 leading-relaxed">
                        <p class="font-semibold mb-1 text-blue-950 dark:text-blue-100">Status: Menunggu Konfirmasi</p>
                        <p>Stok sudah dikurangi dari toko asal <span class="font-semibold">({{ $transfer->fromStore->name }})</span>, namun belum ditambahkan ke toko tujuan <span class="font-semibold">({{ $transfer->toStore->name }})</span>. Silakan klik <strong class="font-semibold">Terima Barang</strong> jika barang sudah tiba di lokasi tujuan.</p>
                    </div>
                </div>
            @endif
        </div>

        <div class="space-y-6">
            <!-- Info Card -->
            <div class="card p-6 shadow-sm">
                <h3 class="text-[15px] font-semibold text-slate-800 dark:text-white mb-5">Informasi Transfer</h3>
                
                <div class="space-y-5">
                    <div>
                        <p class="label mb-1.5">No. Referensi</p>
                        <p class="font-mono text-[14px] font-medium text-slate-800 dark:text-white">TRF-{{ str_pad($transfer->id, 5, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    <div>
                        <p class="label mb-1.5">Toko Asal (Pengirim)</p>
                        <div class="inline-flex items-center px-2.5 py-1 rounded-md bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-[13px] font-medium text-slate-700 dark:text-slate-300">
                            {{ $transfer->fromStore->name }}
                        </div>
                    </div>
                    <div>
                        <p class="label mb-1.5">Toko Tujuan (Penerima)</p>
                        <div class="inline-flex items-center px-2.5 py-1 rounded-md bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-[13px] font-medium text-slate-700 dark:text-slate-300">
                            {{ $transfer->toStore->name }}
                        </div>
                    </div>
                    <div>
                        <p class="label mb-1.5">Tanggal & Waktu Dibuat</p>
                        <p class="text-[14px] font-medium text-slate-800 dark:text-white">{{ $transfer->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div>
                        <p class="label mb-2">Dibuat Oleh</p>
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700 flex items-center justify-center text-[11px] font-bold">
                                {{ substr($transfer->creator->name ?? 'S', 0, 1) }}
                            </div>
                            <p class="text-[14px] font-medium text-slate-800 dark:text-white">{{ $transfer->creator->name ?? 'System' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
