<?php

namespace App\Models;

use App\Enums\Gender;
use App\Enums\StudentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Student extends Model
{
    use LogsActivity, SoftDeletes;

    protected $fillable = [
        'family_id',
        'student_code',
        'first_name',
        'last_name',
        'full_name',
        'gender',
        'date_of_birth',
        'birth_certificate_number',
        'photo_path',
        'blood_group',
        'religion',
        'nationality',
        'previous_school',
        'previous_class',
        'health_conditions',
        'status',
        'admission_date',
        'is_active',
    ];

    protected $casts = [
        'gender' => Gender::class,
        'status' => StudentStatus::class,
        'date_of_birth' => 'date',
        'admission_date' => 'date',
        'is_active' => 'boolean',
    ];

    protected static function booted()
    {
        static::creating(function ($student) {
            if (! $student->student_code) {
                $lastStudent = static::withTrashed()->latest('id')->first();
                $nextNumber = $lastStudent ? (int) substr($lastStudent->student_code, 4) + 1 : 1;
                $student->student_code = 'STD-'.str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
            }

            if (! $student->full_name && $student->first_name && $student->last_name) {
                $student->full_name = $student->first_name.' '.$student->last_name;
            }
        });

        static::updating(function ($student) {
            if ($student->isDirty(['first_name', 'last_name'])) {
                $student->full_name = $student->first_name.' '.$student->last_name;
            }
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['student_code', 'full_name', 'status', 'is_active'])
            ->logOnlyDirty();
    }

    public function family(): BelongsTo
    {
        return $this->belongsTo(Family::class);
    }

    public function studentAcademicRecords(): HasMany
    {
        return $this->hasMany(StudentAcademicRecord::class);
    }

    public function getCurrentAcademicRecord(): ?StudentAcademicRecord
    {
        return $this->studentAcademicRecords()
            ->whereHas('academicYear', function ($query) {
                $query->where('is_active', true);
            })
            ->first();
    }

    public function getAge(): int
    {
        return $this->date_of_birth?->age ?? 0;
    }
}
