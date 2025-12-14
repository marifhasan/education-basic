<?php

namespace App\Services;

use App\Enums\FeeType;
use App\Enums\PaymentStatus;
use App\Models\AcademicYear;
use App\Models\ClassModel;
use App\Models\MonthlyFeeGeneration;
use App\Models\MonthlyFeePayment;
use App\Models\Section;
use App\Models\StudentMonthlyFee;
use App\Models\StudentMonthlyFeeDiscount;
use App\Models\StudentMonthlyFeeItem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class MonthlyFeeService
{
    /**
     * Generate monthly fees for students
     *
     * @param  int  $month  (1-12)
     * @param  \Carbon\Carbon  $dueDate
     * @param  array  $discountRules  Optional array of automatic discount rules
     * @param  int  $generatedBy  User ID
     */
    public function generateMonthlyFees(
        AcademicYear $academicYear,
        int $month,
        int $year,
        ?ClassModel $class,
        ?Section $section,
        $dueDate,
        array $discountRules,
        int $generatedBy
    ): MonthlyFeeGeneration {
        return DB::transaction(function () use (
            $academicYear,
            $month,
            $year,
            $class,
            $section,
            $dueDate,
            $discountRules,
            $generatedBy
        ) {
            // Get students to generate fees for
            $students = $this->getEligibleStudents($academicYear, $class, $section);

            if ($students->isEmpty()) {
                throw new \Exception('No eligible students found for fee generation.');
            }

            $totalAmount = 0;

            // Create generation record
            $generation = MonthlyFeeGeneration::create([
                'academic_year_id' => $academicYear->id,
                'class_id' => $class?->id,
                'section_id' => $section?->id,
                'month' => $month,
                'year' => $year,
                'due_date' => $dueDate,
                'students_count' => $students->count(),
                'total_amount' => 0, // Will update after
                'generated_by' => $generatedBy,
            ]);

            // Generate fee for each student
            foreach ($students as $studentRecord) {
                $monthlyFee = $this->createStudentMonthlyFee(
                    $studentRecord,
                    $generation,
                    $month,
                    $year,
                    $dueDate
                );

                // Apply automatic discounts if any
                if (! empty($discountRules)) {
                    $this->applyAutomaticDiscounts($monthlyFee, $studentRecord, $discountRules);
                }

                // Recalculate to get final amounts
                $this->recalculateMonthlyFee($monthlyFee);

                $totalAmount += $monthlyFee->fresh()->total_amount;
            }

            // Update generation total
            $generation->update(['total_amount' => $totalAmount]);

            return $generation->fresh();
        });
    }

    /**
     * Create student monthly fee record
     *
     * @param  \App\Models\StudentAcademicRecord  $studentRecord
     * @param  \Carbon\Carbon  $dueDate
     */
    protected function createStudentMonthlyFee(
        $studentRecord,
        MonthlyFeeGeneration $generation,
        int $month,
        int $year,
        $dueDate
    ): StudentMonthlyFee {
        // Get fee structure for the class
        $feeStructure = \App\Models\ClassFeeStructure::where('class_id', $studentRecord->class_id)
            ->where('academic_year_id', $studentRecord->academic_year_id)
            ->where('is_active', true)
            ->with('feeItem')
            ->get();

        // Calculate gross amount (sum of all monthly fee items)
        $grossAmount = 0;

        // Create student monthly fee record
        $monthlyFee = StudentMonthlyFee::create([
            'student_academic_record_id' => $studentRecord->id,
            'monthly_fee_generation_id' => $generation->id,
            'month' => $month,
            'year' => $year,
            'due_date' => $dueDate,
            'gross_amount' => 0, // Will calculate below
            'discount_amount' => 0,
            'net_amount' => 0,
            'late_fee' => 0,
            'total_amount' => 0,
            'payment_status' => PaymentStatus::UNPAID,
        ]);

        // Create fee items for monthly fees only
        foreach ($feeStructure as $structure) {
            if ($structure->feeItem->fee_type === FeeType::MONTHLY) {
                StudentMonthlyFeeItem::create([
                    'student_monthly_fee_id' => $monthlyFee->id,
                    'fee_item_id' => $structure->fee_item_id,
                    'amount' => $structure->amount,
                ]);

                $grossAmount += $structure->amount;
            }
        }

        // Update gross and net amount
        $monthlyFee->update([
            'gross_amount' => $grossAmount,
            'net_amount' => $grossAmount,
            'total_amount' => $grossAmount,
        ]);

        return $monthlyFee;
    }

    /**
     * Apply automatic discounts to monthly fee
     *
     * @param  \App\Models\StudentAcademicRecord  $studentRecord
     */
    public function applyAutomaticDiscounts(
        StudentMonthlyFee $monthlyFee,
        $studentRecord,
        array $discountRules
    ): void {
        foreach ($discountRules as $rule) {
            $discountType = \App\Models\DiscountType::find($rule['discount_type_id']);

            if (! $discountType) {
                continue;
            }

            // Check if rule applies (you can add custom logic here)
            // For example: sibling discount, merit discount, etc.
            if ($this->shouldApplyDiscount($rule, $studentRecord)) {
                $discountAmount = $discountType->calculateDiscount(
                    $monthlyFee->gross_amount,
                    $rule['value'] ?? null
                );

                StudentMonthlyFeeDiscount::create([
                    'student_monthly_fee_id' => $monthlyFee->id,
                    'discount_type_id' => $discountType->id,
                    'discount_value' => $rule['value'] ?? $discountType->default_value,
                    'discount_amount' => $discountAmount,
                    'remarks' => $rule['remarks'] ?? 'Auto-applied discount',
                ]);
            }
        }
    }

    /**
     * Check if discount should be applied
     *
     * @param  \App\Models\StudentAcademicRecord  $studentRecord
     */
    protected function shouldApplyDiscount(array $rule, $studentRecord): bool
    {
        // Implement your discount logic here
        // For example: check if student has siblings, check GPA for merit discount, etc.

        // Example: Sibling discount - check if family has multiple active students
        if (isset($rule['type']) && $rule['type'] === 'sibling') {
            $siblingCount = \App\Models\Student::where('family_id', $studentRecord->student->family_id)
                ->where('status', \App\Enums\StudentStatus::ACTIVE)
                ->count();

            return $siblingCount >= ($rule['min_siblings'] ?? 2);
        }

        // Example: Merit discount - check previous year GPA
        if (isset($rule['type']) && $rule['type'] === 'merit') {
            return $studentRecord->previous_year_gpa >= ($rule['min_gpa'] ?? 4.5);
        }

        return false;
    }

    /**
     * Recalculate monthly fee totals
     */
    public function recalculateMonthlyFee(StudentMonthlyFee $monthlyFee): StudentMonthlyFee
    {
        // Calculate total discount
        $totalDiscount = $monthlyFee->studentMonthlyFeeDiscounts()->sum('discount_amount');

        // Calculate net amount
        $netAmount = max(0, $monthlyFee->gross_amount - $totalDiscount);

        // Calculate late fee if overdue
        $lateFee = 0;
        if ($monthlyFee->isOverdue() && $monthlyFee->payment_status === PaymentStatus::UNPAID) {
            // Simple late fee calculation: 2% of net amount per month overdue
            $daysOverdue = now()->diffInDays($monthlyFee->due_date);
            $monthsOverdue = ceil($daysOverdue / 30);
            $lateFee = $netAmount * 0.02 * $monthsOverdue;
        }

        // Update amounts
        $monthlyFee->update([
            'discount_amount' => $totalDiscount,
            'net_amount' => $netAmount,
            'late_fee' => $lateFee,
            'total_amount' => $netAmount + $lateFee,
        ]);

        return $monthlyFee->fresh();
    }

    /**
     * Process monthly fee payment
     *
     * @param  array  $paymentData  ['amount', 'payment_method', 'payment_date', 'transaction_reference', 'collected_by']
     */
    public function processPayment(StudentMonthlyFee $monthlyFee, array $paymentData): MonthlyFeePayment
    {
        if ($monthlyFee->payment_status === PaymentStatus::PAID) {
            throw new \Exception('This fee has already been paid.');
        }

        if ($paymentData['amount'] < $monthlyFee->total_amount) {
            throw new \Exception('Payment amount must be equal to or greater than total amount.');
        }

        return DB::transaction(function () use ($monthlyFee, $paymentData) {
            // Create payment record
            $payment = MonthlyFeePayment::create([
                'student_monthly_fee_id' => $monthlyFee->id,
                'amount' => $paymentData['amount'],
                'payment_method' => $paymentData['payment_method'],
                'payment_date' => $paymentData['payment_date'],
                'transaction_reference' => $paymentData['transaction_reference'] ?? null,
                'collected_by' => $paymentData['collected_by'],
                'remarks' => $paymentData['remarks'] ?? null,
            ]);

            // Update monthly fee status
            $monthlyFee->update([
                'payment_status' => PaymentStatus::PAID,
                'payment_date' => $paymentData['payment_date'],
                'monthly_fee_payment_id' => $payment->id,
            ]);

            return $payment->fresh();
        });
    }

    /**
     * Get eligible students for fee generation
     */
    protected function getEligibleStudents(
        AcademicYear $academicYear,
        ?ClassModel $class,
        ?Section $section
    ): Collection {
        $query = \App\Models\StudentAcademicRecord::where('academic_year_id', $academicYear->id)
            ->where('enrollment_status', \App\Enums\EnrollmentStatus::ENROLLED)
            ->where('is_active', true);

        if ($class) {
            $query->where('class_id', $class->id);
        }

        if ($section) {
            $query->where('section_id', $section->id);
        }

        return $query->with(['student', 'section'])->get();
    }
}
