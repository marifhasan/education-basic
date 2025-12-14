<?php

namespace Database\Seeders;

use App\Enums\DiscountCalculationType;
use App\Models\DiscountType;
use Illuminate\Database\Seeder;

class DiscountTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $discountTypes = [
            [
                'name' => 'Sibling Discount',
                'code' => 'SIBLING',
                'calculation_type' => DiscountCalculationType::PERCENTAGE,
                'default_value' => 10.00,
                'description' => 'Discount for students with siblings in the same school',
                'is_active' => true,
                'requires_approval' => false,
            ],
            [
                'name' => 'Merit Scholarship',
                'code' => 'MERIT',
                'calculation_type' => DiscountCalculationType::PERCENTAGE,
                'default_value' => 25.00,
                'description' => 'Merit-based scholarship for top performing students',
                'is_active' => true,
                'requires_approval' => true,
            ],
            [
                'name' => 'Financial Aid',
                'code' => 'FINANCIAL_AID',
                'calculation_type' => DiscountCalculationType::PERCENTAGE,
                'default_value' => 50.00,
                'description' => 'Need-based financial assistance for deserving students',
                'is_active' => true,
                'requires_approval' => true,
            ],
            [
                'name' => 'Staff Child Discount',
                'code' => 'STAFF_CHILD',
                'calculation_type' => DiscountCalculationType::PERCENTAGE,
                'default_value' => 20.00,
                'description' => 'Discount for children of school staff members',
                'is_active' => true,
                'requires_approval' => false,
            ],
            [
                'name' => 'Early Bird Discount',
                'code' => 'EARLY_BIRD',
                'calculation_type' => DiscountCalculationType::PERCENTAGE,
                'default_value' => 5.00,
                'description' => 'Discount for students who pay fees before the due date',
                'is_active' => true,
                'requires_approval' => false,
            ],
            [
                'name' => 'Multiple Sibling Discount',
                'code' => 'MULTI_SIBLING',
                'calculation_type' => DiscountCalculationType::PERCENTAGE,
                'default_value' => 15.00,
                'description' => 'Additional discount for families with 3 or more children',
                'is_active' => true,
                'requires_approval' => false,
            ],
            [
                'name' => 'Sports Scholarship',
                'code' => 'SPORTS',
                'calculation_type' => DiscountCalculationType::PERCENTAGE,
                'default_value' => 30.00,
                'description' => 'Scholarship for students excelling in sports',
                'is_active' => true,
                'requires_approval' => true,
            ],
            [
                'name' => 'Special Discount',
                'code' => 'SPECIAL',
                'calculation_type' => DiscountCalculationType::FIXED,
                'default_value' => 1000.00,
                'description' => 'Special case discount (fixed amount)',
                'is_active' => true,
                'requires_approval' => true,
            ],
        ];

        foreach ($discountTypes as $type) {
            DiscountType::updateOrCreate(
                ['code' => $type['code']],
                $type
            );
        }
    }
}
