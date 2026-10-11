<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AiReceiptReader implements ReceiptReader
{
    /**
     * Membaca informasi struk dengan bantuan Vision AI API.
     * Menggunakan API key server-side dari .env (tidak bocor ke client).
     *
     * @return array{total: ?int, merchant: ?string, date: ?string}
     */
    public function read(string $path): array
    {
        $default = [
            'total' => null,
            'merchant' => null,
            'date' => null,
        ];

        $apiKey = env('GEMINI_API_KEY') ?? env('VISION_API_KEY');
        if (! $apiKey) {
            Log::warning('AiReceiptReader: API key tidak ditemukan di .env (GEMINI_API_KEY/VISION_API_KEY).');

            return $default;
        }

        // Cari file fisik di storage
        $fullPath = Storage::disk('public')->exists($path)
            ? Storage::disk('public')->path($path)
            : (file_exists($path) ? $path : null);

        if (! $fullPath || ! file_exists($fullPath)) {
            Log::warning("AiReceiptReader: File struk tidak ditemukan pada path: {$path}");

            return $default;
        }

        try {
            $mimeType = mime_content_type($fullPath) ?: 'image/jpeg';
            $base64Image = base64_encode(file_get_contents($fullPath));

            $prompt = 'Kamu adalah asisten OCR struk belanja. Analisis gambar struk ini dan ekstrak informasi berikut dalam format JSON murni tanpa markdown/backticks:
{
  "total": integer nominal total belanja dalam rupiah (contoh: 45000),
  "merchant": string nama toko atau merchant (contoh: "Alfamart"),
  "date": string tanggal transaksi format YYYY-MM-DD (contoh: "2026-10-11")
}
Jika salah satu nilai tidak terbaca atau tidak yakin, beri nilai null untuk properti tersebut.';

            $response = Http::timeout(25)->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt],
                            [
                                'inline_data' => [
                                    'mime_type' => $mimeType,
                                    'data' => $base64Image,
                                ],
                            ],
                        ],
                    ],
                ],
                'generationConfig' => [
                    'response_mime_type' => 'application/json',
                ],
            ]);

            if ($response->successful()) {
                $content = $response->json('candidates.0.content.parts.0.text');
                if ($content) {
                    $parsed = json_decode($content, true);
                    if (is_array($parsed)) {
                        return [
                            'total' => isset($parsed['total']) && is_numeric($parsed['total']) ? (int) $parsed['total'] : null,
                            'merchant' => ! empty($parsed['merchant']) ? (string) $parsed['merchant'] : null,
                            'date' => ! empty($parsed['date']) ? (string) $parsed['date'] : null,
                        ];
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::error('AiReceiptReader exception: '.$e->getMessage());
        }

        return $default;
    }
}
