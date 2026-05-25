<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class LoginUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Admin Dalwa',
                'email' => 'admin@dalwa-water.local',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'status' => 'active',
            ]
        );

        User::updateOrCreate(
            ['username' => 'kasir'],
            [
                'name' => 'Kasir Dalwa',
                'email' => 'kasir@dalwa-water.local',
                'password' => Hash::make('password'),
                'role' => 'cashier',
                'status' => 'active',
            ]
        );
    }
}
