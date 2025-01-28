<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FloorTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('floor_type')->insert([
            ['name' => 'Дом'],
            ['name' => 'Квартира'],
            ['name' => 'Часть дома'],
            ['name' => 'Часть квартиры'],
        ]);
    }
}
