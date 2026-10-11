<nav x-data="{ open: false }" class="bg-white dark:bg-slate-900 border-b border-gray-100 dark:border-slate-800 sticky top-0 z-30 shadow-sm backdrop-blur-md bg-white/90 dark:bg-slate-900/90">
    <!-- Primary Navigation Menu -->
    <div class="max-w-xl mx-auto px-4 sm:px-6">
        <div class="flex justify-between h-16 items-center">
            <div class="flex items-center gap-3">
                <!-- Logo / App Name -->
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-[#0f9d78] to-[#0b6e86] flex items-center justify-center text-white shadow-md shadow-emerald-500/20">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    </div>
                    <div>
                        <span class="font-bold text-lg text-slate-800 dark:text-white tracking-tight">Dompet Arif</span>
                        <span class="text-[10px] block font-semibold text-emerald-600 dark:text-emerald-400 uppercase tracking-widest -mt-1">Pribadi</span>
                    </div>
                </a>
            </div>

            <!-- Settings Dropdown -->
            <div class="flex items-center gap-2">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-1.5 border border-slate-200 dark:border-slate-700 text-xs font-semibold rounded-full text-slate-700 dark:text-slate-200 bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 focus:outline-none transition">
                            <span class="truncate max-w-[100px]">{{ Auth::user()->name }}</span>
                            <svg class="ms-1.5 h-3.5 w-3.5 fill-current text-slate-400" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-2 border-b border-gray-100 dark:border-slate-700">
                            <p class="text-xs text-gray-500 dark:text-gray-400">Masuk sebagai</p>
                            <p class="text-sm font-semibold text-gray-800 dark:text-white truncate">{{ Auth::user()->email }}</p>
                        </div>

                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profil Saya') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Keluar') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>
</nav>
