<?php

namespace App\Models;

use App\Enums\PaymentMethod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class AdmissionPayment extends Model
{
    use LogsActivity, SoftDeletes;

    protected $fillable = [
        'admission_id',
        'receipt_number',
        'amount',
        'payment_method',
        'payment_date',
        'transaction_reference',
        'collected_by',
        'remarks',
    ];

    protected $casts = [
        'payment_method' => PaymentMethod::class,
        'amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    protected static function booted()
    {
        static::creating(function ($payment) {
            if (! $payment->receipt_number) {
                $lastPayment = static::withTrashed()->latest('id')->first();
                $nextNumber = $lastPayment ? (int) substr($lastPayment->receipt_number, 8) + 1 : 1;
                $payment->receipt_number = 'ADM-RCP-'.str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
            }
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['receipt_number', 'amount', 'payment_method', 'payment_date'])
            ->logOnlyDirty();
    }

    public function admission(): BelongsTo
    {
        return $this->belongsTo(Admission::class);
    }

    public function collectedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'collected_by');
    }
}
