<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\StockIn;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(LoginUserSeeder::class);

        $admin = User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Admin Dalwa',
                'email' => 'admin@dalwa-water.local',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'status' => 'active',
            ]
        );

        $cashier = User::updateOrCreate(
            ['username' => 'kasir'],
            [
                'name' => 'Ahmad Fauzi',
                'email' => 'kasir@dalwa-water.local',
                'password' => Hash::make('password'),
                'role' => 'cashier',
                'status' => 'active',
            ]
        );
    }
}
