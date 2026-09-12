<x-app-layout>
    <x-slot name="header">Kelola Pelanggan</x-slot>
    <x-slot name="title">Daftar Pelanggan</x-slot>

    <div class="card p-6">
        <div class="mb-6 space-y-4">
            <h2 class="text-section-title text-gray-900 dark:text-white">Daftar Pelanggan</h2>
            
            <div class="filter-bar">
                <form action="{{ route('customers.index') }}" method="GET" class="flex flex-wrap items-center gap-3 w-full">
                    <div class="flex items-stretch">
                        <div class="relative w-full sm:w-72 lg:w-80 shrink-0">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama/telepon..." class="input w-full rounded-r-none border-r-0">
                        </div>
                        <button type="submit" class="btn-secondary rounded-l-none border-l-0 px-4 h-auto shrink-0 shadow-none m-0 focus:z-10 focus:ring-primary-500" title="Cari">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        </button>
                    </div>
                    
                    <div class="w-full sm:w-auto flex gap-2 justify-end ml-auto">
                        <button type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', 'create-customer')" class="btn btn-primary w-full sm:w-auto shrink-0">
                            <svg class="w-5 h-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Pelanggan</th>
                        <th>No. Telepon</th>
                        <th>Alamat</th>
                        <th>Saldo Deposit</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                        <tr>
                            <td>{{ $customers->firstItem() + $loop->index }}</td>
                            <td class="font-medium text-slate-800 dark:text-white">
                                {{ $customer->name }}
                            </td>
                            <td>{{ $customer->phone ?? '-' }}</td>
                            <td class="max-w-xs truncate" title="{{ $customer->address }}">{{ $customer->address ?? '-' }}</td>
                            <td class="font-semibold text-emerald-600 dark:text-emerald-400">
                                Rp {{ number_format($customer->deposit_balance, 0, ',', '.') }}
                            </td>
                            <td>
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', 'deposit-customer-{{ $customer->id }}')" class="btn-icon text-emerald-500 hover:bg-emerald-50 dark:hover:bg-emerald-500/10" title="Top-up Deposit">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                                    </button>
                                    <a href="{{ route('customers.show', $customer) }}" class="btn-icon text-primary-500 hover:bg-primary-50 dark:hover:bg-primary-500/10" title="Detail & Riwayat Deposit">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    </a>
                                    <button type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', 'edit-customer-{{ $customer->id }}')" class="btn-icon text-amber-500 hover:bg-amber-50 dark:hover:bg-amber-500/10" title="Edit">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </button>
                                    <form action="{{ route('customers.destroy', $customer) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pelanggan ini?');" class="inline">
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
                                Tidak ada data pelanggan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $customers->links() }}
        </div>
    </div>

    {{-- Create Modal --}}
    <x-modal name="create-customer" focusable>
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Tambah Pelanggan Baru</h2>
                <button x-on:click="$dispatch('close')" class="text-gray-400 hover:text-gray-500">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <form action="{{ route('customers.store') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="form_mode" value="create">
                
                <div>
                    <label for="name" class="label">Nama Pelanggan <span class="text-rose-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('form_mode') === 'create' ? old('name') : '' }}" class="input @error('name') border-rose-500 @enderror" required autofocus>
                    @if(old('form_mode') === 'create') @error('name') <p class="text-rose-500 text-sm mt-1">{{ $message }}</p> @enderror @endif
                </div>

                <div>
                    <label for="phone" class="label">Nomor Telepon</label>
                    <input type="text" id="phone" name="phone" value="{{ old('form_mode') === 'create' ? old('phone') : '' }}" class="input @error('phone') border-rose-500 @enderror">
                    @if(old('form_mode') === 'create') @error('phone') <p class="text-rose-500 text-sm mt-1">{{ $message }}</p> @enderror @endif
                </div>

                <div>
                    <label for="address" class="label">Alamat Lengkap</label>
                    <textarea id="address" name="address" rows="3" class="input @error('address') border-rose-500 @enderror">{{ old('form_mode') === 'create' ? old('address') : '' }}</textarea>
                    @if(old('form_mode') === 'create') @error('address') <p class="text-rose-500 text-sm mt-1">{{ $message }}</p> @enderror @endif
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" x-on:click="$dispatch('close')" class="btn bg-slate-100 text-slate-700 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </x-modal>

    {{-- Edit Modals --}}
    @foreach($customers as $customer)
        <x-modal name="edit-customer-{{ $customer->id }}" focusable>
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Edit Pelanggan: {{ $customer->name }}</h2>
                    <button x-on:click="$dispatch('close')" class="text-gray-400 hover:text-gray-500">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                <form action="{{ route('customers.update', $customer) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="form_mode" value="edit_{{ $customer->id }}">
                    
                    <div>
                        <label for="name_{{ $customer->id }}" class="label">Nama Pelanggan <span class="text-rose-500">*</span></label>
                        <input type="text" id="name_{{ $customer->id }}" name="name" value="{{ old('form_mode') === 'edit_'.$customer->id ? old('name') : $customer->name }}" class="input @error('name') border-rose-500 @enderror" required autofocus>
                        @if(old('form_mode') === 'edit_'.$customer->id) @error('name') <p class="text-rose-500 text-sm mt-1">{{ $message }}</p> @enderror @endif
                    </div>

                    <div>
                        <label for="phone_{{ $customer->id }}" class="label">Nomor Telepon</label>
                        <input type="text" id="phone_{{ $customer->id }}" name="phone" value="{{ old('form_mode') === 'edit_'.$customer->id ? old('phone') : $customer->phone }}" class="input @error('phone') border-rose-500 @enderror">
                        @if(old('form_mode') === 'edit_'.$customer->id) @error('phone') <p class="text-rose-500 text-sm mt-1">{{ $message }}</p> @enderror @endif
                    </div>

                    <div>
                        <label for="address_{{ $customer->id }}" class="label">Alamat Lengkap</label>
                        <textarea id="address_{{ $customer->id }}" name="address" rows="3" class="input @error('address') border-rose-500 @enderror">{{ old('form_mode') === 'edit_'.$customer->id ? old('address') : $customer->address }}</textarea>
                        @if(old('form_mode') === 'edit_'.$customer->id) @error('address') <p class="text-rose-500 text-sm mt-1">{{ $message }}</p> @enderror @endif
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                        <button type="button" x-on:click="$dispatch('close')" class="btn bg-slate-100 text-slate-700 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </x-modal>

        {{-- Deposit Modal --}}
        <x-modal name="deposit-customer-{{ $customer->id }}" focusable>
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Top-up Deposit: {{ $customer->name }}</h2>
                    <button x-on:click="$dispatch('close')" class="text-gray-400 hover:text-gray-500">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                <div class="mb-4 p-4 bg-emerald-50 dark:bg-emerald-500/10 rounded-lg">
                    <p class="text-sm text-emerald-600 dark:text-emerald-400 font-medium">Saldo Saat Ini: Rp {{ number_format($customer->deposit_balance, 0, ',', '.') }}</p>
                </div>
                <form action="{{ route('customers.deposit', $customer) }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="form_mode" value="deposit_{{ $customer->id }}">
                    
                    <div x-data="{
                        rawAmount: '{{ old('form_mode') === 'deposit_'.$customer->id ? old('amount') : '' }}',
                        get formattedAmount() {
                            if (!this.rawAmount) return '';
                            return parseInt(this.rawAmount).toLocaleString('id-ID');
                        },
                        set formattedAmount(value) {
                            let num = value.toString().replace(/[^0-9]/g, '');
                            this.rawAmount = num;
                        }
                    }">
                        <label for="amount_text_{{ $customer->id }}" class="label">Nominal Deposit (Rp) <span class="text-rose-500">*</span></label>
                        <div class="relative mt-1">
                            <span class="absolute left-3 top-2 text-slate-500 font-bold text-sm">Rp</span>
                            <input type="text" id="amount_text_{{ $customer->id }}" x-model="formattedAmount" class="input pl-9 @error('amount') border-rose-500 @enderror" required placeholder="0">
                            <input type="hidden" name="amount" x-model="rawAmount">
                        </div>
                        @if(old('form_mode') === 'deposit_'.$customer->id) @error('amount') <p class="text-rose-500 text-sm mt-1">{{ $message }}</p> @enderror @endif
                    </div>

                    <div>
                        <label for="notes_{{ $customer->id }}" class="label">Catatan (Opsional)</label>
                        <textarea id="notes_{{ $customer->id }}" name="notes" rows="2" class="input @error('notes') border-rose-500 @enderror" placeholder="Cth: Titip untuk proyek rumah">{{ old('form_mode') === 'deposit_'.$customer->id ? old('notes') : '' }}</textarea>
                        @if(old('form_mode') === 'deposit_'.$customer->id) @error('notes') <p class="text-rose-500 text-sm mt-1">{{ $message }}</p> @enderror @endif
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                        <button type="button" x-on:click="$dispatch('close')" class="btn bg-slate-100 text-slate-700 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Deposit</button>
                    </div>
                </form>
            </div>
        </x-modal>
    @endforeach

    {{-- Error Handling Script --}}
    @if ($errors->any() && old('form_mode'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                setTimeout(() => {
                    const mode = '{{ old('form_mode') }}';
                    if (mode === 'create') {
                        window.dispatchEvent(new CustomEvent('open-modal', { detail: 'create-customer' }));
                    } else if (mode.startsWith('edit_')) {
                        window.dispatchEvent(new CustomEvent('open-modal', { detail: 'edit-customer-' + mode.split('_')[1] }));
                    } else if (mode.startsWith('deposit_')) {
                        window.dispatchEvent(new CustomEvent('open-modal', { detail: 'deposit-customer-' + mode.split('_')[1] }));
                    }
                }, 100);
            });
        </script>
    @endif
</x-app-layout>
