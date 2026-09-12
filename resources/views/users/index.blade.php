<x-app-layout>
    <x-slot name="header">Kelola Pengguna</x-slot>
    <x-slot name="title">Daftar Pengguna</x-slot>

    <div class="card p-6">
        <div class="mb-6 space-y-4">
            <h2 class="text-section-title text-gray-900 dark:text-white">Daftar Karyawan / Pengguna</h2>
            
            <div class="filter-bar">
                <form action="{{ route('users.index') }}" method="GET" class="flex flex-wrap items-center gap-3 w-full">
                    <div class="w-full sm:w-auto flex-grow sm:flex-grow-0">
                        <select name="store_id" class="input w-full" onchange="this.form.submit()">
                            <option value="">Semua Toko</option>
                            @foreach($stores as $store)
                                <option value="{{ $store->id }}" {{ request('store_id') == $store->id ? 'selected' : '' }}>
                                    {{ $store->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="w-full sm:w-auto flex-grow sm:flex-grow-0">
                        <select name="role" class="input w-full" onchange="this.form.submit()">
                            <option value="">Semua Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                                    {{ ucwords(str_replace('_', ' ', $role->name)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-stretch">
                        <div class="relative w-full sm:w-72 lg:w-80 shrink-0">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama/email..." class="input w-full rounded-r-none border-r-0">
                        </div>
                        <button type="submit" class="btn-secondary rounded-l-none border-l-0 px-4 h-auto shrink-0 shadow-none m-0 focus:z-10 focus:ring-primary-500" title="Cari">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        </button>
                    </div>
                    
                    <div class="w-full sm:w-auto flex gap-2 justify-end ml-auto">
                        <button type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', 'create-user')" class="btn btn-primary w-full sm:w-auto shrink-0">
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
                        <th>Nama Lengkap</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Toko / Cabang</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>{{ $users->firstItem() + $loop->index }}</td>
                            <td class="font-medium text-slate-800 dark:text-white">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 flex items-center justify-center font-bold text-xs">
                                        {{ substr($user->name, 0, 2) }}
                                    </div>
                                    {{ $user->name }}
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @foreach($user->roles as $role)
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium 
                                        {{ $role->name === 'super_admin' ? 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400' : '' }}
                                        {{ $role->name === 'store_admin' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : '' }}
                                        {{ $role->name === 'cashier' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : '' }}
                                        {{ $role->name === 'central_sales' ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400' : '' }}
                                    ">
                                        {{ ucwords(str_replace('_', ' ', $role->name)) }}
                                    </span>
                                @endforeach
                            </td>
                            <td>
                                @if($user->isSuperAdmin() || $user->hasRole('central_sales'))
                                    <span class="badge-info">Semua Toko</span>
                                @else
                                    {{ $user->store->name ?? '-' }}
                                @endif
                            </td>
                            <td>
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', 'edit-user-{{ $user->id }}')" class="btn-icon text-amber-500 hover:bg-amber-50 dark:hover:bg-amber-500/10" title="Edit">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </button>
                                    @if($user->id !== auth()->id())
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-icon text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-500/10" title="Hapus">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-slate-500">
                                Tidak ada data pengguna.
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
            {{ $users->links() }}
        </div>
    </div>

    {{-- Create Modal --}}
    <x-modal name="create-user" focusable maxWidth="2xl">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-[15px] font-semibold text-slate-800 dark:text-white">Tambah Pengguna Baru</h2>
                <button type="button" x-on:click="$dispatch('close')" class="p-1.5 hover:bg-slate-200/50 rounded text-slate-400 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            
            <form action="{{ route('users.store') }}" method="POST" class="flex-1 flex flex-col min-h-0">
                @csrf
                <input type="hidden" name="form_mode" value="create">

                <div class="flex-1 overflow-y-auto flex flex-col space-y-5" x-data="{ selectedRole: '{{ old('form_mode') === 'create' ? old('role') : '' }}' }">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="label">Nama Lengkap <span class="text-rose-500">*</span></label>
                            <input type="text" id="name" name="name" value="{{ old('form_mode') === 'create' ? old('name') : '' }}" class="input @error('name') border-rose-500 @enderror" required>
                            @if(old('form_mode') === 'create') @error('name') <p class="text-rose-500 text-sm mt-1">{{ $message }}</p> @enderror @endif
                        </div>

                        <div>
                            <label for="email" class="label">Email <span class="text-rose-500">*</span></label>
                            <input type="email" id="email" name="email" value="{{ old('form_mode') === 'create' ? old('email') : '' }}" class="input @error('email') border-rose-500 @enderror" required>
                            @if(old('form_mode') === 'create') @error('email') <p class="text-rose-500 text-sm mt-1">{{ $message }}</p> @enderror @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="role" class="label">Peran (Role) <span class="text-rose-500">*</span></label>
                            <select id="role" name="role" x-model="selectedRole" class="input @error('role') border-rose-500 @enderror" required>
                                <option value="">Pilih Role...</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}">
                                        {{ ucwords(str_replace('_', ' ', $role->name)) }}
                                    </option>
                                @endforeach
                            </select>
                            @if(old('form_mode') === 'create') @error('role') <p class="text-rose-500 text-sm mt-1">{{ $message }}</p> @enderror @endif
                        </div>
                        
                        <div x-show="selectedRole !== 'super_admin' && selectedRole !== 'central_sales'">
                            <label for="store_id" class="label">Toko / Cabang</label>
                            <select id="store_id" name="store_id" class="input @error('store_id') border-rose-500 @enderror">
                                <option value="">Pilih Toko...</option>
                                @foreach($stores as $store)
                                    <option value="{{ $store->id }}" {{ (old('form_mode') === 'create' && old('store_id') == $store->id) ? 'selected' : '' }}>
                                        {{ $store->name }}
                                    </option>
                                @endforeach
                            </select>
                            @if(old('form_mode') === 'create') @error('store_id') <p class="text-rose-500 text-sm mt-1">{{ $message }}</p> @enderror @endif
                        </div>
                        <div x-show="selectedRole === 'super_admin' || selectedRole === 'central_sales'">
                            <label class="label text-slate-400">Toko / Cabang</label>
                            <input type="text" disabled value="Akses Semua Toko" class="input bg-slate-50 cursor-not-allowed">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="label">Password <span class="text-rose-500">*</span></label>
                            <input type="password" id="password" name="password" class="input @error('password') border-rose-500 @enderror" required autocomplete="new-password">
                            @if(old('form_mode') === 'create') @error('password') <p class="text-rose-500 text-sm mt-1">{{ $message }}</p> @enderror @endif
                        </div>

                        <div>
                            <label for="password_confirmation" class="label">Konfirmasi Password <span class="text-rose-500">*</span></label>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="input" required autocomplete="new-password">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-5 mt-5 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" x-on:click="$dispatch('close')" class="btn-secondary">Batal</button>
                    <button type="submit" class="btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </x-modal>

    {{-- Edit Modals --}}
    @foreach($users as $user)
        <x-modal name="edit-user-{{ $user->id }}" focusable maxWidth="2xl">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-[15px] font-semibold text-slate-800 dark:text-white">Edit Pengguna: {{ $user->name }}</h2>
                    <button type="button" x-on:click="$dispatch('close')" class="p-1.5 hover:bg-slate-200/50 rounded text-slate-400 transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                
                <form action="{{ route('users.update', $user) }}" method="POST" class="flex-1 flex flex-col min-h-0">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="form_mode" value="edit_{{ $user->id }}">

                    <div class="flex-1 overflow-y-auto flex flex-col space-y-5" x-data="{ selectedRole: '{{ old('form_mode') === 'edit_'.$user->id ? old('role') : $user->roles->first()?->name }}' }">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="name_{{ $user->id }}" class="label">Nama Lengkap <span class="text-rose-500">*</span></label>
                                <input type="text" id="name_{{ $user->id }}" name="name" value="{{ old('form_mode') === 'edit_'.$user->id ? old('name') : $user->name }}" class="input @error('name') border-rose-500 @enderror" required>
                                @if(old('form_mode') === 'edit_'.$user->id) @error('name') <p class="text-rose-500 text-sm mt-1">{{ $message }}</p> @enderror @endif
                            </div>

                            <div>
                                <label for="email_{{ $user->id }}" class="label">Email <span class="text-rose-500">*</span></label>
                                <input type="email" id="email_{{ $user->id }}" name="email" value="{{ old('form_mode') === 'edit_'.$user->id ? old('email') : $user->email }}" class="input @error('email') border-rose-500 @enderror" required>
                                @if(old('form_mode') === 'edit_'.$user->id) @error('email') <p class="text-rose-500 text-sm mt-1">{{ $message }}</p> @enderror @endif
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="role_{{ $user->id }}" class="label">Peran (Role) <span class="text-rose-500">*</span></label>
                                <select id="role_{{ $user->id }}" name="role" x-model="selectedRole" class="input @error('role') border-rose-500 @enderror" required>
                                    <option value="">Pilih Role...</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}">
                                            {{ ucwords(str_replace('_', ' ', $role->name)) }}
                                        </option>
                                    @endforeach
                                </select>
                                @if(old('form_mode') === 'edit_'.$user->id) @error('role') <p class="text-rose-500 text-sm mt-1">{{ $message }}</p> @enderror @endif
                            </div>
                            
                            <div x-show="selectedRole !== 'super_admin' && selectedRole !== 'central_sales'">
                                <label for="store_id_{{ $user->id }}" class="label">Toko / Cabang</label>
                                <select id="store_id_{{ $user->id }}" name="store_id" class="input @error('store_id') border-rose-500 @enderror">
                                    <option value="">Pilih Toko...</option>
                                    @foreach($stores as $store)
                                        <option value="{{ $store->id }}" {{ (old('form_mode') === 'edit_'.$user->id ? old('store_id') == $store->id : $user->store_id == $store->id) ? 'selected' : '' }}>
                                            {{ $store->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @if(old('form_mode') === 'edit_'.$user->id) @error('store_id') <p class="text-rose-500 text-sm mt-1">{{ $message }}</p> @enderror @endif
                            </div>
                            <div x-show="selectedRole === 'super_admin' || selectedRole === 'central_sales'">
                                <label class="label text-slate-400">Toko / Cabang</label>
                                <input type="text" disabled value="Akses Semua Toko" class="input bg-slate-50 cursor-not-allowed">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="password_{{ $user->id }}" class="label">Password <span class="text-slate-400 font-normal normal-case">(opsional)</span></label>
                                <input type="password" id="password_{{ $user->id }}" name="password" class="input @error('password') border-rose-500 @enderror" placeholder="Kosongkan jika tidak diubah" autocomplete="new-password">
                                @if(old('form_mode') === 'edit_'.$user->id) @error('password') <p class="text-rose-500 text-sm mt-1">{{ $message }}</p> @enderror @endif
                            </div>

                            <div>
                                <label for="password_confirmation_{{ $user->id }}" class="label">Konfirmasi Password</label>
                                <input type="password" id="password_confirmation_{{ $user->id }}" name="password_confirmation" class="input" placeholder="Kosongkan jika tidak diubah" autocomplete="new-password">
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 pt-5 mt-5 border-t border-slate-100 dark:border-slate-800">
                        <button type="button" x-on:click="$dispatch('close')" class="btn-secondary">Batal</button>
                        <button type="submit" class="btn-primary">Simpan Perubahan</button>
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
                        window.dispatchEvent(new CustomEvent('open-modal', { detail: 'create-user' }));
                    } else if (mode.startsWith('edit_')) {
                        window.dispatchEvent(new CustomEvent('open-modal', { detail: 'edit-user-' + mode.split('_')[1] }));
                    }
                }, 100);
            });
        </script>
    @endif
</x-app-layout>
