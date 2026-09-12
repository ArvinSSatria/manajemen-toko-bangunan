<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }"
      x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))"
      :class="{ 'dark': darkMode }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Arsaweb Bangunan') }} - Login</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Prevent FOUC for Dark Mode -->
        <script>
            if (localStorage.getItem('darkMode') === 'true') {
                document.documentElement.classList.add('dark');
            }
        </script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-slate-900 bg-white dark:bg-[#121212] dark:text-white">
        <div class="min-h-screen flex flex-col md:flex-row p-4 md:p-6 lg:p-8 gap-8">
            
            <!-- Left Side / Login Form -->
            <div class="flex-1 flex flex-col justify-center items-center w-full relative order-2 md:order-1">
                <div class="w-full max-w-sm">
                    {{ $slot }}
                </div>
            </div>

            <!-- Right Side / Background Image Container -->
            <div class="hidden md:flex flex-1 relative order-1 md:order-2">
                <!-- Custom shape container -->
                <div class="w-full h-full relative overflow-hidden bg-slate-100 dark:bg-slate-800" 
                     style="border-radius: 40px; border-top-left-radius: 120px; border-bottom-right-radius: 120px; min-height: 600px;">
                    
                    <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=2070&auto=format&fit=crop" 
                         alt="Building" class="absolute inset-0 w-full h-full object-cover">
                    
                    <!-- Overlay gradient -->
                    <div class="absolute inset-0 bg-gradient-to-b from-black/40 via-transparent to-transparent"></div>
                    
                    <!-- Content Overlay -->
                    <div class="absolute top-12 right-12 left-12 text-right">
                        <h2 class="text-2xl lg:text-3xl font-bold tracking-tight text-white mb-4 ml-auto max-w-sm drop-shadow-md">
                            Browse thousands of properties to buy, sell, or rent with trusted agents.
                        </h2>
                    </div>
                </div>
                
                <!-- Dark Mode Toggle (Floating over image) -->
                <div class="absolute bottom-6 right-6 z-20">
                    <button @click="darkMode = !darkMode" class="p-3 rounded-full bg-black/30 backdrop-blur-md text-white hover:bg-black/50 transition-colors shadow-lg border border-white/20">
                        <svg x-show="!darkMode" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg>
                        <svg x-show="darkMode" x-cloak class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    </button>
                </div>
            </div>
            
        </div>
    </body>
</html>
