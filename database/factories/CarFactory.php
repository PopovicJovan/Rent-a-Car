<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Car>
 */
class CarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => $this->faker->word,
            'brand' => $this->faker->word,
            'price' => $this->faker->numberBetween(10000, 50000),
            'description' => $this->faker->paragraph,
            'fuelType' => $this->faker->word,
            'image' => Str::random(10)
        ];
    }
}
