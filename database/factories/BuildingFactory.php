<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Building>
 */
class BuildingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type_id = DB::table('building_type')->inRandomOrder()->first()->id;
        $developer_id = DB::table('developers')->inRandomOrder()->first()->id;

        return [
            'type_id' => $type_id,
            'hot_water' => $this->faker->boolean(),
            'gas' => $this->faker->boolean(),
            'elevators' => $this->faker->numberBetween(0, 10),
            'floors' => $this->faker->numberBetween(1, 50),
            'build_year' => $this->faker->year(),
            'developer_id' => $developer_id,
            'address' => $this->faker->address(),
        ];
    }
}
