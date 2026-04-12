<?php
// database/seeders/DemoUserSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DemoUserSeeder extends Seeder
{
    public function run(): void
    {
        // Regular users
        $users = [
            [
                'name' => 'Juan Dela Cruz',
                'email' => 'juan@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'phone' => '09123456789',
            ],
            [
                'name' => 'Maria Santos',
                'email' => 'maria@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'phone' => '09123456788',
            ],
            [
                'name' => 'Pedro Reyes',
                'email' => 'pedro@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'phone' => '09123456787',
            ],
            [
                'name' => 'Ana Flores',
                'email' => 'ana@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'phone' => '09123456786',
            ],
            [
                'name' => 'Carlos Mendoza',
                'email' => 'carlos@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'phone' => '09123456785',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}