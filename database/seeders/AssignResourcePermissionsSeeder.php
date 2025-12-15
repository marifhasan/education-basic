<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AssignResourcePermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Academic Coordinator - Admissions, students, families, academic records (no financial access)
        $academicCoordinator = Role::findByName('academic_coordinator');
        $academicCoordinator->givePermissionTo([
            // Admissions
            'ViewAny:Admission',
            'View:Admission',
            'Create:Admission',
            'Update:Admission',
            'Delete:Admission',
            'approve_admission',
            'reject_admission',

            // Students
            'ViewAny:Student',
            'View:Student',
            'Create:Student',
            'Update:Student',
            'Delete:Student',

            // Families
            'ViewAny:Family',
            'View:Family',
            'Create:Family',
            'Update:Family',
            'Delete:Family',

            // Student Academic Records
            'ViewAny:StudentAcademicRecord',
            'View:StudentAcademicRecord',
            'Create:StudentAcademicRecord',
            'Update:StudentAcademicRecord',
            'Delete:StudentAcademicRecord',

            // Activity Logs
            'view_activity_logs',
        ]);

        // Finance Manager - Full financial operations
        $financeManager = Role::findByName('finance_manager');
        $financeManager->givePermissionTo([
            // Fee Items
            'ViewAny:FeeItem',
            'View:FeeItem',
            'Create:FeeItem',
            'Update:FeeItem',
            'Delete:FeeItem',

            // Class Fee Structures
            'ViewAny:ClassFeeStructure',
            'View:ClassFeeStructure',
            'Create:ClassFeeStructure',
            'Update:ClassFeeStructure',
            'Delete:ClassFeeStructure',

            // Discount Types
            'ViewAny:DiscountType',
            'View:DiscountType',
            'Create:DiscountType',
            'Update:DiscountType',
            'Delete:DiscountType',

            // Monthly Fee Generation
            'ViewAny:MonthlyFeeGeneration',
            'View:MonthlyFeeGeneration',
            'Create:MonthlyFeeGeneration',
            'Update:MonthlyFeeGeneration',
            'Delete:MonthlyFeeGeneration',
            'generate_monthly_fees',

            // Monthly Fee Payments
            'ViewAny:MonthlyFeePayment',
            'View:MonthlyFeePayment',
            'Create:MonthlyFeePayment',
            'Update:MonthlyFeePayment',
            'Delete:MonthlyFeePayment',
            'collect_payments',
            'view_financial_reports',

            // Activity Logs
            'view_activity_logs',

            // View students and families (for payment context)
            'ViewAny:Student',
            'View:Student',
            'ViewAny:Family',
            'View:Family',
        ]);

        // Accountant - Payment recording only
        $accountant = Role::findByName('accountant');
        $accountant->givePermissionTo([
            // View only for students and families
            'ViewAny:Student',
            'View:Student',
            'ViewAny:Family',
            'View:Family',

            // View student academic records
            'ViewAny:StudentAcademicRecord',
            'View:StudentAcademicRecord',

            // View class fee structures
            'ViewAny:ClassFeeStructure',
            'View:ClassFeeStructure',

            // Payment recording
            'ViewAny:MonthlyFeePayment',
            'View:MonthlyFeePayment',
            'Create:MonthlyFeePayment',
            'collect_payments',
        ]);

        // Data Entry - Student/family data entry only
        $dataEntry = Role::findByName('data_entry');
        $dataEntry->givePermissionTo([
            // Students
            'ViewAny:Student',
            'View:Student',
            'Create:Student',
            'Update:Student',

            // Families
            'ViewAny:Family',
            'View:Family',
            'Create:Family',
            'Update:Family',
        ]);

        // Setup Manager - System configuration
        $setupManager = Role::findByName('setup_manager');
        $setupManager->givePermissionTo([
            // Curricula
            'ViewAny:Curriculum',
            'View:Curriculum',
            'Create:Curriculum',
            'Update:Curriculum',
            'Delete:Curriculum',

            // Academic Years
            'ViewAny:AcademicYear',
            'View:AcademicYear',
            'Create:AcademicYear',
            'Update:AcademicYear',
            'Delete:AcademicYear',

            // Classes
            'ViewAny:ClassModel',
            'View:ClassModel',
            'Create:ClassModel',
            'Update:ClassModel',
            'Delete:ClassModel',

            // Sections
            'ViewAny:Section',
            'View:Section',
            'Create:Section',
            'Update:Section',
            'Delete:Section',

            // Custom permissions
            'manage_setup',
            'view_activity_logs',
        ]);

        // Super Admin already has all permissions from RolePermissionSeeder
    }
}
