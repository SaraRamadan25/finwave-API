<?php

namespace Database\Factories;

use App\Models\Investment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvestmentFactory extends Factory
{
    protected $model = Investment::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'price' => $this->faker->numberBetween(0, 100000) / 100,
            'image' => json_encode(['key1' => 'value1', 'key2' => 'value2']),
            'description' => $this->faker->text(200),
            'user_id' => User::factory(),
        ];
    }
}
