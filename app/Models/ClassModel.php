<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassModel extends Model
{
    use SoftDeletes;

    protected $table = 'classes';

    protected $fillable = [
        'curriculum_id',
        'name',
        'code',
        'order',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    public function curriculum(): BelongsTo
    {
        return $this->belongsTo(Curriculum::class);
    }

    public function sections(): HasMany
    {
        return $this->hasMany(Section::class, 'class_id');
    }

    public function classFeeStructures(): HasMany
    {
        return $this->hasMany(ClassFeeStructure::class, 'class_id');
    }

    public function studentAcademicRecords(): HasMany
    {
        return $this->hasMany(StudentAcademicRecord::class, 'class_id');
    }

    public function admissions(): HasMany
    {
        return $this->hasMany(Admission::class, 'class_id');
    }
}
