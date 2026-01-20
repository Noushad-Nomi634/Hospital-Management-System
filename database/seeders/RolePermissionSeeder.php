<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\Doctor;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ──────────────── Step 1: Define permissions to copy ────────────────
        $permissions = [
            'view_dashboard',
            'view patients',
            'create patients',
            'edit patients',
            'delete patients',
            'view appointments',
            'create appointments',
            'edit appointments',
            'delete appointments',
            'view consultation',
            'view enrollment',
            'create enrollment',
            'edit enrollment',
            'delete enrollment',
            'view feedback',
            'view payments',
            'create payments',
            'view returns',
            'create returns',
            'view_reports',
            'manage_appointments',
            'manage_sessions',
            'manage_payments',
            'create_patients',
            'book_appointments',
        ];

        // ──────────────── Step 2: Create permissions for doctor guard ────────────────
        foreach ($permissions as $perm) {
            Permission::firstOrCreate([
                'name' => $perm,
                'guard_name' => 'doctor',
            ]);
        }

        $this->command->info('Doctor guard permissions added ✅');

        // ──────────────── Step 3: Create Doctor role if not exists ────────────────
        $role = Role::firstOrCreate([
            'name' => 'doctor',
            'guard_name' => 'doctor',
        ]);

        $this->command->info('Doctor role created ✅');

        // ──────────────── Step 4: Assign all doctor guard permissions to Doctor role ────────────────
        $role->syncPermissions(Permission::where('guard_name', 'doctor')->get());

        $this->command->info('All permissions assigned to Doctor role ✅');

        // ──────────────── Step 5: Optional - Assign role to all existing doctors ────────────────
        $doctors = Doctor::all();
        foreach ($doctors as $doctor) {
            if (!$doctor->hasRole('doctor')) {
                $doctor->assignRole('doctor');
            }
        }

        $this->command->info('Doctor role assigned to all doctors ✅');

        // ──────────────── Step 6: Optional - Add denied permissions for test ────────────────
        // Example: deny 'view_dashboard' for Doctor ID 1
        $doctor = Doctor::find(1);
        if ($doctor) {
            $permission = Permission::where('name', 'view_dashboard')->where('guard_name', 'doctor')->first();
            if ($permission) {
                DB::table('denied_permissions')->updateOrInsert(
                    [
                        'permission_id' => $permission->id,
                        'model_id' => $doctor->id,
                        'model_type' => Doctor::class,
                    ],
                    [
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }

        $this->command->info('Denied permissions example added for Doctor ID 1 ✅');
    }
}
