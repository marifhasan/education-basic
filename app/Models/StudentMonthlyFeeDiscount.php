<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentMonthlyFeeDiscount extends Model
{
    protected $fillable = [
        'student_monthly_fee_id',
        'discount_type_id',
        'discount_value',
        'discount_amount',
        'remarks',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'discount_amount' => 'decimal:2',
    ];

    public function studentMonthlyFee(): BelongsTo
    {
        return $this->belongsTo(StudentMonthlyFee::class);
    }

    public function discountType(): BelongsTo
    {
        return $this->belongsTo(DiscountType::class);
    }
}
