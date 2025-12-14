<?php

namespace App\Services;

use App\Enums\AdmissionStatus;
use App\Enums\EnrollmentStatus;
use App\Enums\PaymentStatus;
use App\Enums\StudentStatus;
use App\Models\Admission;
use App\Models\AdmissionDiscount;
use App\Models\AdmissionPayment;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentAcademicRecord;
use Illuminate\Support\Facades\DB;

class AdmissionService
{
    /**
     * Process admission payment and create student record
     *
     * @param  array  $paymentData  ['amount', 'payment_method', 'payment_date', 'transaction_reference', 'collected_by']
     * @param  Section|null  $section  Optional section. If null, default section (code "0") will be used/created.
     * @return array ['student' => Student, 'academic_record' => StudentAcademicRecord, 'payment' => AdmissionPayment]
     *
     * @throws \Exception
     */
    public function processAdmissionPayment(Admission $admission, array $paymentData, ?Section $section = null): array
    {
        // Validate admission status
        if ($admission->status !== AdmissionStatus::APPROVED) {
            throw new \Exception('Admission must be approved before processing payment.');
        }

        if ($admission->payment_status === PaymentStatus::PAID) {
            throw new \Exception('Payment has already been processed for this admission.');
        }

        // Get or create default section if not provided
        if (!$section) {
            $section = $this->getOrCreateDefaultSection($admission->academicYear, $admission->classModel);
        }

        // Validate section capacity
        if ($section->isFull()) {
            throw new \Exception('Section is full. Cannot enroll student.');
        }

        // Validate payment amount
        if ($paymentData['amount'] < $admission->net_amount) {
            throw new \Exception('Payment amount must be equal to or greater than net amount.');
        }

        return DB::transaction(function () use ($admission, $paymentData, $section) {
            // Create student record
            $student = Student::create([
                'family_id' => $admission->family_id,
                'first_name' => $admission->applicant_first_name,
                'last_name' => $admission->applicant_last_name,
                'gender' => $admission->gender,
                'date_of_birth' => $admission->date_of_birth,
                'photo_path' => $admission->photo_path,
                'admission_date' => now(),
                'status' => StudentStatus::ACTIVE,
                'is_active' => true,
            ]);

            // Create student academic record (roll number will be auto-generated)
            $academicRecord = StudentAcademicRecord::create([
                'student_id' => $student->id,
                'academic_year_id' => $admission->academic_year_id,
                'class_id' => $admission->class_id,
                'section_id' => $section->id,
                'enrollment_date' => now(),
                'enrollment_status' => EnrollmentStatus::ENROLLED,
                'is_active' => true,
            ]);

            // Increment section strength
            $section->increment('current_strength');

            // Create admission payment
            $payment = AdmissionPayment::create([
                'admission_id' => $admission->id,
                'amount' => $paymentData['amount'],
                'payment_method' => $paymentData['payment_method'],
                'payment_date' => $paymentData['payment_date'],
                'transaction_reference' => $paymentData['transaction_reference'] ?? null,
                'collected_by' => $paymentData['collected_by'],
                'remarks' => $paymentData['remarks'] ?? null,
            ]);

            // Update admission record
            $admission->update([
                'student_id' => $student->id,
                'status' => AdmissionStatus::ADMITTED,
                'payment_status' => PaymentStatus::PAID,
            ]);

            return [
                'student' => $student->fresh(),
                'academic_record' => $academicRecord->fresh(),
                'payment' => $payment->fresh(),
            ];
        });
    }

    /**
     * Apply discounts to admission
     *
     * @param  array  $discounts  Array of ['discount_type_id', 'discount_value', 'approved_by', 'remarks']
     */
    public function applyDiscounts(Admission $admission, array $discounts): Admission
    {
        return DB::transaction(function () use ($admission, $discounts) {
            $totalDiscount = 0;

            foreach ($discounts as $discountData) {
                $discountType = \App\Models\DiscountType::findOrFail($discountData['discount_type_id']);

                // Calculate discount amount
                $discountAmount = $discountType->calculateDiscount(
                    $admission->admission_fee,
                    $discountData['discount_value'] ?? null
                );

                // Create discount record
                AdmissionDiscount::create([
                    'admission_id' => $admission->id,
                    'discount_type_id' => $discountData['discount_type_id'],
                    'discount_value' => $discountData['discount_value'] ?? $discountType->default_value,
                    'discount_amount' => $discountAmount,
                    'approved_by' => $discountData['approved_by'] ?? null,
                    'remarks' => $discountData['remarks'] ?? null,
                ]);

                $totalDiscount += $discountAmount;
            }

            // Update admission with calculated amounts
            $admission->update([
                'discount_amount' => $totalDiscount,
                'net_amount' => max(0, $admission->admission_fee - $totalDiscount),
            ]);

            return $admission->fresh();
        });
    }

    /**
     * Calculate net amount for admission
     */
    public function calculateNetAmount(Admission $admission): float
    {
        return $admission->calculateNetAmount();
    }

    /**
     * Get or create default section (code "0") for a class in an academic year
     */
    protected function getOrCreateDefaultSection($academicYear, $classModel): Section
    {
        return Section::firstOrCreate(
            [
                'academic_year_id' => $academicYear->id,
                'class_id' => $classModel->id,
                'code' => '0',
            ],
            [
                'name' => 'Default',
                'capacity' => 999, // Large capacity for default section
                'current_strength' => 0,
                'last_roll_number' => 0,
                'is_active' => true,
                'is_archived' => false,
            ]
        );
    }
}
