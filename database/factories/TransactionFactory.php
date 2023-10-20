<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Report;
use Illuminate\Database\Eloquent\Factories\Factory;


class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'transaction_content'=>fake()->text(200),
            'category_id'=>Category::factory(),
        ];
    }
}
