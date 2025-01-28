<?php

namespace Database\Seeders;

use App\Models\Agent;
use App\Models\User;
use Illuminate\Database\Seeder;

class DefaultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $admin = User::where('email', 'admin@propert.io')->get()->first();
        if ($admin == null) {
            $admin = User::create([
                'name' => 'admin',
                'is_admin' => true,
                'email' => 'admin@propert.io',
                'password' => 'admin',
            ]);
            Agent::create([
                'type_id' => 1,
                'name' => 'admin',
                'address' => 'propert.io',
                'email' => 'admin@propert.io',
                'user_id' => $admin->id,
            ]);
        }

        User::create([
            'name' => 'Vasily',
            'email' => 'vas123@mail.ru',
            'password' => '1995abcabc'
        ]);

        $user = User::create([
            'name' => 'Dmitry Ovchinov',
            'email' => 'ovchinov.agent@mail.ru',
            'password' => 'qwertyuiop'
        ]);

        Agent::create([
            'type_id' => 3,
            'name' => 'Dmitry Ovchinov',
            'address' => 'Moscow, lenina 12',
            'email' => 'ovchinov.agent@mail.ru',
            'user_id' => $user->id,
        ]);
    }
}
