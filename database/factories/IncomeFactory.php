<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class IncomeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title'=>fake()->sentence,
            'description'=>fake()->text(200),
            'amount_of_money'=>fake()->numberBetween(1000,100000),
            'user_id'=>User::factory(),
        ];
    }
}
