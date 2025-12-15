<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // RBAC Setup - MUST run in this order
        $this->command->info('🔐 Setting up RBAC system...');
        $this->call([
            RolePermissionSeeder::class,        // 1. Create roles and custom permissions
            FilamentShieldSeeder::class,        // 2. Generate Filament resource permissions
            AssignResourcePermissionsSeeder::class, // 3. Assign permissions to roles
            SuperAdminSeeder::class,            // 4. Create super admin user
        ]);

        // Application Data Seeders
        $this->command->info('📊 Seeding application data...');
        $this->call([
            FeeItemSeeder::class,
            DiscountTypeSeeder::class,
        ]);

        $this->command->info('✅ Database seeded successfully!');
        $this->command->warn('⚠️  IMPORTANT: Change the super admin password after first login!');
    }
}
