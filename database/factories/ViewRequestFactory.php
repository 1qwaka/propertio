<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ViewRequest>
 */
class ViewRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $property_id = DB::table('properties')->inRandomOrder()->first()->id;
        $user_id = DB::table('users')->inRandomOrder()->first()->id;

        return [
            'status' => $this->faker->randomElement(['open', 'accepted', 'rejected']),
            'date' => $this->faker->date(),
            'property_id' => $property_id,
            'user_id' => $user_id,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
