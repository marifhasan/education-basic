<?php

namespace App\Services;

use App\Enums\EnrollmentStatus;
use App\Models\AcademicYear;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentAcademicRecord;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class EnrollmentService
{
    /**
     * Enroll student to new academic year and section
     *
     * @param  Section|null  $newSection  Optional section. If null, default section (code "0") will be used/created.
     * @param  int|null  $classId  Required if $newSection is null
     * @param  bool  $isPromotion  Whether this is a promotion from previous year
     *
     * @throws \Exception
     */
    public function enrollStudent(
        Student $student,
        AcademicYear $newAcademicYear,
        ?Section $newSection = null,
        ?int $classId = null,
        bool $isPromotion = false
    ): StudentAcademicRecord {
        // Get or create default section if not provided
        if (!$newSection) {
            if (!$classId) {
                throw new \Exception('Either section or class_id must be provided.');
            }
            $classModel = \App\Models\ClassModel::findOrFail($classId);
            $newSection = $this->getOrCreateDefaultSection($newAcademicYear, $classModel);
        }

        // Validate section belongs to academic year
        if ($newSection->academic_year_id !== $newAcademicYear->id) {
            throw new \Exception('Section does not belong to the specified academic year.');
        }

        // Check if section is full
        if ($newSection->isFull()) {
            throw new \Exception('Section is full. Cannot enroll student.');
        }

        // Check if student already enrolled in this academic year
        $existingRecord = StudentAcademicRecord::where('student_id', $student->id)
            ->where('academic_year_id', $newAcademicYear->id)
            ->first();

        if ($existingRecord) {
            throw new \Exception('Student is already enrolled in this academic year.');
        }

        return DB::transaction(function () use ($student, $newAcademicYear, $newSection, $isPromotion) {
            // Get previous academic record if promotion
            $previousRecord = null;
            if ($isPromotion) {
                $previousRecord = $student->studentAcademicRecords()
                    ->where('is_active', true)
                    ->latest()
                    ->first();

                // Mark previous record as promoted
                if ($previousRecord) {
                    $previousRecord->update([
                        'enrollment_status' => EnrollmentStatus::PROMOTED,
                        'is_active' => false,
                    ]);
                }
            }

            // Create new academic record (roll number will be auto-generated)
            $newRecord = StudentAcademicRecord::create([
                'student_id' => $student->id,
                'academic_year_id' => $newAcademicYear->id,
                'class_id' => $newSection->class_id,
                'section_id' => $newSection->id,
                'enrollment_date' => now(),
                'enrollment_status' => EnrollmentStatus::ENROLLED,
                'previous_year_gpa' => $previousRecord?->previous_year_gpa,
                'is_active' => true,
            ]);

            // Increment section strength
            $newSection->increment('current_strength');

            return $newRecord->fresh();
        });
    }

    /**
     * Bulk promote students from current section to target section
     *
     * @param  array  $studentIds  Optional array of specific student IDs to promote. If empty, promotes all.
     * @return array ['success_count' => int, 'failed' => array, 'records' => Collection]
     */
    public function bulkPromote(
        Section $currentSection,
        Section $targetSection,
        array $studentIds = []
    ): array {
        // Get students to promote
        $query = StudentAcademicRecord::where('section_id', $currentSection->id)
            ->where('enrollment_status', EnrollmentStatus::ENROLLED)
            ->where('is_active', true)
            ->with('student');

        if (! empty($studentIds)) {
            $query->whereIn('student_id', $studentIds);
        }

        $studentsToPromote = $query->get();

        if ($studentsToPromote->isEmpty()) {
            throw new \Exception('No eligible students found for promotion.');
        }

        $successCount = 0;
        $failed = [];
        $newRecords = collect();

        // Get target academic year
        $targetAcademicYear = $targetSection->academicYear;

        foreach ($studentsToPromote as $currentRecord) {
            try {
                $newRecord = $this->enrollStudent(
                    student: $currentRecord->student,
                    newAcademicYear: $targetAcademicYear,
                    newSection: $targetSection,
                    classId: null,
                    isPromotion: true
                );

                $newRecords->push($newRecord);
                $successCount++;
            } catch (\Exception $e) {
                $failed[] = [
                    'student_id' => $currentRecord->student_id,
                    'student_name' => $currentRecord->student->full_name,
                    'error' => $e->getMessage(),
                ];
            }
        }

        return [
            'success_count' => $successCount,
            'failed' => $failed,
            'records' => $newRecords,
        ];
    }

    /**
     * Transfer student to different section (same academic year)
     *
     * @throws \Exception
     */
    public function transferToSection(
        StudentAcademicRecord $currentRecord,
        Section $targetSection
    ): StudentAcademicRecord {
        // Validate target section belongs to same academic year
        if ($targetSection->academic_year_id !== $currentRecord->academic_year_id) {
            throw new \Exception('Target section must be in the same academic year.');
        }

        // Check if section is full
        if ($targetSection->isFull()) {
            throw new \Exception('Target section is full. Cannot transfer student.');
        }

        return DB::transaction(function () use ($currentRecord, $targetSection) {
            // Decrement old section strength
            $currentRecord->section->decrement('current_strength');

            // Update academic record with new section (roll number will be regenerated)
            $currentRecord->section_id = $targetSection->id;
            $currentRecord->roll_number = null; // Force regeneration
            $currentRecord->save();

            // Increment target section strength
            $targetSection->increment('current_strength');

            return $currentRecord->fresh();
        });
    }

    /**
     * Withdraw student from current enrollment
     */
    public function withdrawStudent(StudentAcademicRecord $academicRecord, ?string $reason = null): StudentAcademicRecord
    {
        return DB::transaction(function () use ($academicRecord, $reason) {
            // Update academic record
            $academicRecord->update([
                'enrollment_status' => EnrollmentStatus::WITHDRAWN,
                'is_active' => false,
                'remarks' => $reason,
            ]);

            // Decrement section strength
            $academicRecord->section->decrement('current_strength');

            // Update student status if needed
            $academicRecord->student->update([
                'is_active' => false,
            ]);

            return $academicRecord->fresh();
        });
    }

    /**
     * Detain student (not promoted to next year)
     */
    public function detainStudent(StudentAcademicRecord $academicRecord, ?string $reason = null): StudentAcademicRecord
    {
        $academicRecord->update([
            'enrollment_status' => EnrollmentStatus::DETAINED,
            'remarks' => $reason,
        ]);

        return $academicRecord->fresh();
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
