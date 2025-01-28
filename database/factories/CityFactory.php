<?php

namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\City>
 */
class CityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'population' => fake()->numberBetween(1000, 10e6),
            'area' => fake()->numberBetween(10, 1e7),
            'name' => fake()->city(),
            'rating' => fake()->randomFloat(2, 0, 5),
        ];
    }
}
