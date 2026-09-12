<x-app-layout>
    <x-slot name="header">{{ $expense->exists ? 'Edit Pengeluaran' : 'Catat Pengeluaran' }}</x-slot>
    <x-slot name="title">{{ $expense->exists ? 'Edit Pengeluaran' : 'Catat Pengeluaran' }}</x-slot>

    <div class="card p-6 max-w-2xl mx-auto">
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('expenses.index') }}" class="btn-icon text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h2 class="text-section-title text-gray-900 dark:text-white">
                {{ $expense->exists ? 'Edit Pengeluaran Operasional' : 'Catat Pengeluaran Operasional' }}
            </h2>
        </div>

        <form action="{{ $expense->exists ? route('expenses.update', $expense) : route('expenses.store') }}" method="POST" class="space-y-6">
            @csrf
            @if($expense->exists)
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="store_id" class="label">Toko / Cabang <span class="text-rose-500">*</span></label>
                    <select id="store_id" name="store_id" class="input @error('store_id') border-rose-500 @enderror" required {{ !auth()->user()->isSuperAdmin() ? 'readonly pointer-events-none' : '' }}>
                        <option value="">Pilih Toko...</option>
                        @foreach($stores as $store)
                            <option value="{{ $store->id }}" {{ (old('store_id', $expense->store_id ?? auth()->user()->store_id) == $store->id) ? 'selected' : '' }}>
                                {{ $store->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('store_id') <p class="text-rose-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="date" class="label">Tanggal Pengeluaran <span class="text-rose-500">*</span></label>
                    <input type="date" id="date" name="date" value="{{ old('date', $expense->date ? $expense->date->format('Y-m-d') : date('Y-m-d')) }}" class="input @error('date') border-rose-500 @enderror" required>
                    @error('date') <p class="text-rose-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="amount" class="label">Nominal (Rp) <span class="text-rose-500">*</span></label>
                <div class="relative">
                    <span class="absolute left-3 top-2.5 text-slate-500 font-bold">Rp</span>
                    <input type="number" id="amount" name="amount" value="{{ old('amount', $expense->amount) }}" class="input pl-10 @error('amount') border-rose-500 @enderror" required min="1">
                </div>
                @error('amount') <p class="text-rose-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="description" class="label">Keterangan / Tujuan Pengeluaran <span class="text-rose-500">*</span></label>
                <textarea id="description" name="description" rows="3" placeholder="Contoh: Bayar listrik bulanan, Beli perlengkapan toko..." class="input @error('description') border-rose-500 @enderror" required>{{ old('description', $expense->description) }}</textarea>
                @error('description') <p class="text-rose-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                <a href="{{ route('expenses.index') }}" class="btn bg-slate-100 text-slate-700 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan Pengeluaran
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
