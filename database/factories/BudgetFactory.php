<?php

namespace Database\Factories;

use App\Models\Budget;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Budget>
 */
class BudgetFactory extends Factory
{
    protected $model = Budget::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'category' => fake()->randomElement(['Makan & Minum', 'Transportasi', 'Belanja', 'Tagihan', 'Hiburan', 'Lainnya']),
            'monthly_limit' => fake()->numberBetween(500000, 3000000),
        ];
    }
}
