<?php

namespace Database\Factories;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Transaction>
 */
class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categoriesOut = ['Makan & Minum', 'Transportasi', 'Belanja', 'Tagihan', 'Hiburan', 'Lainnya'];
        $type = fake()->randomElement(['in', 'out']);

        return [
            'user_id' => User::factory(),
            'type' => $type,
            'description' => $type === 'in' ? fake()->randomElement(['Gaji Bulanan', 'Freelance Project', 'Bonus Kinerja', 'Cashback']) : fake()->randomElement(['Makan Siang Soto', 'Bensin Motor', 'Kopi Susu Senja', 'Belanja Mingguan', 'Tagihan Listrik PLN', 'Nonton Bioskop']),
            'amount' => $type === 'in' ? fake()->numberBetween(500000, 5000000) : fake()->numberBetween(15000, 250000),
            'category' => $type === 'in' ? 'Pemasukan' : fake()->randomElement($categoriesOut),
            'receipt_path' => null,
            'source' => fake()->randomElement(['manual', 'voice', 'camera']),
            'transacted_at' => fake()->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
