<?php

namespace App\Services;

class NullReceiptReader implements ReceiptReader
{
    /**
     * Implementasi default NullReceiptReader.
     * Tidak membaca data apa-apa, meminta pengguna mengisi nominal manual.
     *
     * @return array{total: ?int, merchant: ?string, date: ?string}
     */
    public function read(string $path): array
    {
        return [
            'total' => null,
            'merchant' => null,
            'date' => null,
        ];
    }
}
