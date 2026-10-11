<?php

namespace App\Services;

interface ReceiptReader
{
    /**
     * Membaca informasi struk dari file path yang tersimpan.
     *
     * @param  string  $path  Path file fisik atau relative storage
     * @return array{total: ?int, merchant: ?string, date: ?string}
     */
    public function read(string $path): array;
}
