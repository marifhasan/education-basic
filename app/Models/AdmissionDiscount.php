<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdmissionDiscount extends Model
{
    protected $fillable = [
        'admission_id',
        'discount_type_id',
        'discount_value',
        'discount_amount',
        'approved_by',
        'remarks',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'discount_amount' => 'decimal:2',
    ];

    public function admission(): BelongsTo
    {
        return $this->belongsTo(Admission::class);
    }

    public function discountType(): BelongsTo
    {
        return $this->belongsTo(DiscountType::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
