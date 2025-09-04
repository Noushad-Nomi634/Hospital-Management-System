<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Clear Spatie permission cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define permissions
        $permissions = [
            'view_dashboard',
            'manage_appointments',
            'manage_sessions',
            'manage_payments',
            'view_reports',
            'create_patients',
            'book_appointments',
            'view_schedule',
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions
        Role::firstOrCreate(['name' => 'admin'])->givePermissionTo(Permission::all());

        Role::firstOrCreate(['name' => 'branch_admin'])->givePermissionTo([
            'view_dashboard',
            'manage_appointments',
            'manage_sessions',
            'manage_payments',
            'view_reports',
        ]);

        Role::firstOrCreate(['name' => 'doctor'])->givePermissionTo([
            'manage_appointments',
            'manage_sessions',
        ]);

        Role::firstOrCreate(['name' => 'cashier'])->givePermissionTo([
            'manage_payments',
        ]);

        Role::firstOrCreate(['name' => 'view-only-admin'])->givePermissionTo([
            'view_dashboard',
            'view_reports',
        ]);

        Role::firstOrCreate(['name' => 'receptionist'])->givePermissionTo([
            'create_patients',
            'book_appointments',
            'view_schedule',
        ]);
    }
}
