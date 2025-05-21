<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'admin',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'admin',
                'whatsapp' => '+601116801508'
            ]
        );
    }
} 