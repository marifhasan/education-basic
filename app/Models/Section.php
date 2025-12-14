<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Section extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'class_id',
        'academic_year_id',
        'name',
        'code',
        'capacity',
        'current_strength',
        'last_roll_number',
        'class_teacher_id',
        'is_active',
        'is_archived',
    ];

    protected $casts = [
        'capacity' => 'integer',
        'current_strength' => 'integer',
        'last_roll_number' => 'integer',
        'is_active' => 'boolean',
        'is_archived' => 'boolean',
    ];

    public function classModel(): BelongsTo
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function classTeacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'class_teacher_id');
    }

    public function studentAcademicRecords(): HasMany
    {
        return $this->hasMany(StudentAcademicRecord::class);
    }

    public function getNextRollNumber(): int
    {
        $this->increment('last_roll_number');

        return $this->last_roll_number;
    }

    public function isFull(): bool
    {
        return $this->current_strength >= $this->capacity;
    }
}
