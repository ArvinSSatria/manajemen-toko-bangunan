{{-- Top Navbar --}}
<header class="sticky top-0 z-30 bg-white/70 dark:bg-[#1C1C1E]/70 backdrop-blur-xl border-b border-slate-200/50 dark:border-slate-800/50" style="height: 72px;">
    <div class="flex items-center justify-between h-full px-4 sm:px-6 lg:px-8">

        {{-- Left: Hamburger + Page Title --}}
        <div class="flex items-center gap-4">
            {{-- Mobile menu button --}}
            <button @click="sidebarOpen = !sidebarOpen"
                    class="p-2 -ml-2 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:text-gray-200 dark:hover:bg-gray-800 lg:hidden"
                    id="btn-toggle-sidebar">
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>



            {{-- Page title --}}
            @isset($header)
                <h2 class="text-base font-semibold text-gray-900 dark:text-white hidden sm:block">
                    {{ $header }}
                </h2>
            @endisset
        </div>

        {{-- Right: Store selector + Dark mode + User dropdown --}}
        <div class="flex items-center gap-2">

            {{-- Store Selector (Super Admin only) --}}
            @if(auth()->user()->isSuperAdmin())
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open"
                        class="flex items-center gap-2 px-3 h-9 text-sm rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800"
                        id="btn-store-selector">
                    <svg class="w-4 h-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349" />
                    </svg>
                    <span class="font-medium">{{ session('active_store_name', 'Semua Toko') }}</span>
                    <svg class="w-3.5 h-3.5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                    </svg>
                </button>

                <div x-show="open"
                     @click.outside="open = false"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-900 rounded-xl shadow-lg py-1 z-50"
                     style="border: 1px solid #e5e7eb; display: none;">
                    <a href="{{ route('store.switch', ['store_id' => 'all']) }}"
                       class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 {{ !session('active_store_id') ? 'text-primary-600 dark:text-primary-400 font-medium bg-primary-50/50 dark:bg-primary-900/10' : '' }}">
                        Semua Toko
                    </a>
                    @foreach(\App\Models\Store::where('is_active', true)->get() as $store)
                        <a href="{{ route('store.switch', ['store_id' => $store->id]) }}"
                           class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 {{ session('active_store_id') == $store->id ? 'text-primary-600 dark:text-primary-400 font-medium bg-primary-50/50 dark:bg-primary-900/10' : '' }}">
                            {{ $store->name }}
                        </a>
                    @endforeach
                </div>
            </div>
            @else
                @if(auth()->user()->store)
                <span class="hidden sm:flex items-center gap-2 px-3 h-9 text-sm text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-800 rounded-lg">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349" />
                    </svg>
                    {{ auth()->user()->store->name }}
                </span>
                @endif
            @endif

            {{-- Dark mode toggle --}}
            <button @click="darkMode = !darkMode"
                    class="p-2 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:text-gray-200 dark:hover:bg-gray-800"
                    id="btn-dark-mode">
                <svg x-show="!darkMode" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" />
                </svg>
                <svg x-show="darkMode" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="display: none;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                </svg>
            </button>

            {{-- User Dropdown --}}
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open"
                        class="flex items-center gap-2.5 pl-2 pr-1 h-10 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800"
                        id="btn-user-menu">
                    <div class="w-8 h-8 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center text-gray-500 dark:text-gray-400 text-xs font-semibold">
                        {{ substr(auth()->user()->name, 0, 2) }}
                    </div>
                    <div class="hidden sm:block text-left">
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-200 leading-tight">{{ auth()->user()->name }}</p>
                    </div>
                    <svg class="w-3.5 h-3.5 text-gray-400 hidden sm:block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                    </svg>
                </button>

                <div x-show="open"
                     @click.outside="open = false"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-900 rounded-xl shadow-lg py-1 z-50"
                     style="border: 1px solid #e5e7eb; display: none;">

                    <div class="px-4 py-3 border-b" style="border-color: #f3f4f6;">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate mt-0.5">{{ auth()->user()->email }}</p>
                    </div>

                    <a href="{{ route('profile.edit') }}"
                       class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800">
                        <svg class="w-4 h-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                        </svg>
                        Profil Saya
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="flex items-center gap-2 w-full px-4 py-2.5 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/10"
                                id="btn-logout">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                            </svg>
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
