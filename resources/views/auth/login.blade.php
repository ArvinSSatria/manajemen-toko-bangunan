<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="text-center mb-8">
        <!-- Logo Area -->
        <div class="flex items-center justify-center gap-3 mb-10">
            <div class="w-8 h-10 bg-[#513252] rounded-md flex items-center justify-center text-white relative overflow-hidden">
                <div class="absolute w-2 h-6 bg-white/30 top-1 left-1 rounded-sm"></div>
                <span class="font-bold text-xl">T</span>
            </div>
            <h1 class="text-2xl font-bold" style="color: #513252;">Toko Bangunan.</h1>
        </div>

        <h2 class="text-3xl font-bold text-slate-900 dark:text-white mb-2 tracking-tight">Welcome Back</h2>
        <p class="text-[14px] text-slate-500 dark:text-slate-400">Let's login to grab amazing deal</p>
    </div>

    <!-- Login Form -->
    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div class="relative">
            <label for="email" class="absolute top-2 left-4 text-[10px] font-medium text-slate-400">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                   class="w-full pt-6 pb-2 px-4 bg-[#F5F6F8] dark:bg-[#1C1C1E] border-0 rounded-[10px] text-[13px] font-semibold text-slate-900 dark:text-white focus:ring-2 focus:ring-[#513252]" 
                   placeholder="rownok@gmail.com">
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div class="relative" x-data="{ show: false }">
            <label for="password" class="absolute top-2 left-4 text-[10px] font-medium text-slate-400">Password</label>
            <input id="password" :type="show ? 'text' : 'password'" name="password" required
                   class="w-full pt-6 pb-2 px-4 bg-[#F5F6F8] dark:bg-[#1C1C1E] border-0 rounded-[10px] text-[13px] font-semibold text-slate-900 dark:text-white focus:ring-2 focus:ring-[#513252]" 
                   placeholder="••••••••••••">
            
            <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 focus:outline-none">
                <svg x-show="!show" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                <svg x-show="show" x-cloak class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 011.51-3.1m2.2-2.2A10.05 10.05 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.05 10.05 0 01-1.51 3.1m-2.2 2.2A10.05 10.05 0 0112 19c-1.4 0-2.73-.3-3.925-.825m0 0l-3-3m3 3l3-3m-3 3L3 3m18 18L5.25 5.25" /></svg>
            </button>
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Remember & Forgot -->
        <div class="flex items-center justify-between pt-1">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" name="remember" class="w-3.5 h-3.5 rounded border-slate-300 text-[#513252] focus:ring-[#513252] dark:border-slate-600 dark:bg-slate-900">
                <span class="ms-2 text-[12px] font-bold text-slate-800 dark:text-slate-200">Remember me</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-[12px] font-bold text-slate-900 dark:text-white hover:underline" href="{{ route('password.request') }}">
                    Forgot Password?
                </a>
            @endif
        </div>

        <div class="pt-4">
            <button type="submit" class="w-full py-3.5 px-4 rounded-[10px] text-[14px] font-bold text-white transition-colors hover:opacity-90 shadow-sm" style="background-color: #513252;">
                Login
            </button>
        </div>
        
        <div class="text-center mt-6">
            <p class="text-[13px] text-slate-600 dark:text-slate-400 font-medium">
                Don't have an account? <a href="#" class="text-[#513252] dark:text-[#a06aa2] font-bold hover:underline">Sign Up</a>
            </p>
        </div>
    </form>
</x-guest-layout>
