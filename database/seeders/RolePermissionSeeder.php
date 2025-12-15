<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create custom permissions
        $customPermissions = [
            'approve_admission',
            'reject_admission',
            'generate_monthly_fees',
            'collect_payments',
            'view_financial_reports',
            'manage_setup',
            'manage_users',
            'view_activity_logs',
        ];

        foreach ($customPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Create roles
        $roles = [
            'super_admin',
            'academic_coordinator',
            'finance_manager',
            'accountant',
            'data_entry',
            'setup_manager',
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate([
                'name' => $roleName,
                'guard_name' => 'web'
            ]);
        }

        // Super admin gets all custom permissions
        $superAdmin = Role::findByName('super_admin');
        $superAdmin->givePermissionTo(Permission::all());
    }
}
