<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class GoalFactory extends Factory
{

    public function definition(): array
    {
        return [
            'title'=>fake()->sentence,
            'description'=>fake()->text(200),
            'amount_of_money'=>fake()->numberBetween(0, 100000) / 100,
            'money_limit'=>fake()->numberBetween(0, 100000) / 100,
            'user_id'=>User::factory(),
        ];
    }
}
