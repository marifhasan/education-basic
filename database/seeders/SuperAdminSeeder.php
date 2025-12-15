<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create super admin user
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@school.local'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('admin@12345'),
                'is_active' => true,
            ]
        );

        // Assign super_admin role
        $superAdminRole = Role::findByName('super_admin');
        $superAdmin->assignRole($superAdminRole);

        $this->command->info('Super admin created successfully!');
        $this->command->info('Email: admin@school.local');
        $this->command->info('Password: admin@12345');
        $this->command->warn('IMPORTANT: Change this password immediately after first login!');
    }
}
