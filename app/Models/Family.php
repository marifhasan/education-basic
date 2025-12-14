<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Family extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'family_code',
        'father_name',
        'father_nid',
        'father_phone',
        'father_occupation',
        'mother_name',
        'mother_nid',
        'mother_phone',
        'mother_occupation',
        'guardian_name',
        'guardian_nid',
        'guardian_phone',
        'guardian_relation',
        'primary_contact_person',
        'primary_phone',
        'primary_email',
        'present_address',
        'permanent_address',
        'monthly_income',
        'is_active',
    ];

    protected $casts = [
        'monthly_income' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    protected static function booted()
    {
        static::creating(function ($family) {
            if (! $family->family_code) {
                $lastFamily = static::withTrashed()->latest('id')->first();
                $nextNumber = $lastFamily ? (int) substr($lastFamily->family_code, 4) + 1 : 1;
                $family->family_code = 'FAM-'.str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
            }
        });
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function admissions(): HasMany
    {
        return $this->hasMany(Admission::class);
    }

    public function getTotalStudents(): int
    {
        return $this->students()->count();
    }
}
