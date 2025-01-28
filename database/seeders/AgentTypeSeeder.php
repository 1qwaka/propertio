<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AgentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('agent_type')->insert([
            ['name' => 'Физическое лицо'],
            ['name' => 'Юридическое лицо'],
            ['name' => 'Риелтор'],
            ['name' => 'Застройщик'],
            ['name' => 'Агенство'],
            ['name' => 'Собственник'],
        ]);
    }
}
