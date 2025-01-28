<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();


        $this->call([
//            CitySeeder::class,
            AgentTypeSeeder::class,
            AgentSeeder::class,
            DeveloperSeeder::class,
//            DistrictSeeder::class,
            BuildingTypeSeeder::class,
            BuildingSeeder::class,
            FloorTypeSeeder::class,
            PropertySeeder::class,
            AdvertisementSeeder::class,
            ViewRequestSeeder::class,
//            ContractSeeder::class,
            DefaultSeeder::class,
        ]);
    }
}
