<?php

namespace Database\Factories;

use Faker\Provider\FakeCar;
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
        $this->faker->addProvider(new FakeCar($this->faker));
        $vehicle = $this->faker->vehicleArray();
        return [
            'type' => $this->faker->vehicleType,
            'brand' => $vehicle['brand'],
            'price' => $this->faker->numberBetween(1, 300),
            'description' => $this->faker->paragraph,
            'fuelType' => $this->faker->vehicleFuelType,
            'image' => "abc"
        ];
    }
}
