<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BudgetController extends Controller
{
    /**
     * Simpan atau perbarui limit anggaran per kategori.
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'category' => 'required|string|max:100',
            'monthly_limit' => 'required|integer|min:0',
        ], [
            'category.required' => 'Kategori wajib dipilih.',
            'monthly_limit.required' => 'Batas anggaran bulanan wajib diisi.',
            'monthly_limit.integer' => 'Batas anggaran harus berupa angka bulat rupiah.',
        ]);

        $request->user()->budgets()->updateOrCreate(
            ['category' => $request->input('category')],
            ['monthly_limit' => (int) $request->input('monthly_limit')]
        );

        return redirect()->route('dashboard')->with('status', 'Batas anggaran berhasil diperbarui!');
    }
}
