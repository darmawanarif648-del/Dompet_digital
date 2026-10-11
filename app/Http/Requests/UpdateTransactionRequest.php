<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('transaction'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type' => 'required|in:in,out',
            'description' => 'required|string|max:255',
            'amount' => 'required|integer|min:1',
            'category' => 'required|string|max:100',
            'transacted_at' => 'required|date',
            'receipt' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:4096',
        ];
    }

    /**
     * Custom messages for validation errors in Indonesian.
     */
    public function messages(): array
    {
        return [
            'type.required' => 'Jenis transaksi (Pemasukan/Pengeluaran) wajib dipilih.',
            'description.required' => 'Keterangan transaksi wajib diisi.',
            'amount.required' => 'Nominal jumlah uang wajib diisi.',
            'amount.integer' => 'Nominal harus berupa angka bulat dalam rupiah.',
            'amount.min' => 'Nominal minimal Rp1.',
            'category.required' => 'Kategori transaksi wajib dipilih.',
            'transacted_at.required' => 'Tanggal & waktu transaksi wajib ditentukan.',
            'receipt.image' => 'File struk harus berupa gambar yang valid.',
            'receipt.max' => 'Ukuran foto struk maksimal 4MB.',
        ];
    }
}
