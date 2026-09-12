<x-app-layout>
    <x-slot name="header">{{ $module }}</x-slot>
    <x-slot name="title">{{ $module }}</x-slot>

    <div class="card p-12">
        <div class="text-center">
            <div class="w-20 h-20 mx-auto mb-6 bg-primary-50 dark:bg-primary-900/20 rounded-2xl flex items-center justify-center">
                <svg class="w-10 h-10 text-primary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 1 1-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 0 0 4.486-6.336l-3.276 3.277a3.004 3.004 0 0 1-2.25-2.25l3.276-3.276a4.5 4.5 0 0 0-6.336 4.486c.049.58.025 1.193-.14 1.743" />
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-slate-800 dark:text-white mb-2">Modul {{ $module }}</h2>
            <p class="text-slate-500 dark:text-slate-400 max-w-md mx-auto">
                Modul ini sedang dalam tahap pengembangan dan akan segera tersedia.
            </p>
            <div class="mt-6">
                <span class="badge-info">Segera Hadir</span>
            </div>
        </div>
    </div>
</x-app-layout>
