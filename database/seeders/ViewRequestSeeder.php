<?php

namespace Database\Seeders;

use App\Models\ViewRequest;
use Illuminate\Database\Seeder;

class ViewRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ViewRequest::factory(10)->create();
    }
}
