<?php

namespace App\Models;

use App\Enums\DiscountCalculationType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class DiscountType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'calculation_type',
        'default_value',
        'description',
        'is_active',
        'requires_approval',
    ];

    protected $casts = [
        'calculation_type' => DiscountCalculationType::class,
        'default_value' => 'decimal:2',
        'is_active' => 'boolean',
        'requires_approval' => 'boolean',
    ];

    public function admissionDiscounts(): HasMany
    {
        return $this->hasMany(AdmissionDiscount::class);
    }

    public function studentMonthlyFeeDiscounts(): HasMany
    {
        return $this->hasMany(StudentMonthlyFeeDiscount::class);
    }

    public function calculateDiscount(float $amount, ?float $value = null): float
    {
        $discountValue = $value ?? $this->default_value;

        return $this->calculation_type->calculate($amount, $discountValue);
    }
}
