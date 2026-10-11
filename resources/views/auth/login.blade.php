<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div class="space-y-1">
            <h2 class="text-base font-bold text-slate-800 dark:text-white">Masuk ke Akun Anda</h2>
            <p class="text-xs text-slate-400">Gunakan email dan kata sandi yang terdaftar</p>
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Email</label>
            <input id="email" class="block w-full text-xs rounded-2xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white px-4 py-2.5 focus:ring-emerald-500 focus:border-emerald-500" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="nama@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Kata Sandi</label>
            <input id="password" class="block w-full text-xs rounded-2xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white px-4 py-2.5 focus:ring-emerald-500 focus:border-emerald-500" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between text-xs pt-1">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-emerald-600 shadow-sm focus:ring-emerald-500" name="remember">
                <span class="ms-2 text-slate-600 dark:text-slate-400">Ingat saya</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-emerald-600 dark:text-emerald-400 font-semibold hover:underline" href="{{ route('password.request') }}">
                    Lupa sandi?
                </a>
            @endif
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full py-3 rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-bold text-sm shadow-md shadow-emerald-600/20 hover:from-emerald-700 hover:to-teal-700 transition">
                Masuk
            </button>
        </div>

        <div class="text-center pt-2 border-t border-slate-100 dark:border-slate-800">
            <p class="text-xs text-slate-500 dark:text-slate-400">
                Belum punya akun?
                <a href="{{ route('register') }}" class="font-bold text-emerald-600 dark:text-emerald-400 hover:underline">
                    Daftar Sekarang
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
