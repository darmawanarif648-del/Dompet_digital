<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\Transaction;
use App\Services\ReceiptReader;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TransactionController extends Controller
{
    use AuthorizesRequests;

    /**
     * Simpan transaksi baru ke database.
     */
    public function store(StoreTransactionRequest $request): JsonResponse|RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        // Handle upload struk langsung jika ada
        if ($request->hasFile('receipt')) {
            $path = $request->file('receipt')->store("receipts/{$user->id}", 'public');
            $validated['receipt_path'] = $path;
        }

        $transaction = $user->transactions()->create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil dicatat!',
                'data' => $transaction,
            ], 201);
        }

        return redirect()->route('dashboard')->with('status', 'Transaksi berhasil disimpan!');
    }

    /**
     * Ambil data transaksi spesifik untuk edit (JSON).
     */
    public function show(Transaction $transaction): JsonResponse
    {
        $this->authorize('view', $transaction);

        return response()->json([
            'success' => true,
            'data' => $transaction,
        ]);
    }

    /**
     * Perbarui data transaksi.
     */
    public function update(UpdateTransactionRequest $request, Transaction $transaction): JsonResponse|RedirectResponse
    {
        $this->authorize('update', $transaction);

        $validated = $request->validated();

        if ($request->hasFile('receipt')) {
            if ($transaction->receipt_path && Storage::disk('public')->exists($transaction->receipt_path)) {
                Storage::disk('public')->delete($transaction->receipt_path);
            }
            $path = $request->file('receipt')->store("receipts/{$request->user()->id}", 'public');
            $validated['receipt_path'] = $path;
        }

        $transaction->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil diperbarui!',
                'data' => $transaction,
            ]);
        }

        return redirect()->route('dashboard')->with('status', 'Transaksi berhasil diperbarui!');
    }

    /**
     * Hapus transaksi.
     */
    public function destroy(Request $request, Transaction $transaction): JsonResponse|RedirectResponse
    {
        $this->authorize('delete', $transaction);

        if ($transaction->receipt_path && Storage::disk('public')->exists($transaction->receipt_path)) {
            Storage::disk('public')->delete($transaction->receipt_path);
        }

        $transaction->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil dihapus!',
            ]);
        }

        return redirect()->route('dashboard')->with('status', 'Transaksi berhasil dihapus!');
    }

    /**
     * Endpoint pemindaian foto struk sebelum disimpan (Upload & OCR).
     */
    public function scanReceipt(Request $request, ReceiptReader $reader): JsonResponse
    {
        $request->validate([
            'receipt' => 'required|image|mimes:jpeg,jpg,png,webp|max:4096',
        ], [
            'receipt.required' => 'Foto struk wajib diunggah.',
            'receipt.image' => 'File harus berupa gambar.',
            'receipt.max' => 'Ukuran file gambar maksimal 4MB.',
        ]);

        $user = $request->user();
        $path = $request->file('receipt')->store("receipts/{$user->id}", 'public');

        // Panggil ReceiptReader
        $readResult = $reader->read($path);

        return response()->json([
            'success' => true,
            'receipt_path' => $path,
            'receipt_url' => asset('storage/'.$path),
            'data' => $readResult,
        ]);
    }

    /**
     * Simpan saldo awal pengguna baru.
     */
    public function setInitialBalance(Request $request): RedirectResponse
    {
        $request->validate([
            'initial_balance' => 'required|integer|min:0',
        ], [
            'initial_balance.required' => 'Saldo awal wajib diisi.',
            'initial_balance.integer' => 'Saldo awal harus berupa angka bulat rupiah.',
        ]);

        $amount = (int) $request->input('initial_balance');
        if ($amount > 0) {
            $request->user()->transactions()->create([
                'type' => 'in',
                'description' => 'Saldo Awal',
                'amount' => $amount,
                'category' => 'Pemasukan',
                'source' => 'manual',
                'transacted_at' => Carbon::now(),
            ]);
        }

        return redirect()->route('dashboard')->with('status', 'Saldo awal berhasil ditambahkan!');
    }

    /**
     * Ekspor daftar transaksi pengguna ke format CSV.
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $user = $request->user();
        $query = $user->transactions()->orderBy('transacted_at', 'desc');

        if ($request->filled('month')) {
            $carbonMonth = Carbon::createFromFormat('Y-m', $request->input('month'));
            if ($carbonMonth) {
                $query->whereYear('transacted_at', $carbonMonth->year)
                    ->whereMonth('transacted_at', $carbonMonth->month);
            }
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        $transactions = $query->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="transaksi_dompet_arif_'.date('Ymd_His').'.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($transactions) {
            $output = fopen('php://output', 'w');
            // Menulis UTF-8 BOM agar Excel menampilkan aksen dan format dengan benar
            fwrite($output, "\xEF\xBB\xBF");

            fputcsv($output, ['ID', 'Tanggal & Waktu', 'Jenis', 'Kategori', 'Keterangan', 'Nominal (Rp)', 'Sumber', 'Ada Struk']);

            foreach ($transactions as $tx) {
                fputcsv($output, [
                    $tx->id,
                    $tx->transacted_at->format('Y-m-d H:i:s'),
                    $tx->type === 'in' ? 'Pemasukan' : 'Pengeluaran',
                    $tx->category,
                    $tx->description,
                    $tx->amount,
                    $tx->source,
                    $tx->receipt_path ? 'Ya' : 'Tidak',
                ]);
            }

            fclose($output);
        };

        return response()->stream($callback, 200, $headers);
    }
}
