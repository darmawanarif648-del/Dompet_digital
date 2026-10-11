<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div class="space-y-1">
            <h2 class="text-base font-bold text-slate-800 dark:text-white">Buat Akun Baru</h2>
            <p class="text-xs text-slate-400">Mulai kelola keuangan pribadi Anda dengan mudah</p>
        </div>

        <!-- Name -->
        <div>
            <label for="name" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Nama Lengkap</label>
            <input id="name" class="block w-full text-xs rounded-2xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white px-4 py-2.5 focus:ring-emerald-500 focus:border-emerald-500" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Arif Wibowo" />
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Alamat Email</label>
            <input id="email" class="block w-full text-xs rounded-2xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white px-4 py-2.5 focus:ring-emerald-500 focus:border-emerald-500" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="arif@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Kata Sandi</label>
            <input id="password" class="block w-full text-xs rounded-2xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white px-4 py-2.5 focus:ring-emerald-500 focus:border-emerald-500" type="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter" />
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Konfirmasi Kata Sandi</label>
            <input id="password_confirmation" class="block w-full text-xs rounded-2xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white px-4 py-2.5 focus:ring-emerald-500 focus:border-emerald-500" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi kata sandi" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full py-3 rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-bold text-sm shadow-md shadow-emerald-600/20 hover:from-emerald-700 hover:to-teal-700 transition">
                Daftar Akun
            </button>
        </div>

        <div class="text-center pt-2 border-t border-slate-100 dark:border-slate-800">
            <p class="text-xs text-slate-500 dark:text-slate-400">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="font-bold text-emerald-600 dark:text-emerald-400 hover:underline">
                    Masuk di sini
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
