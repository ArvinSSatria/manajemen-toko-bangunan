<x-app-layout>
    <x-slot name="header">Kelola Toko</x-slot>
    <x-slot name="title">Daftar Toko</x-slot>

    <div class="card p-6">
        <div class="mb-6 space-y-4">
            <h2 class="text-section-title text-gray-900 dark:text-white">Daftar Toko Cabang</h2>
            
            <div class="filter-bar">
                <form action="{{ route('stores.index') }}" method="GET" class="flex flex-wrap items-center gap-3 w-full">
                    <div class="flex items-stretch">
                        <div class="relative w-full sm:w-72 lg:w-80 shrink-0">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari toko..." class="input w-full rounded-r-none border-r-0">
                        </div>
                        <button type="submit" class="btn-secondary rounded-l-none border-l-0 px-4 h-auto shrink-0 shadow-none m-0 focus:z-10 focus:ring-primary-500" title="Cari">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        </button>
                    </div>
                    
                    <div class="w-full sm:w-auto flex gap-2 justify-end ml-auto">
                        <button type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', 'create-store')" class="btn btn-primary w-full sm:w-auto shrink-0">
                            <svg class="w-5 h-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Toko
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
                        <th>Nama Toko</th>
                        <th>Alamat</th>
                        <th>Telepon</th>
                        <th>Status</th>
                        <th>Karyawan</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stores as $store)
                        <tr>
                            <td>{{ $stores->firstItem() + $loop->index }}</td>
                            <td class="font-medium text-slate-800 dark:text-white">{{ $store->name }}</td>
                            <td>{{ $store->address ?? '-' }}</td>
                            <td>{{ $store->phone ?? '-' }}</td>
                            <td>
                                @if($store->is_active)
                                    <span class="badge-success">Aktif</span>
                                @else
                                    <span class="badge-danger">Nonaktif</span>
                                @endif
                            </td>
                            <td>{{ $store->users_count ?? 0 }} Orang</td>
                            <td>
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', 'edit-store-{{ $store->id }}')" class="btn-icon text-amber-500 hover:bg-amber-50 dark:hover:bg-amber-500/10" title="Edit">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </button>
                                    <form action="{{ route('stores.destroy', $store) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus toko ini?');" class="inline">
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
                            <td colspan="7" class="text-center py-8 text-slate-500">
                                Tidak ada data toko.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $stores->links() }}
        </div>
    </div>

    {{-- Create Modal --}}
    <x-modal name="create-store" focusable>
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Tambah Toko Baru</h2>
                <button x-on:click="$dispatch('close')" class="text-gray-400 hover:text-gray-500">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <form action="{{ route('stores.store') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="form_mode" value="create">
                
                <div>
                    <label for="name" class="label">Nama Toko <span class="text-rose-500">*</span></label>
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
    @foreach($stores as $store)
        <x-modal name="edit-store-{{ $store->id }}" focusable>
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Edit Toko: {{ $store->name }}</h2>
                    <button x-on:click="$dispatch('close')" class="text-gray-400 hover:text-gray-500">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                <form action="{{ route('stores.update', $store) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="form_mode" value="edit_{{ $store->id }}">
                    
                    <div>
                        <label for="name_{{ $store->id }}" class="label">Nama Toko <span class="text-rose-500">*</span></label>
                        <input type="text" id="name_{{ $store->id }}" name="name" value="{{ old('form_mode') === 'edit_'.$store->id ? old('name') : $store->name }}" class="input @error('name') border-rose-500 @enderror" required autofocus>
                        @if(old('form_mode') === 'edit_'.$store->id) @error('name') <p class="text-rose-500 text-sm mt-1">{{ $message }}</p> @enderror @endif
                    </div>

                    <div>
                        <label for="phone_{{ $store->id }}" class="label">Nomor Telepon</label>
                        <input type="text" id="phone_{{ $store->id }}" name="phone" value="{{ old('form_mode') === 'edit_'.$store->id ? old('phone') : $store->phone }}" class="input @error('phone') border-rose-500 @enderror">
                        @if(old('form_mode') === 'edit_'.$store->id) @error('phone') <p class="text-rose-500 text-sm mt-1">{{ $message }}</p> @enderror @endif
                    </div>

                    <div>
                        <label for="address_{{ $store->id }}" class="label">Alamat Lengkap</label>
                        <textarea id="address_{{ $store->id }}" name="address" rows="3" class="input @error('address') border-rose-500 @enderror">{{ old('form_mode') === 'edit_'.$store->id ? old('address') : $store->address }}</textarea>
                        @if(old('form_mode') === 'edit_'.$store->id) @error('address') <p class="text-rose-500 text-sm mt-1">{{ $message }}</p> @enderror @endif
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                        <button type="button" x-on:click="$dispatch('close')" class="btn bg-slate-100 text-slate-700 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
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
                        window.dispatchEvent(new CustomEvent('open-modal', { detail: 'create-store' }));
                    } else if (mode.startsWith('edit_')) {
                        window.dispatchEvent(new CustomEvent('open-modal', { detail: 'edit-store-' + mode.split('_')[1] }));
                    }
                }, 100);
            });
        </script>
    @endif
</x-app-layout>
