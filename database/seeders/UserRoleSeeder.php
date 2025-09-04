<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserRoleSeeder extends Seeder
{
    public function run(): void
    {
        // Find user by email or create
        $user = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password123')
            ]
        );

        // Assign role
        $user->assignRole('admin');
    }
}
