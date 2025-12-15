<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class AdmissionFeeConfiguration extends Model
{
    use LogsActivity, SoftDeletes;

    protected $fillable = [
        'academic_year_id',
        'class_id',
        'total_admission_fee',
        'is_active',
    ];

    protected $casts = [
        'total_admission_fee' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['academic_year_id', 'class_id', 'total_admission_fee', 'is_active'])
            ->logOnlyDirty();
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function classModel(): BelongsTo
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function admissionFeeItems(): HasMany
    {
        return $this->hasMany(AdmissionFeeItem::class);
    }

    public function activeAdmissionFeeItems(): HasMany
    {
        return $this->hasMany(AdmissionFeeItem::class)->where('is_active', true);
    }

    /**
     * Calculate total fee from items
     */
    public function calculateTotalFee(): float
    {
        return $this->activeAdmissionFeeItems()->sum('amount');
    }

    /**
     * Update total fee based on items
     */
    public function updateTotalFee(): void
    {
        $this->update([
            'total_admission_fee' => $this->calculateTotalFee()
        ]);
    }

    /**
     * Get formatted total fee
     */
    public function getFormattedTotalFeeAttribute(): string
    {
        return number_format($this->total_admission_fee, 2);
    }
}