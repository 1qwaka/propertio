<?php

namespace database\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\District>
 */
class DistrictFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $city_id = DB::table('cities')->inRandomOrder()->first()->id;

        return [
            'city_id' => $city_id,
            'population' => $this->faker->numberBetween(1000, 100000),
            'area' => $this->faker->numberBetween(5, 500),
            'name' => $this->faker->streetName(),
            'rating' => $this->faker->randomFloat(2, 0, 5),
        ];
    }
}
