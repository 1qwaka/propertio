<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contract>
 */
class ContractFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $property_id = DB::table('properties')->inRandomOrder()->first()->id;
        $buyer_id = DB::table('users')->inRandomOrder()->first()->id;
        $agent_id = DB::table('agents')->inRandomOrder()->first()->id;

        return [
            'property_id' => $property_id,
            'status' => $this->faker->randomElement(['open', 'accepted', 'rejected']),
            'date' => $this->faker->date(),
            'price' => $this->faker->numberBetween(100000, 1000000),
            'buyer_id' => $buyer_id,
            'agent_id' => $agent_id,
            'until' => $this->faker->date(),
            'buyer_agreement' => $this->faker->boolean(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
