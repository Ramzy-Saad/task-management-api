<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Ramzy Saad',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
            ]
        );

        // 2. Additional random users
        User::factory()->count(5)->create();
    }
}