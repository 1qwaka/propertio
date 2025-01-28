<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BuildingTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('building_type')->insert([
            ['name' => 'Кирпичный'],
            ['name' => 'Панельный'],
            ['name' => 'Блочный'],
            ['name' => 'Монолитный'],
            ['name' => 'Кирпично-монолитный'],
            ['name' => 'Газобетонный'],
            ['name' => 'Пенобетонный'],
            ['name' => 'Деревянный'],
        ]);
    }
}
