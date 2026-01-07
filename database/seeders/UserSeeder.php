<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@toko.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Create Staff User
        User::create([
            'name' => 'Staff Member',
            'email' => 'staff@toko.com',
            'password' => Hash::make('password123'),
            'role' => 'staff',
            'email_verified_at' => now(),
        ]);

        // Create Guest User
        User::create([
            'name' => 'Guest User',
            'email' => 'guest@toko.com',
            'password' => Hash::make('password123'),
            'role' => 'guest',
            'email_verified_at' => now(),
        ]);
    }
}
