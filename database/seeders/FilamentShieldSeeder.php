<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class FilamentShieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Generate Filament Shield permissions for all resources, pages, and widgets
        $this->command->info('Generating Filament Shield permissions...');

        // Create the permissions directly instead of using the command
        $this->createResourcePermissions();

        $this->command->info('Filament Shield permissions generated successfully!');
    }

    /**
     * Create resource permissions manually
     */
    private function createResourcePermissions(): void
    {
        $resources = [
            'Admission',
            'Student',
            'Family',
            'StudentAcademicRecord',
            'FeeItem',
            'ClassFeeStructure',
            'DiscountType',
            'MonthlyFeeGeneration',
            'MonthlyFeePayment',
            'Curriculum',
            'AcademicYear',
            'ClassModel',
            'Section',
            'User',
            'Role',
            'ActivityLog',
        ];

        $permissions = ['viewAny', 'view', 'create', 'update', 'delete', 'restore', 'forceDelete'];

        foreach ($resources as $resource) {
            foreach ($permissions as $permission) {
                $permissionName = ucfirst($permission) . ':' . $resource;
                \Spatie\Permission\Models\Permission::firstOrCreate([
                    'name' => $permissionName,
                    'guard_name' => 'web'
                ]);
            }
        }

        // Add additional permissions
        $additionalPermissions = [
            'replicate',
            'reorder',
            'deleteAny',
            'forceDeleteAny',
            'restoreAny',
        ];

        foreach ($resources as $resource) {
            foreach ($additionalPermissions as $permission) {
                $permissionName = ucfirst($permission) . ':' . $resource;
                \Spatie\Permission\Models\Permission::firstOrCreate([
                    'name' => $permissionName,
                    'guard_name' => 'web'
                ]);
            }
        }
    }
}