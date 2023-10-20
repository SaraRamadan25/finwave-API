<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CommunityFactory extends Factory
{

    public function definition(): array
    {
        return [
            'name'=>fake()->word,
            'number_of_people'=>fake()->numberBetween(2,20),
        ];
    }
}
