<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{ darkMode: localStorage.getItem('darkMode') === 'true', sidebarOpen: window.innerWidth >= 1024 }"
      x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))"
      :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Sistem Manajemen Toko Bahan Bangunan - POS, Inventaris, dan Keuangan">

    <title>{{ $title ?? config('app.name') }} — {{ config('app.name') }}</title>

    <!-- Prevent FOUC for Dark Mode -->
    <script>
        if (localStorage.getItem('darkMode') === 'true') {
            document.documentElement.classList.add('dark');
        }
    </script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="font-sans antialiased text-slate-700 dark:text-slate-300">
    <div class="min-h-screen flex">

        {{-- Sidebar Overlay (mobile) --}}
        <div x-show="sidebarOpen"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="sidebarOpen = false"
             class="fixed inset-0 z-40 bg-black/50 lg:hidden"
             style="display: none;">
        </div>

        {{-- Sidebar --}}
        @include('layouts.partials.sidebar')

        {{-- Main Content --}}
        <div class="flex-1 min-h-screen flex flex-col transition-[margin] duration-300 lg:ml-[260px]"
             :class="sidebarOpen ? 'lg:!ml-[260px]' : 'lg:!ml-[80px]'">

            {{-- Top Navbar --}}
            @include('layouts.partials.navbar')

            {{-- Page Content --}}
            <main class="flex-1 p-6 lg:p-8">
                {{-- Flash Messages --}}
                @if(session('success'))
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: '{{ session("success") }}',
                                showConfirmButton: false,
                                showCloseButton: true,
                                timer: 2500,
                                timerProgressBar: true,
                            });
                        });
                    </script>
                @endif

                @if(session('error'))
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: '{{ session("error") }}',
                                showConfirmButton: true,
                                showCloseButton: true,
                                confirmButtonText: 'Tutup',
                            });
                        });
                    </script>
                @endif

                {{ $slot }}
            </main>

        </div>
    </div>

    @stack('scripts')
</body>
</html>
