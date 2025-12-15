<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class AdmissionFeeItem extends Model
{
    use LogsActivity;

    protected $fillable = [
        'admission_fee_configuration_id',
        'item_name',
        'amount',
        'is_active',
        'description',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['item_name', 'amount', 'is_active', 'description'])
            ->logOnlyDirty();
    }

    public function admissionFeeConfiguration(): BelongsTo
    {
        return $this->belongsTo(AdmissionFeeConfiguration::class);
    }

    /**
     * Get formatted amount
     */
    public function getFormattedAmountAttribute(): string
    {
        return number_format($this->amount, 2);
    }

    /**
     * Update the parent configuration total when item changes
     */
    protected static function booted()
    {
        static::saved(function ($item) {
            $item->admissionFeeConfiguration->updateTotalFee();
        });

        static::deleted(function ($item) {
            $item->admissionFeeConfiguration->updateTotalFee();
        });
    }
}