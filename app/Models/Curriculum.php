<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Curriculum extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'description',
        'academic_year_start_month',
        'academic_year_end_month',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'academic_year_start_month' => 'integer',
        'academic_year_end_month' => 'integer',
    ];

    public function academicYears(): HasMany
    {
        return $this->hasMany(AcademicYear::class);
    }

    public function classes(): HasMany
    {
        return $this->hasMany(ClassModel::class);
    }

    public function getActiveAcademicYear(): ?AcademicYear
    {
        return $this->academicYears()->where('is_active', true)->first();
    }
}
