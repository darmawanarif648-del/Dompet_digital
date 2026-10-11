<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Dompet Arif') }}</title>

        <!-- Google Fonts: Inter -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <style>
            body {
                font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            }
        </style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-100 dark:bg-slate-950 text-slate-900 dark:text-slate-100 min-h-screen selection:bg-emerald-500 selection:text-white transition-colors duration-200">
        <div class="min-h-screen flex flex-col">
            @include('layouts.navigation')

            <!-- Main Content Container (Mobile-First 480px max-width) -->
            <main class="flex-1 w-full max-w-[480px] mx-auto px-4 py-5 pb-24 sm:py-6">
                <!-- Flash Notification Status -->
                @if (session('status'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" class="mb-4 p-3.5 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-700 dark:text-emerald-300 text-xs font-semibold flex items-center justify-between shadow-sm">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span>{{ session('status') }}</span>
                        </div>
                        <button @click="show = false" class="text-emerald-600 dark:text-emerald-400 hover:opacity-75">✕</button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 p-3.5 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-700 dark:text-rose-300 text-xs font-medium space-y-1 shadow-sm">
                        <div class="font-bold flex items-center gap-1.5 text-rose-600 dark:text-rose-400">
                            <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            Terdapat kesalahan input:
                        </div>
                        <ul class="list-disc list-inside ps-1 space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>

        <!-- Global Toast Container -->
        <div id="toastContainer" class="fixed bottom-5 left-1/2 -translate-x-1/2 w-[90%] max-w-[400px] z-50 pointer-events-none flex flex-col gap-2"></div>
    </body>
</html>
