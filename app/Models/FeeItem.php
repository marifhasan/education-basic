<?php

namespace App\Models;

use App\Enums\FeeType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class FeeItem extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'name',
        'code',
        'fee_type',
        'description',
        'is_mandatory',
        'is_active',
        'display_order',
    ];

    protected $casts = [
        'fee_type' => FeeType::class,
        'is_mandatory' => 'boolean',
        'is_active' => 'boolean',
        'display_order' => 'integer',
    ];

    public function classFeeStructures(): HasMany
    {
        return $this->hasMany(ClassFeeStructure::class);
    }

    public function studentMonthlyFeeItems(): HasMany
    {
        return $this->hasMany(StudentMonthlyFeeItem::class);
    }

    /**
     * Configure activity log options
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'code', 'fee_type', 'description', 'is_mandatory', 'is_active', 'display_order'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
