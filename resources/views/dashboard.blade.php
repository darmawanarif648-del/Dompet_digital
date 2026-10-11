<x-app-layout>
    <div x-data="dompetApp()" x-init="initApp()" class="space-y-5">

        <!-- 1. KARTU SALDO UTAMA (Gradasi Hijau-Teal Mewah #0f9d78 ke #0b6e86) -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-[#0f9d78] to-[#0b6e86] p-6 text-white shadow-xl shadow-teal-900/20">
            <!-- Background Decorative Orbs -->
            <div class="absolute -right-12 -top-12 w-44 h-44 rounded-full bg-white/10 blur-2xl pointer-events-none"></div>
            <div class="absolute -left-10 -bottom-10 w-36 h-36 rounded-full bg-black/10 blur-xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col justify-between space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-xs uppercase tracking-wider font-semibold text-emerald-100/80">Saldo Dompet</span>
                        <h2 class="text-3xl font-extrabold tracking-tight mt-0.5">
                            @rupiah($mainBalance)
                        </h2>
                    </div>
                    <div class="w-10 h-10 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center border border-white/20">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>

                <!-- Ringkasan Bulan Ini (In vs Out) -->
                <div class="pt-2 border-t border-white/15 grid grid-cols-2 gap-3 text-xs">
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-2.5 border border-white/10">
                        <div class="flex items-center gap-1.5 text-emerald-200">
                            <svg class="w-3.5 h-3.5 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 11l5-5m0 0l5 5m-5-5v12" />
                            </svg>
                            <span class="font-medium text-[11px]">Masuk Bulan Ini</span>
                        </div>
                        <div class="font-bold text-sm text-white mt-1">
                            @rupiah($monthlyIn)
                        </div>
                    </div>

                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-2.5 border border-white/10">
                        <div class="flex items-center gap-1.5 text-rose-200">
                            <svg class="w-3.5 h-3.5 text-rose-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 13l-5 5m0 0l-5-5m5 5V6" />
                            </svg>
                            <span class="font-medium text-[11px]">Keluar Bulan Ini</span>
                        </div>
                        <div class="font-bold text-sm text-white mt-1">
                            @rupiah($monthlyOut)
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. TOMBOL AKSI CEPAT (Suara & Foto Struk) -->
        <div class="grid grid-cols-2 gap-3">
            <!-- Tombol Suara -->
            <button
                @click="toggleVoiceRecord()"
                :class="isRecording ? 'bg-rose-600 text-white animate-pulse ring-4 ring-rose-400 shadow-rose-600/30' : 'bg-white dark:bg-slate-900 text-slate-800 dark:text-white hover:bg-slate-50 dark:hover:bg-slate-800 border border-slate-200/80 dark:border-slate-800 shadow-sm'"
                class="group relative flex items-center justify-center gap-2.5 py-3.5 px-4 rounded-2xl font-bold text-sm transition-all duration-200 active:scale-95"
            >
                <div :class="isRecording ? 'bg-white/20' : 'bg-rose-500/10 text-rose-600 dark:text-rose-400'" class="w-9 h-9 rounded-xl flex items-center justify-center transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
                    </svg>
                </div>
                <div class="text-left">
                    <span class="block text-xs uppercase font-extrabold tracking-wider" :class="isRecording ? 'text-white' : 'text-slate-500 dark:text-slate-400'">Bicara</span>
                    <span class="block text-sm" x-text="isRecording ? 'Mendengarkan...' : 'Suara'">Suara</span>
                </div>
            </button>

            <!-- Tombol Foto Struk -->
            <label
                for="receiptCameraInput"
                class="cursor-pointer group relative flex items-center justify-center gap-2.5 py-3.5 px-4 rounded-2xl font-bold text-sm bg-white dark:bg-slate-900 text-slate-800 dark:text-white hover:bg-slate-50 dark:hover:bg-slate-800 border border-slate-200/80 dark:border-slate-800 shadow-sm transition-all duration-200 active:scale-95"
            >
                <input
                    type="file"
                    id="receiptCameraInput"
                    accept="image/*"
                    capture="environment"
                    class="hidden"
                    @change="handleCameraReceiptUpload($event)"
                >
                <div class="w-9 h-9 rounded-xl bg-teal-500/10 text-teal-600 dark:text-teal-400 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <div class="text-left">
                    <span class="block text-xs uppercase font-extrabold tracking-wider text-slate-500 dark:text-slate-400">Scan OCR</span>
                    <span class="block text-sm">Foto Struk</span>
                </div>
            </label>
        </div>

        <!-- Tombol Tambah Manual (+) -->
        <button
            @click="openAddModal()"
            class="w-full flex items-center justify-center gap-2 py-3 rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold text-sm shadow-md shadow-emerald-600/20 hover:from-emerald-700 hover:to-teal-700 active:scale-[0.98] transition"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
            </svg>
            <span>Catat Transaksi Manual</span>
        </button>

        <!-- 3. RINGKASAN PENGELUARAN PER KATEGORI & ANGGARAN BULANAN -->
        <div class="bg-white dark:bg-slate-900 rounded-3xl p-5 border border-slate-200/80 dark:border-slate-800 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-sm text-slate-800 dark:text-white">Pengeluaran & Anggaran</h3>
                    <p class="text-xs text-slate-400">Monitoring pemakaian bulan ini</p>
                </div>
                <button
                    @click="isBudgetModalOpen = true"
                    class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 hover:underline flex items-center gap-1"
                >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Atur Limit
                </button>
            </div>

            <div class="space-y-3 pt-1">
                @foreach ($categoryExpenses as $cat)
                    <div class="space-y-1">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $cat['name'] }}</span>
                            <div class="flex items-center gap-1.5">
                                <span class="font-bold text-slate-900 dark:text-white">@rupiah($cat['amount'])</span>
                                <span class="text-[10px] text-slate-400">({{ $cat['percentage'] }}%)</span>
                            </div>
                        </div>

                        <!-- Progress Bar Keseluruhan Kategori -->
                        <div class="w-full h-2 rounded-full bg-slate-100 dark:bg-slate-800 overflow-hidden flex">
                            <div
                                class="h-full rounded-full transition-all duration-500 {{ $cat['is_warning'] ? 'bg-rose-500' : 'bg-emerald-500' }}"
                                style="width: {{ min(100, $cat['percentage']) }}%"
                            ></div>
                        </div>

                        <!-- Info Limit Anggaran jika disetel -->
                        @if ($cat['budget_limit'] > 0)
                            <div class="flex items-center justify-between text-[10px] pt-0.5">
                                <span class="text-slate-400">
                                    Limit: @rupiah($cat['budget_limit'])
                                </span>
                                @if ($cat['is_warning'])
                                    <span class="font-bold text-rose-500 flex items-center gap-0.5 animate-pulse">
                                        ⚠️ Terpakai {{ $cat['budget_usage_percent'] }}% (Mendekati/Lewat Batas!)
                                    </span>
                                @else
                                    <span class="text-emerald-600 dark:text-emerald-400 font-medium">
                                        Terpakai {{ $cat['budget_usage_percent'] }}%
                                    </span>
                                @endif
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <!-- 4. RIWAYAT TRANSAKSI & FILTER & EKSPOR CSV -->
        <div class="bg-white dark:bg-slate-900 rounded-3xl p-5 border border-slate-200/80 dark:border-slate-800 shadow-sm space-y-4">
            <div class="flex items-center justify-between flex-wrap gap-2">
                <div>
                    <h3 class="font-bold text-sm text-slate-800 dark:text-white">Riwayat Transaksi</h3>
                    <p class="text-xs text-slate-400">Catatan mutasi dompet Anda</p>
                </div>
                <a
                    href="{{ route('transactions.export-csv', ['month' => $filterMonth, 'category' => $filterCategory]) }}"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 text-xs font-semibold transition"
                >
                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Ekspor CSV
                </a>
            </div>

            <!-- Filter Bulan & Kategori -->
            <form method="GET" action="{{ route('dashboard') }}" class="grid grid-cols-2 gap-2 text-xs">
                <div>
                    <label class="block text-[10px] uppercase font-bold text-slate-400 mb-1">Bulan</label>
                    <input
                        type="month"
                        name="month"
                        value="{{ $filterMonth }}"
                        onchange="this.form.submit()"
                        class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-700 dark:text-slate-200 p-2 focus:ring-emerald-500 focus:border-emerald-500"
                    >
                </div>
                <div>
                    <label class="block text-[10px] uppercase font-bold text-slate-400 mb-1">Kategori</label>
                    <select
                        name="category"
                        onchange="this.form.submit()"
                        class="w-full text-xs rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-700 dark:text-slate-200 p-2 focus:ring-emerald-500 focus:border-emerald-500"
                    >
                        <option value="">Semua Kategori</option>
                        <option value="Pemasukan" {{ $filterCategory === 'Pemasukan' ? 'selected' : '' }}>Pemasukan</option>
                        @foreach ($standardCategories as $cat)
                            <option value="{{ $cat }}" {{ $filterCategory === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
            </form>

            <!-- List Transaksi -->
            <div class="divide-y divide-slate-100 dark:divide-slate-800 pt-1">
                @forelse ($transactions as $tx)
                    <div class="py-3 flex items-center justify-between gap-3 group">
                        <div class="flex items-center gap-3 min-w-0">
                            <!-- Icon Kategori -->
                            <div class="w-10 h-10 rounded-2xl flex items-center justify-center shrink-0 {{ $tx->type === 'in' ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' : 'bg-rose-500/10 text-rose-600 dark:text-rose-400' }}">
                                @if ($tx->type === 'in')
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/></svg>
                                @elseif ($tx->category === 'Makan & Minum')
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                @elseif ($tx->category === 'Transportasi')
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                @else
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                @endif
                            </div>

                            <div class="min-w-0">
                                <div class="font-bold text-xs sm:text-sm text-slate-800 dark:text-white truncate">
                                    {{ $tx->description }}
                                </div>
                                <div class="flex items-center gap-1.5 text-[11px] text-slate-400 mt-0.5">
                                    <span>{{ $tx->transacted_at->format('d M, H:i') }}</span>
                                    <span>•</span>
                                    <span class="font-medium text-slate-500 dark:text-slate-400">{{ $tx->category }}</span>
                                    @if ($tx->source !== 'manual')
                                        <span>•</span>
                                        <span class="text-[9px] uppercase px-1 rounded bg-slate-100 dark:bg-slate-800 text-slate-500">{{ $tx->source }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Nominal & Aksi -->
                        <div class="text-right shrink-0 space-y-1">
                            <div class="font-extrabold text-xs sm:text-sm {{ $tx->type === 'in' ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-800 dark:text-white' }}">
                                {{ $tx->type === 'in' ? '+' : '-' }}@rupiah($tx->amount)
                            </div>

                            <div class="flex items-center justify-end gap-1.5">
                                @if ($tx->receipt_path)
                                    <button
                                        @click="openReceiptPreview('{{ asset('storage/' . $tx->receipt_path) }}')"
                                        title="Lihat Foto Struk"
                                        class="p-1 rounded-lg text-slate-400 hover:text-teal-600 hover:bg-teal-50 dark:hover:bg-slate-800 transition"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </button>
                                @endif

                                <button
                                    @click="openEditModal({{ json_encode($tx) }})"
                                    title="Edit Transaksi"
                                    class="p-1 rounded-lg text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 dark:hover:bg-slate-800 transition"
                                >
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                </button>

                                <form method="POST" action="{{ route('transactions.destroy', $tx) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="submit"
                                        title="Hapus Transaksi"
                                        class="p-1 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-slate-800 transition"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="py-8 text-center space-y-2">
                        <div class="w-12 h-12 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center mx-auto text-slate-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                        <p class="text-xs font-semibold text-slate-500">Belum ada transaksi</p>
                        <p class="text-[11px] text-slate-400">Gunakan tombol Suara atau Foto Struk untuk mulai mencatat</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination Links -->
            @if ($transactions->hasPages())
                <div class="pt-3 border-t border-slate-100 dark:divide-slate-800 text-xs">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>

        <!-- ================= MODAL TAMBAH / EDIT TRANSAKSI ================= -->
        <div x-show="isTxModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 py-6 text-center sm:block sm:p-0">
                <div @click="isTxModalOpen = false" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>

                <div class="inline-block align-bottom bg-white dark:bg-slate-900 rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle w-full max-w-md border border-slate-200 dark:border-slate-800">
                    <form :action="txFormUrl" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                        @csrf
                        <input type="hidden" name="_method" :value="txFormMethod">
                        <input type="hidden" name="source" :value="formTx.source">
                        <input type="hidden" name="receipt_path" :value="formTx.receipt_path">

                        <div class="flex items-center justify-between">
                            <h3 class="font-extrabold text-base text-slate-900 dark:text-white" x-text="isEditMode ? 'Edit Transaksi' : 'Catat Transaksi'"></h3>
                            <button type="button" @click="isTxModalOpen = false" class="text-slate-400 hover:text-slate-600">✕</button>
                        </div>

                        <!-- Pilihan Jenis (Pemasukan / Pengeluaran) -->
                        <div class="grid grid-cols-2 gap-2 p-1 bg-slate-100 dark:bg-slate-800 rounded-2xl">
                            <button
                                type="button"
                                @click="formTx.type = 'out'; if (formTx.category === 'Pemasukan') formTx.category = 'Makan & Minum'"
                                :class="formTx.type === 'out' ? 'bg-white dark:bg-slate-700 text-rose-600 dark:text-rose-400 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                                class="py-2 rounded-xl text-xs font-bold transition"
                            >
                                Pengeluaran
                            </button>
                            <button
                                type="button"
                                @click="formTx.type = 'in'; formTx.category = 'Pemasukan'"
                                :class="formTx.type === 'in' ? 'bg-white dark:bg-slate-700 text-emerald-600 dark:text-emerald-400 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                                class="py-2 rounded-xl text-xs font-bold transition"
                            >
                                Pemasukan
                            </button>
                        </div>
                        <input type="hidden" name="type" :value="formTx.type">

                        <!-- Input Nominal (Amount) -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Nominal (Rupiah)</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center font-bold text-sm text-slate-400">Rp</span>
                                <input
                                    type="number"
                                    name="amount"
                                    x-model="formTx.amount"
                                    required
                                    min="1"
                                    placeholder="25000"
                                    class="w-full pl-11 pr-4 py-2.5 rounded-2xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white font-bold text-base focus:ring-emerald-500 focus:border-emerald-500"
                                >
                            </div>
                        </div>

                        <!-- Keterangan -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Keterangan</label>
                            <input
                                type="text"
                                name="description"
                                x-model="formTx.description"
                                required
                                placeholder="Contoh: Makan siang soto ayam"
                                class="w-full px-4 py-2.5 rounded-2xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white text-xs focus:ring-emerald-500 focus:border-emerald-500"
                            >
                        </div>

                        <!-- Kategori -->
                        <div x-show="formTx.type === 'out'">
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Kategori</label>
                            <select
                                name="category"
                                x-model="formTx.category"
                                class="w-full px-4 py-2.5 rounded-2xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white text-xs focus:ring-emerald-500 focus:border-emerald-500"
                            >
                                @foreach ($standardCategories as $cat)
                                    <option value="{{ $cat }}">{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <template x-if="formTx.type === 'in'">
                            <input type="hidden" name="category" value="Pemasukan">
                        </template>

                        <!-- Tanggal & Waktu -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Tanggal & Waktu</label>
                            <input
                                type="datetime-local"
                                name="transacted_at"
                                x-model="formTx.transacted_at"
                                required
                                class="w-full px-4 py-2.5 rounded-2xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white text-xs focus:ring-emerald-500 focus:border-emerald-500"
                            >
                        </div>

                        <!-- Struk Thumbnail / Upload Manual jika perlu -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">Foto Struk (Opsional)</label>
                            <template x-if="formTx.preview_url">
                                <div class="relative w-20 h-20 rounded-xl overflow-hidden border border-slate-200">
                                    <img :src="formTx.preview_url" class="w-full h-full object-cover">
                                    <button type="button" @click="formTx.preview_url = ''; formTx.receipt_path = ''" class="absolute top-1 right-1 bg-black/60 text-white rounded-full p-0.5 text-[9px]">✕</button>
                                </div>
                            </template>
                            <input
                                type="file"
                                name="receipt"
                                accept="image/*"
                                class="block w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200"
                            >
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-2">
                            <button
                                type="submit"
                                class="w-full py-3 rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-bold text-sm shadow-md hover:from-emerald-700 hover:to-teal-700 active:scale-[0.98] transition"
                            >
                                <span x-text="isEditMode ? 'Simpan Perubahan' : 'Simpan Transaksi'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- ================= MODAL ATUR LIMIT ANGGARAN ================= -->
        <div x-show="isBudgetModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 py-6 text-center sm:block sm:p-0">
                <div @click="isBudgetModalOpen = false" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>

                <div class="inline-block align-bottom bg-white dark:bg-slate-900 rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle w-full max-w-md border border-slate-200 dark:border-slate-800 p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="font-extrabold text-base text-slate-900 dark:text-white">Atur Limit Anggaran Bulanan</h3>
                        <button type="button" @click="isBudgetModalOpen = false" class="text-slate-400 hover:text-slate-600">✕</button>
                    </div>
                    <p class="text-xs text-slate-400">Tentukan batas pengeluaran bulanan per kategori. Peringatan akan muncul jika pemakaian melampaui 80%.</p>

                    <form action="{{ route('budgets.update') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Pilih Kategori</label>
                            <select name="category" required class="w-full px-4 py-2.5 rounded-2xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white text-xs focus:ring-emerald-500 focus:border-emerald-500">
                                @foreach ($standardCategories as $cat)
                                    <option value="{{ $cat }}">{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Batas Maksimal Bulanan (Rp)</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center font-bold text-sm text-slate-400">Rp</span>
                                <input type="number" name="monthly_limit" required min="0" placeholder="1000000" class="w-full pl-11 pr-4 py-2.5 rounded-2xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white font-bold text-sm focus:ring-emerald-500 focus:border-emerald-500">
                            </div>
                        </div>

                        <button type="submit" class="w-full py-3 rounded-2xl bg-emerald-600 text-white font-bold text-sm hover:bg-emerald-700 transition">
                            Simpan Anggaran
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- ================= MODAL SALDO AWAL (Jika Belum Ada Transaksi) ================= -->
        @if (! $hasTransactions)
            <div x-data="{ openInitModal: true }" x-show="openInitModal" class="fixed inset-0 z-50 overflow-y-auto">
                <div class="flex items-center justify-center min-h-screen px-4 py-6 text-center">
                    <div class="fixed inset-0 bg-slate-900/70 backdrop-blur-md"></div>
                    <div class="relative inline-block bg-white dark:bg-slate-900 rounded-3xl text-left overflow-hidden shadow-2xl p-6 w-full max-w-sm border border-emerald-500/30 space-y-4">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mx-auto">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div class="text-center space-y-1">
                            <h3 class="font-extrabold text-base text-slate-900 dark:text-white">Selamat Datang di Dompet Arif!</h3>
                            <p class="text-xs text-slate-400">Masukkan saldo awal dompet Anda saat ini untuk memulai pencatatan.</p>
                        </div>
                        <form action="{{ route('transactions.initial-balance') }}" method="POST" class="space-y-3">
                            @csrf
                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 dark:text-slate-300 mb-1">Nominal Saldo Awal (Rp)</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center font-bold text-sm text-slate-400">Rp</span>
                                    <input type="number" name="initial_balance" required min="0" placeholder="500000" class="w-full pl-11 pr-4 py-2.5 rounded-2xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white font-bold text-base focus:ring-emerald-500 focus:border-emerald-500">
                                </div>
                            </div>
                            <button type="submit" class="w-full py-3 rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-bold text-sm shadow-md hover:from-emerald-700 hover:to-teal-700 transition">
                                Simpan Saldo Awal
                            </button>
                            <button type="button" @click="openInitModal = false" class="w-full py-2 text-xs font-semibold text-slate-400 hover:text-slate-600">
                                Lewati untuk sekarang
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        <!-- ================= MODAL LIGHTBOX PREVIEW STRUK ================= -->
        <div x-show="previewReceiptUrl" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm" style="display: none;" @click="previewReceiptUrl = ''">
            <div class="relative max-w-sm max-h-[85vh] overflow-hidden rounded-2xl bg-white dark:bg-slate-900 p-2 shadow-2xl" @click.stop>
                <img :src="previewReceiptUrl" class="max-h-[75vh] w-auto mx-auto rounded-xl object-contain">
                <div class="p-2 text-center">
                    <button type="button" @click="previewReceiptUrl = ''" class="px-4 py-1.5 text-xs font-bold rounded-xl bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                        Tutup Gambar
                    </button>
                </div>
            </div>
        </div>

        <!-- ================= LOADING SPINNER OCR MODAL ================= -->
        <div x-show="isProcessingOcr" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" style="display: none;">
            <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 shadow-2xl flex flex-col items-center gap-3 max-w-xs text-center border border-slate-200 dark:border-slate-800">
                <div class="w-10 h-10 border-4 border-emerald-500 border-t-transparent rounded-full animate-spin"></div>
                <div>
                    <h4 class="font-bold text-sm text-slate-800 dark:text-white">Memproses Foto Struk...</h4>
                    <p class="text-xs text-slate-400 mt-0.5">Sedang mengompres gambar dan membaca data struk</p>
                </div>
            </div>
        </div>

    </div>

    <!-- JAVASCRIPT LOGIC (Web Speech API, Canvas Image Compression, Client Parser) -->
    <script>
        function dompetApp() {
            return {
                isTxModalOpen: false,
                isBudgetModalOpen: false,
                isEditMode: false,
                txFormUrl: "{{ route('transactions.store') }}",
                txFormMethod: 'POST',
                previewReceiptUrl: '',
                isRecording: false,
                recognition: null,
                isProcessingOcr: false,

                formTx: {
                    id: null,
                    type: 'out',
                    description: '',
                    amount: '',
                    category: 'Makan & Minum',
                    transacted_at: '',
                    source: 'manual',
                    receipt_path: '',
                    preview_url: ''
                },

                initApp() {
                    this.setupSpeechRecognition();
                },

                openAddModal() {
                    this.isEditMode = false;
                    this.txFormUrl = "{{ route('transactions.store') }}";
                    this.txFormMethod = 'POST';
                    const now = new Date();
                    now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
                    this.formTx = {
                        id: null,
                        type: 'out',
                        description: '',
                        amount: '',
                        category: 'Makan & Minum',
                        transacted_at: now.toISOString().slice(0, 16),
                        source: 'manual',
                        receipt_path: '',
                        preview_url: ''
                    };
                    this.isTxModalOpen = true;
                },

                openEditModal(tx) {
                    this.isEditMode = true;
                    this.txFormUrl = `/transactions/${tx.id}`;
                    this.txFormMethod = 'PUT';
                    const dt = new Date(tx.transacted_at);
                    dt.setMinutes(dt.getMinutes() - dt.getTimezoneOffset());
                    this.formTx = {
                        id: tx.id,
                        type: tx.type,
                        description: tx.description,
                        amount: tx.amount,
                        category: tx.category,
                        transacted_at: dt.toISOString().slice(0, 16),
                        source: tx.source,
                        receipt_path: tx.receipt_path || '',
                        preview_url: tx.receipt_path ? `/storage/${tx.receipt_path}` : ''
                    };
                    this.isTxModalOpen = true;
                },

                openReceiptPreview(url) {
                    this.previewReceiptUrl = url;
                },

                showToast(message, type = 'success') {
                    const container = document.getElementById('toastContainer');
                    if (!container) return;
                    const toast = document.createElement('div');
                    const isSuccess = type === 'success';
                    toast.className = `p-3.5 rounded-2xl shadow-xl text-xs font-semibold flex items-center justify-between pointer-events-auto transition-all transform duration-300 translate-y-2 opacity-0 ${
                        isSuccess ? 'bg-emerald-600 text-white shadow-emerald-600/30' : 'bg-rose-600 text-white shadow-rose-600/30'
                    }`;
                    toast.innerHTML = `
                        <div class="flex items-center gap-2">
                            <span>${isSuccess ? '✓' : '⚠️'}</span>
                            <span>${message}</span>
                        </div>
                    `;
                    container.appendChild(toast);
                    setTimeout(() => {
                        toast.classList.remove('translate-y-2', 'opacity-0');
                    }, 10);
                    setTimeout(() => {
                        toast.classList.add('opacity-0', 'translate-y-2');
                        setTimeout(() => toast.remove(), 300);
                    }, 4000);
                },

                /* ================= FITUR SUARA (WEB SPEECH API) ================= */
                setupSpeechRecognition() {
                    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
                    if (!SpeechRecognition) {
                        return;
                    }
                    this.recognition = new SpeechRecognition();
                    this.recognition.lang = 'id-ID';
                    this.recognition.continuous = false;
                    this.recognition.interimResults = false;

                    this.recognition.onstart = () => {
                        this.isRecording = true;
                        this.showToast('Mendengarkan ucapan... Silakan bicara.', 'success');
                    };

                    this.recognition.onend = () => {
                        this.isRecording = false;
                    };

                    this.recognition.onerror = (event) => {
                        this.isRecording = false;
                        if (event.error === 'not-allowed') {
                            alert('Izin mikrofon ditolak oleh browser. Mohon izinkan akses mikrofon di pengaturan situs.');
                        } else {
                            this.showToast(`Pengenalan suara gagal: ${event.error}`, 'error');
                        }
                    };

                    this.recognition.onresult = (event) => {
                        const transcript = event.results[0][0].transcript;
                        this.processVoiceInput(transcript);
                    };
                },

                toggleVoiceRecord() {
                    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
                    if (!SpeechRecognition) {
                        alert('Browser Anda tidak mendukung Web Speech API. Silakan gunakan Google Chrome di localhost atau HTTPS.');
                        return;
                    }
                    if (this.isRecording) {
                        this.recognition.stop();
                    } else {
                        try {
                            this.recognition.start();
                        } catch (e) {
                            console.error(e);
                        }
                    }
                },

                /* Client Parser Suara Bahasa Indonesia */
                processVoiceInput(text) {
                    const lower = text.toLowerCase().trim();
                    const parseResult = this.parseIndonesianVoice(lower);

                    if (parseResult.amount && parseResult.amount > 0) {
                        // Kirim langsung lewat fetch POST /transactions dengan source = voice
                        this.showToast(`Terdeteksi: ${parseResult.description} (${this.formatRupiah(parseResult.amount)}). Menyimpan...`);

                        const now = new Date();
                        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());

                        const payload = {
                            type: parseResult.type,
                            description: parseResult.description,
                            amount: parseResult.amount,
                            category: parseResult.category,
                            source: 'voice',
                            transacted_at: now.toISOString().slice(0, 16)
                        };

                        fetch("{{ route('transactions.store') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify(payload)
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                this.showToast('Transaksi suara berhasil disimpan!', 'success');
                                setTimeout(() => window.location.reload(), 1200);
                            } else {
                                this.showToast('Gagal menyimpan transaksi suara', 'error');
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            this.showToast('Terjadi kesalahan jaringan', 'error');
                        });
                    } else {
                        // Nominal tidak terbaca, buka form modal dan minta user mengisi jumlah
                        this.openAddModal();
                        this.formTx.source = 'voice';
                        this.formTx.type = parseResult.type;
                        this.formTx.description = parseResult.description || text;
                        this.formTx.category = parseResult.category;
                        this.showToast('Nominal belum terdeteksi. Silakan masukkan jumlah uang.', 'error');
                    }
                },

                parseIndonesianVoice(rawText) {
                    let text = rawText.trim();
                    let type = 'out';

                    // 1. Deteksi Jenis (Pemasukan vs Pengeluaran)
                    const incomeKeywords = ['gaji', 'terima', 'masuk', 'tambah saldo', 'top up', 'bonus', 'dapat', 'pemasukan', 'cair'];
                    for (const kw of incomeKeywords) {
                        if (text.includes(kw)) {
                            type = 'in';
                            break;
                        }
                    }

                    // 2. Ekstrak Nominal Uang
                    let amount = null;

                    // Support format seperti: "25.000", "50000", "25 rb", "25 ribu", "1 juta", "1,5 juta", "20k"
                    const regexMillion = /(\d+(?:[.,]\d+)?)\s*(?:juta|jt)/i;
                    const regexThousand = /(\d+(?:[.,]\d+)?)\s*(?:ribu|rb|k)/i;
                    const regexPlainNumber = /(?:rp\.?\s*)?(\d{1,3}(?:\.\d{3})+|\d+)/i;

                    if (regexMillion.test(text)) {
                        const match = text.match(regexMillion);
                        const num = parseFloat(match[1].replace(',', '.'));
                        amount = Math.round(num * 1000000);
                        text = text.replace(match[0], '');
                    } else if (regexThousand.test(text)) {
                        const match = text.match(regexThousand);
                        const num = parseFloat(match[1].replace(',', '.'));
                        amount = Math.round(num * 1000);
                        text = text.replace(match[0], '');
                    } else if (regexPlainNumber.test(text)) {
                        const match = text.match(regexPlainNumber);
                        const cleanNum = match[1].replace(/\./g, '');
                        if (parseInt(cleanNum) > 100) {
                            amount = parseInt(cleanNum);
                            text = text.replace(match[0], '');
                        }
                    }

                    // Pembersihan teks keterangan
                    let description = text.replace(/(?:sebesar|seharga|sejumlah|buat|untuk|beli|bayar)/gi, '')
                                         .replace(/\s+/g, ' ')
                                         .trim();
                    if (!description) {
                        description = type === 'in' ? 'Pemasukan' : 'Pengeluaran';
                    }
                    description = description.charAt(0).toUpperCase() + description.slice(1);

                    // 3. Tebak Kategori
                    let category = 'Lainnya';
                    if (type === 'in') {
                        category = 'Pemasukan';
                    } else {
                        const lowerDesc = rawText.toLowerCase();
                        if (/(makan|warteg|soto|nasi|mie|bakso|burger|kopi|teh|minum|cafe|resto|jajan|ayam)/.test(lowerDesc)) {
                            category = 'Makan & Minum';
                        } else if (/(bensin|pertamax|pertalite|ojek|gojek|grab|maxim|parkir|tol|angkot|taksi|kereta|krl|bus)/.test(lowerDesc)) {
                            category = 'Transportasi';
                        } else if (/(belanja|supermarket|minimarket|indomaret|alfamart|pasar|baju|sepatu|toko)/.test(lowerDesc)) {
                            category = 'Belanja';
                        } else if (/(listrik|pln|token|air|pdam|pulsa|kuota|wifi|indihome|internet|bpjs|tagihan|kost)/.test(lowerDesc)) {
                            category = 'Tagihan';
                        } else if (/(nonton|bioskop|game|steam|netflix|spotify|liburan|karaoke|jalan-jalan|wisata)/.test(lowerDesc)) {
                            category = 'Hiburan';
                        }
                    }

                    return { type, amount, description, category };
                },

                formatRupiah(num) {
                    return 'Rp' + (num || 0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                },

                /* ================= FITUR KAMERA STRUK & CANVAS COMPRESSION ================= */
                handleCameraReceiptUpload(event) {
                    const file = event.target.files[0];
                    if (!file) return;

                    this.isProcessingOcr = true;

                    // Kompresi di browser via Canvas: Max 1200px, JPEG kualitas 0.7
                    this.compressImage(file, 1200, 0.7, (compressedBlob) => {
                        const formData = new FormData();
                        formData.append('receipt', compressedBlob, 'receipt.jpg');

                        fetch("{{ route('transactions.scan-receipt') }}", {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json'
                            },
                            body: formData
                        })
                        .then(res => res.json())
                        .then(resData => {
                            this.isProcessingOcr = false;
                            if (resData.success) {
                                this.openAddModal();
                                this.formTx.source = 'camera';
                                this.formTx.type = 'out';
                                this.formTx.receipt_path = resData.receipt_path;
                                this.formTx.preview_url = resData.receipt_url;

                                const info = resData.data || {};
                                if (info.total) {
                                    this.formTx.amount = info.total;
                                }
                                if (info.merchant) {
                                    this.formTx.description = `Belanja di ${info.merchant}`;
                                    this.formTx.category = 'Belanja';
                                }
                                if (info.date) {
                                    this.formTx.transacted_at = `${info.date}T12:00`;
                                }

                                this.showToast('Foto struk berhasil diproses. Silakan konfirmasi data sebelum simpan.');
                            } else {
                                this.showToast('Gagal memproses struk', 'error');
                            }
                        })
                        .catch(err => {
                            this.isProcessingOcr = false;
                            console.error(err);
                            this.showToast('Gagal mengunggah foto struk', 'error');
                        });
                    });
                },

                compressImage(file, maxDimension, quality, callback) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        const img = new Image();
                        img.onload = () => {
                            let width = img.width;
                            let height = img.height;

                            if (width > maxDimension || height > maxDimension) {
                                if (width > height) {
                                    height = Math.round((height * maxDimension) / width);
                                    width = maxDimension;
                                } else {
                                    width = Math.round((width * maxDimension) / height);
                                    height = maxDimension;
                                }
                            }

                            const canvas = document.createElement('canvas');
                            canvas.width = width;
                            canvas.height = height;
                            const ctx = canvas.getContext('2d');
                            ctx.drawImage(img, 0, 0, width, height);

                            canvas.toBlob((blob) => {
                                callback(blob);
                            }, 'image/jpeg', quality);
                        };
                        img.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            };
        }
    </script>
</x-app-layout>
