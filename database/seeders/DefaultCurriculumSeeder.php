<?php

namespace Database\Seeders;

use App\Models\Curriculum;
use Illuminate\Database\Seeder;

class DefaultCurriculumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure at least one curriculum exists - create default if none found
        if (Curriculum::count() === 0) {
            Curriculum::create([
                'name' => 'National Curriculum',
                'code' => '01',
                'description' => 'Default national curriculum for general education',
                'academic_year_start_month' => 1,  // January
                'academic_year_end_month' => 12,   // December
                'is_active' => true,
            ]);

            $this->command->info('✅ Default curriculum created: National Curriculum (Code: 01)');
        } else {
            $this->command->info('ℹ️  Curriculum already exists. Skipping default creation.');
        }

        // Optionally create additional curricula for comprehensive setup
        $curricula = [
            [
                'name' => 'Madrasah Education Board',
                'code' => '02',
                'description' => 'Islamic education curriculum board',
                'academic_year_start_month' => 1,
                'academic_year_end_month' => 12,
                'is_active' => true,
            ],
            [
                'name' => 'English Medium',
                'code' => '03',
                'description' => 'Cambridge/Edexcel based English medium curriculum',
                'academic_year_start_month' => 9,  // September
                'academic_year_end_month' => 8,    // August
                'is_active' => true,
            ],
        ];

        foreach ($curricula as $curriculum) {
            if (! Curriculum::where('code', $curriculum['code'])->exists()) {
                Curriculum::create($curriculum);
                $this->command->info("✅ Created curriculum: {$curriculum['name']} (Code: {$curriculum['code']})");
            }
        }
    }
}
