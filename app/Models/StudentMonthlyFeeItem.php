<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentMonthlyFeeItem extends Model
{
    protected $fillable = [
        'student_monthly_fee_id',
        'fee_item_id',
        'amount',
        'remarks',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function studentMonthlyFee(): BelongsTo
    {
        return $this->belongsTo(StudentMonthlyFee::class);
    }

    public function feeItem(): BelongsTo
    {
        return $this->belongsTo(FeeItem::class);
    }
}
