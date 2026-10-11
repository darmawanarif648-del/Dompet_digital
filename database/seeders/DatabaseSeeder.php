<?php

namespace Database\Seeders;

use App\Models\Budget;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'arif@dompetarif.test'],
            [
                'name' => 'Arif Wibowo',
                'password' => Hash::make('password'),
            ]
        );

        // Buat anggaran bulanan
        $budgets = [
            ['category' => 'Makan & Minum', 'monthly_limit' => 2000000],
            ['category' => 'Transportasi', 'monthly_limit' => 750000],
            ['category' => 'Belanja', 'monthly_limit' => 1200000],
            ['category' => 'Tagihan', 'monthly_limit' => 1000000],
            ['category' => 'Hiburan', 'monthly_limit' => 500000],
            ['category' => 'Lainnya', 'monthly_limit' => 500000],
        ];

        foreach ($budgets as $b) {
            Budget::updateOrCreate(
                ['user_id' => $user->id, 'category' => $b['category']],
                ['monthly_limit' => $b['monthly_limit']]
            );
        }

        // Hapus transaksi seeder lama jika ada
        Transaction::where('user_id', $user->id)->delete();

        // Buat data transaksi contoh
        $now = Carbon::now();

        $sampleTransactions = [
            [
                'type' => 'in',
                'description' => 'Saldo Awal Dompet',
                'amount' => 5000000,
                'category' => 'Pemasukan',
                'source' => 'manual',
                'transacted_at' => $now->copy()->startOfMonth(),
            ],
            [
                'type' => 'in',
                'description' => 'Gaji Bulanan',
                'amount' => 8500000,
                'category' => 'Pemasukan',
                'source' => 'manual',
                'transacted_at' => $now->copy()->startOfMonth()->addDays(2),
            ],
            [
                'type' => 'out',
                'description' => 'Makan Siang Nasi Padang',
                'amount' => 35000,
                'category' => 'Makan & Minum',
                'source' => 'voice',
                'transacted_at' => $now->copy()->subDays(3)->setHour(12)->setMinute(30),
            ],
            [
                'type' => 'out',
                'description' => 'Kopi Kenangan Senja',
                'amount' => 24000,
                'category' => 'Makan & Minum',
                'source' => 'manual',
                'transacted_at' => $now->copy()->subDays(2)->setHour(16)->setMinute(15),
            ],
            [
                'type' => 'out',
                'description' => 'Bensin Pertamax Motor',
                'amount' => 50000,
                'category' => 'Transportasi',
                'source' => 'manual',
                'transacted_at' => $now->copy()->subDays(2)->setHour(8)->setMinute(10),
            ],
            [
                'type' => 'out',
                'description' => 'Belanja Supermarket Bulanan',
                'amount' => 450000,
                'category' => 'Belanja',
                'source' => 'camera',
                'transacted_at' => $now->copy()->subDays(1)->setHour(19)->setMinute(45),
            ],
            [
                'type' => 'out',
                'description' => 'Tagihan Listrik PLN',
                'amount' => 380000,
                'category' => 'Tagihan',
                'source' => 'manual',
                'transacted_at' => $now->copy()->subHours(10),
            ],
            [
                'type' => 'out',
                'description' => 'Nonton Bioskop IMAX',
                'amount' => 110000,
                'category' => 'Hiburan',
                'source' => 'voice',
                'transacted_at' => $now->copy()->subHours(4),
            ],
        ];

        foreach ($sampleTransactions as $tx) {
            Transaction::create(array_merge($tx, ['user_id' => $user->id]));
        }
    }
}
