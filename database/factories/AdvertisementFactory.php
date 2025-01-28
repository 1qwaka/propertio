<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Advertisement>
 */
class AdvertisementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */


    public function definition(): array
    {
        $agent_id = DB::table('agents')->inRandomOrder()->first()->id;
        $property_id = DB::table('properties')->inRandomOrder()->first()->id;

        return [
            'agent_id' => $agent_id,
            'description' => $this->faker->text(),
            'price' => $this->faker->numberBetween(50000, 500000),
            'property_id' => $property_id,
            'type' => $this->faker->randomElement(['sell', 'rent']),
            'hidden' => $this->faker->boolean(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }


}

