<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class purchaseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'=>fake()->name(),
            'quantity'=>fake()->numberBetween(1,10),
            'item_price'=>fake()->numberBetween(0, 100000) / 100,
            'image'=>fake()->imageUrl(),
            'user_id'=>User::factory(),
            'category_id'=>Category::factory(),
            'transaction_id'=>Transaction::factory(),
        ];
    }
}
