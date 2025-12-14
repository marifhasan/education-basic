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
        // Create default admin user if not exists
        if (! User::where('email', 'admin@example.com')->exists()) {
            User::create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
            ]);
            $this->command->info('✅ Admin user created: admin@example.com / password');
        }

        // Call curriculum seeder to ensure default curriculum exists
        $this->call([
            DefaultCurriculumSeeder::class,
        ]);
    }
}
