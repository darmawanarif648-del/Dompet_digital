<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Dompet Arif') }}</title>

        <!-- Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
        </style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-900 dark:text-slate-100 antialiased bg-slate-50 dark:bg-slate-950 min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-[420px] space-y-6">
            <div class="text-center space-y-2">
                <div class="w-14 h-14 mx-auto rounded-3xl bg-gradient-to-tr from-[#0f9d78] to-[#0b6e86] flex items-center justify-center text-white shadow-xl shadow-teal-700/20">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                </div>
                <h1 class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">Dompet Arif</h1>
                <p class="text-xs text-slate-500 dark:text-slate-400">Aplikasi dompet digital pribadi & pencatat keuangan cerdas</p>
            </div>

            <div class="bg-white dark:bg-slate-900 shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-200/80 dark:border-slate-800 rounded-3xl p-6 sm:p-7">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
