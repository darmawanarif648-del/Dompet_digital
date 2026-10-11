<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman utama (dashboard dompet digital).
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $now = Carbon::now();

        // 1. Saldo Utama Keseluruhan
        $totalIn = (int) $user->transactions()->where('type', 'in')->sum('amount');
        $totalOut = (int) $user->transactions()->where('type', 'out')->sum('amount');
        $mainBalance = $totalIn - $totalOut;

        // 2. Ringkasan Bulan Berjalan
        $currentYear = $now->year;
        $currentMonth = $now->month;

        $monthlyIn = (int) $user->transactions()
            ->where('type', 'in')
            ->whereYear('transacted_at', $currentYear)
            ->whereMonth('transacted_at', $currentMonth)
            ->sum('amount');

        $monthlyOut = (int) $user->transactions()
            ->where('type', 'out')
            ->whereYear('transacted_at', $currentYear)
            ->whereMonth('transacted_at', $currentMonth)
            ->sum('amount');

        // 3. Kategori Pengeluaran & Anggaran
        $standardCategories = [
            'Makan & Minum',
            'Transportasi',
            'Belanja',
            'Tagihan',
            'Hiburan',
            'Lainnya',
        ];

        $budgets = $user->budgets()->get()->keyBy('category');

        $categoryExpenses = [];
        foreach ($standardCategories as $cat) {
            $catAmount = (int) $user->transactions()
                ->where('type', 'out')
                ->where('category', $cat)
                ->whereYear('transacted_at', $currentYear)
                ->whereMonth('transacted_at', $currentMonth)
                ->sum('amount');

            $catPercentage = $monthlyOut > 0 ? round(($catAmount / $monthlyOut) * 100, 1) : 0;

            $budgetLimit = isset($budgets[$cat]) ? $budgets[$cat]->monthly_limit : 0;
            $budgetUsagePercent = $budgetLimit > 0 ? round(($catAmount / $budgetLimit) * 100, 1) : 0;
            $isBudgetExceeded = $budgetLimit > 0 && $budgetUsagePercent >= 80;

            $categoryExpenses[] = [
                'name' => $cat,
                'amount' => $catAmount,
                'percentage' => $catPercentage,
                'budget_limit' => $budgetLimit,
                'budget_usage_percent' => $budgetUsagePercent,
                'is_warning' => $isBudgetExceeded,
            ];
        }

        // 4. Filter Riwayat Transaksi
        $txQuery = $user->transactions()->orderBy('transacted_at', 'desc');

        $filterMonth = $request->query('month');
        if ($filterMonth) {
            try {
                $monthDate = Carbon::createFromFormat('Y-m', $filterMonth);
                $txQuery->whereYear('transacted_at', $monthDate->year)
                    ->whereMonth('transacted_at', $monthDate->month);
            } catch (\Exception $e) {
                // Abaikan jika format tidak valid
            }
        }

        $filterCategory = $request->query('category');
        if ($filterCategory) {
            $txQuery->where('category', $filterCategory);
        }

        $transactions = $txQuery->paginate(10)->withQueryString();

        // 5. Cek apakah user pertama kali masuk (belum ada transaksi)
        $hasTransactions = $user->transactions()->exists();

        return view('dashboard', [
            'mainBalance' => $mainBalance,
            'monthlyIn' => $monthlyIn,
            'monthlyOut' => $monthlyOut,
            'categoryExpenses' => $categoryExpenses,
            'transactions' => $transactions,
            'standardCategories' => $standardCategories,
            'filterMonth' => $filterMonth,
            'filterCategory' => $filterCategory,
            'hasTransactions' => $hasTransactions,
            'user' => $user,
        ]);
    }
}
