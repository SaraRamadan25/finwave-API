<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;


class StatisticFactory extends Factory
{

    public function definition(): array
    {
        return [
            'category_percentage'=>fake()->randomFloat(2, 0, 100),
            'category_id'=>Category::factory(),
        ];
    }
}
