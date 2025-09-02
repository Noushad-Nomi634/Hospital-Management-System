<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find user by email
        $user = User::where('email', 'admin@example.com')->first();

        if ($user) {
            // Assign the correct role defined in RolesAndPermissionsSeeder
            $user->assignRole('Super Admin'); // <- yahan 'Super Admin' use karein
        }
    }
}

