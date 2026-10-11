<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'description',
        'amount',
        'category',
        'receipt_path',
        'source',
        'transacted_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'amount' => 'integer',
            'transacted_at' => 'datetime',
        ];
    }

    /**
     * Relasi ke model User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope untuk transaksi masuk.
     */
    public function scopeIn($query)
    {
        return $query->where('type', 'in');
    }

    /**
     * Scope untuk transaksi keluar.
     */
    public function scopeOut($query)
    {
        return $query->where('type', 'out');
    }

    /**
     * Scope filter bulan tertentu (contoh: YYYY-MM).
     */
    public function scopeForMonth($query, $year, $month)
    {
        return $query->whereYear('transacted_at', $year)
            ->whereMonth('transacted_at', $month);
    }
}
