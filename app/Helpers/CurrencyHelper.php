<?php

namespace App\Helpers;

class CurrencyHelper
{
    /**
     * Format integer amount to Indonesian Rupiah (contoh: Rp25.000).
     */
    public static function rupiah(int|float|null $amount): string
    {
        $amount = (int) ($amount ?? 0);

        return 'Rp'.number_format($amount, 0, ',', '.');
    }
}
