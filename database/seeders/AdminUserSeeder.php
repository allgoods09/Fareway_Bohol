<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@farewaybohol.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '09991234567',
        ]);

        User::create([
            'name' => 'Moderator User',
            'email' => 'moderator@farewaybohol.com',
            'password' => Hash::make('password'),
            'role' => 'moderator',
            'phone' => '09991234568',
        ]);
    }
}