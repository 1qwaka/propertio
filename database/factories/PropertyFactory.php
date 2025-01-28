<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Property>
 */
class PropertyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $building = DB::table('buildings')->inRandomOrder()->first();
        $floor_type_id = DB::table('floor_type')->inRandomOrder()->first()->id;
        $agent_id = DB::table('agents')->inRandomOrder()->first()->id;

        return [
            'building_id' => $building->id,
            'floor' => $this->faker->numberBetween(1, $building->floors),
            'area' => $this->faker->numberBetween(20, 200),
            'floor_type_id' => $floor_type_id,
            'address' => $this->faker->address(),
            'living_space_type' => $this->faker->randomElement(['primary', 'secondary']),
            'agent_id' => $agent_id,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
