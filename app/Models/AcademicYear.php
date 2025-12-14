<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcademicYear extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'curriculum_id',
        'name',
        'start_date',
        'end_date',
        'is_active',
        'is_closed',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
        'is_closed' => 'boolean',
    ];

    public function curriculum(): BelongsTo
    {
        return $this->belongsTo(Curriculum::class);
    }

    public function sections(): HasMany
    {
        return $this->hasMany(Section::class);
    }

    public function studentAcademicRecords(): HasMany
    {
        return $this->hasMany(StudentAcademicRecord::class);
    }

    public function classFeeStructures(): HasMany
    {
        return $this->hasMany(ClassFeeStructure::class);
    }

    public function admissions(): HasMany
    {
        return $this->hasMany(Admission::class);
    }

    // Only one active year per curriculum
    protected static function booted()
    {
        static::saving(function ($year) {
            if ($year->is_active) {
                static::where('curriculum_id', $year->curriculum_id)
                    ->where('id', '!=', $year->id)
                    ->update(['is_active' => false]);
            }
        });
    }
}
