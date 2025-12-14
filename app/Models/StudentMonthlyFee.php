<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentMonthlyFee extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'student_academic_record_id',
        'monthly_fee_generation_id',
        'month',
        'year',
        'due_date',
        'gross_amount',
        'discount_amount',
        'net_amount',
        'late_fee',
        'total_amount',
        'payment_status',
        'payment_date',
        'monthly_fee_payment_id',
        'remarks',
    ];

    protected $casts = [
        'payment_status' => PaymentStatus::class,
        'month' => 'integer',
        'year' => 'integer',
        'due_date' => 'date',
        'payment_date' => 'date',
        'gross_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'late_fee' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function studentAcademicRecord(): BelongsTo
    {
        return $this->belongsTo(StudentAcademicRecord::class);
    }

    public function monthlyFeeGeneration(): BelongsTo
    {
        return $this->belongsTo(MonthlyFeeGeneration::class);
    }

    public function monthlyFeePayment(): BelongsTo
    {
        return $this->belongsTo(MonthlyFeePayment::class);
    }

    public function studentMonthlyFeeItems(): HasMany
    {
        return $this->hasMany(StudentMonthlyFeeItem::class);
    }

    public function studentMonthlyFeeDiscounts(): HasMany
    {
        return $this->hasMany(StudentMonthlyFeeDiscount::class);
    }

    public function isOverdue(): bool
    {
        if ($this->payment_status === PaymentStatus::PAID || $this->payment_status === PaymentStatus::WAIVED) {
            return false;
        }

        return $this->due_date && $this->due_date->isPast();
    }

    public function getMonthName(): string
    {
        return \Carbon\Carbon::createFromDate($this->year, $this->month, 1)->format('F Y');
    }
}
