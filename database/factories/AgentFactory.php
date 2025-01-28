<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Agent>
 */
class AgentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Получаем случайный ID из таблицы agent_type
        $typeId = DB::table('agent_type')->inRandomOrder()->first()->id;
        $userId = DB::table('users')->inRandomOrder()->first()->id;

        return [
            'type_id' => $typeId,
            'name' => $this->faker->name(),
            'address' => $this->faker->address(),
            'email' => $this->faker->unique()->safeEmail(),
            'user_id' => $userId,
//            'created_at' => now(),
//            'updated_at' => now(),
        ];
    }
}
