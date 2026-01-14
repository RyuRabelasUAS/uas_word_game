<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'mobile_number' => '+1234567890',
                'company' => 'Word Nexus',
                'position' => 'System Administrator',
                'is_admin' => true,
                'password' => Hash::make('password'),
            ]
        );
    }
}
