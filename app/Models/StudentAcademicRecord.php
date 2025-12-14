<?php

namespace App\Models;

use App\Enums\EnrollmentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentAcademicRecord extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'student_id',
        'academic_year_id',
        'class_id',
        'section_id',
        'roll_number',
        'enrollment_date',
        'enrollment_status',
        'previous_year_gpa',
        'remarks',
        'is_active',
    ];

    protected $casts = [
        'enrollment_status' => EnrollmentStatus::class,
        'enrollment_date' => 'date',
        'previous_year_gpa' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    protected static function booted()
    {
        static::creating(function ($record) {
            if (!$record->roll_number) {
                $record->roll_number = $record->generateRollNumber();
            }
        });

        static::updating(function ($record) {
            // Regenerate roll number if section changed or roll_number is null
            if ($record->isDirty('section_id') || $record->roll_number === null) {
                $record->roll_number = $record->generateRollNumber();
            }
        });
    }

    public function generateRollNumber(): string
    {
        // Load relationships if not loaded
        $this->loadMissing(['academicYear.curriculum', 'classModel.curriculum', 'section']);

        // Get year from academic year start date (YYYY)
        $year = $this->academicYear->start_date->format('Y');

        // Get curriculum code (CC) - try from academic year first, then from class
        $curriculumCode = $this->academicYear->curriculum->code
            ?? $this->classModel->curriculum->code;

        // Get class code (LL)
        $classCode = $this->classModel->code;

        // Get section code (S)
        $sectionCode = $this->section->code ?? '0';

        // Get next serial number (SS) from section
        $serialNumber = $this->section->getNextRollNumber();

        // Format: YYYY-CC-LL-S-SS
        return sprintf(
            '%s-%s-%s-%s-%02d',
            $year,
            $curriculumCode,
            $classCode,
            $sectionCode,
            $serialNumber
        );
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function classModel(): BelongsTo
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function studentMonthlyFees(): HasMany
    {
        return $this->hasMany(StudentMonthlyFee::class);
    }

    public function getUnpaidMonthlyFees()
    {
        return $this->studentMonthlyFees()
            ->where('payment_status', 'unpaid')
            ->get();
    }

    public function getTotalUnpaidAmount(): float
    {
        return $this->studentMonthlyFees()
            ->where('payment_status', 'unpaid')
            ->sum('total_amount');
    }
}
