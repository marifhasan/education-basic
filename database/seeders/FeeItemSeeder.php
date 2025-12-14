<?php

namespace Database\Seeders;

use App\Enums\FeeType;
use App\Models\FeeItem;
use Illuminate\Database\Seeder;

class FeeItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $feeItems = [
            // Admission Fees
            [
                'name' => 'Admission Fee',
                'code' => 'ADMISSION',
                'fee_type' => FeeType::ADMISSION,
                'description' => 'One-time admission fee paid at the time of enrollment',
                'is_mandatory' => true,
                'is_active' => true,
                'display_order' => 1,
            ],
            [
                'name' => 'Registration Fee',
                'code' => 'REGISTRATION',
                'fee_type' => FeeType::ADMISSION,
                'description' => 'Registration and processing fee for new students',
                'is_mandatory' => true,
                'is_active' => true,
                'display_order' => 2,
            ],

            // Monthly Fees
            [
                'name' => 'Education Fee',
                'code' => 'EDUCATION',
                'fee_type' => FeeType::MONTHLY,
                'description' => 'Core monthly education fee (required for onboarding)',
                'is_mandatory' => true,
                'is_active' => true,
                'display_order' => 9,
            ],
            [
                'name' => 'Tuition Fee',
                'code' => 'TUITION',
                'fee_type' => FeeType::MONTHLY,
                'description' => 'Monthly tuition fee for regular classes',
                'is_mandatory' => true,
                'is_active' => true,
                'display_order' => 10,
            ],
            [
                'name' => 'Library Fee',
                'code' => 'LIBRARY',
                'fee_type' => FeeType::MONTHLY,
                'description' => 'Monthly library maintenance and book access fee',
                'is_mandatory' => false,
                'is_active' => true,
                'display_order' => 11,
            ],
            [
                'name' => 'Lab Fee',
                'code' => 'LAB',
                'fee_type' => FeeType::MONTHLY,
                'description' => 'Laboratory and practical class fee',
                'is_mandatory' => false,
                'is_active' => true,
                'display_order' => 12,
            ],
            [
                'name' => 'Sports Fee',
                'code' => 'SPORTS',
                'fee_type' => FeeType::MONTHLY,
                'description' => 'Sports and physical education fee',
                'is_mandatory' => false,
                'is_active' => true,
                'display_order' => 13,
            ],
            [
                'name' => 'Transport Fee',
                'code' => 'TRANSPORT',
                'fee_type' => FeeType::MONTHLY,
                'description' => 'School bus and transportation fee',
                'is_mandatory' => false,
                'is_active' => true,
                'display_order' => 14,
            ],

            // Annual Fees
            [
                'name' => 'Annual Exam Fee',
                'code' => 'ANNUAL_EXAM',
                'fee_type' => FeeType::ANNUAL,
                'description' => 'Annual examination and certificate fee',
                'is_mandatory' => true,
                'is_active' => true,
                'display_order' => 20,
            ],
            [
                'name' => 'Annual Function Fee',
                'code' => 'ANNUAL_FUNCTION',
                'fee_type' => FeeType::ANNUAL,
                'description' => 'Annual day and cultural function fee',
                'is_mandatory' => false,
                'is_active' => true,
                'display_order' => 21,
            ],
            [
                'name' => 'Development Fee',
                'code' => 'DEVELOPMENT',
                'fee_type' => FeeType::ANNUAL,
                'description' => 'Annual development and infrastructure fee',
                'is_mandatory' => true,
                'is_active' => true,
                'display_order' => 22,
            ],

            // One-Time Fees
            [
                'name' => 'ID Card Fee',
                'code' => 'ID_CARD',
                'fee_type' => FeeType::ONE_TIME,
                'description' => 'Student ID card issuance fee',
                'is_mandatory' => true,
                'is_active' => true,
                'display_order' => 30,
            ],
            [
                'name' => 'Uniform Fee',
                'code' => 'UNIFORM',
                'fee_type' => FeeType::ONE_TIME,
                'description' => 'School uniform purchase fee',
                'is_mandatory' => false,
                'is_active' => true,
                'display_order' => 31,
            ],
            [
                'name' => 'Books Fee',
                'code' => 'BOOKS',
                'fee_type' => FeeType::ONE_TIME,
                'description' => 'Textbooks and study material fee',
                'is_mandatory' => false,
                'is_active' => true,
                'display_order' => 32,
            ],
            [
                'name' => 'Exam Form Fee',
                'code' => 'EXAM_FORM',
                'fee_type' => FeeType::ONE_TIME,
                'description' => 'Exam form submission fee',
                'is_mandatory' => false,
                'is_active' => true,
                'display_order' => 33,
            ],
        ];

        foreach ($feeItems as $item) {
            FeeItem::updateOrCreate(
                ['code' => $item['code']],
                $item
            );
        }
    }
}
