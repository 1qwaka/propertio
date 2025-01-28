<?php

namespace Database\Seeders;

use App\Models\Agent;
use Faker\Generator;
use Illuminate\Container\Container;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AgentSeeder extends Seeder
{
    protected $faker;

    public function __construct()
    {
        $this->faker = Container::getInstance()->make(Generator::class);;
    }
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
//        Agent::factory(5)->create();

        $createCount = 10;

        $availableUserIds = DB::table('users')
            ->whereNotIn('id', function ($query) {
                $query->select('user_id')->from('agents');
            })
            ->pluck('id')
            ->toArray();

        if (count($availableUserIds) < $createCount) {
            throw new \Exception("no available users to become agents");
        }

        shuffle($availableUserIds);

        for ($i = 0; $i < $createCount; $i++) {
            $typeId = DB::table('agent_type')->inRandomOrder()->first()->id;

            Agent::create([
                'user_id' => $availableUserIds[$i],
                'type_id' => $typeId,
                'name' => $this->faker->name,
                'address' => $this->faker->address,
                'email' => $this->faker->unique()->safeEmail,
            ]);
        }


    }
}
