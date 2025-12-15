<?php

namespace App\Models;

use App\Enums\AdmissionStatus;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Admission extends Model
{
    use LogsActivity, SoftDeletes;

    protected $fillable = [
        'academic_year_id',
        'class_id',
        'family_id',
        'student_id',
        'admission_number',
        'applicant_first_name',
        'applicant_last_name',
        'applicant_full_name',
        'applicant_gender',
        'applicant_dob',
        'applicant_photo_path',
        'application_date',
        'status',
        'approval_date',
        'approved_by',
        'rejection_reason',
        'admission_fee_configuration_id',
        'admission_fee_amount',
        'discount_amount',
        'net_amount',
        'payment_status',
        'remarks',
    ];

    protected $casts = [
        'status' => AdmissionStatus::class,
        'payment_status' => PaymentStatus::class,
        'application_date' => 'date',
        'approval_date' => 'date',
        'applicant_dob' => 'date',
        'admission_fee_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'net_amount' => 'decimal:2',
    ];

    protected static function booted()
    {
        static::creating(function ($admission) {
            if (! $admission->admission_number) {
                $year = now()->year;
                $lastAdmission = static::withTrashed()
                    ->whereYear('created_at', $year)
                    ->latest('id')
                    ->first();

                $nextNumber = $lastAdmission
                    ? (int) substr($lastAdmission->admission_number, -4) + 1
                    : 1;

                $admission->admission_number = 'ADM-'.$year.'-'.str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
            }

            if (! $admission->applicant_full_name && $admission->applicant_first_name && $admission->applicant_last_name) {
                $admission->applicant_full_name = $admission->applicant_first_name.' '.$admission->applicant_last_name;
            }
        });

        static::updating(function ($admission) {
            if ($admission->isDirty(['applicant_first_name', 'applicant_last_name'])) {
                $admission->applicant_full_name = $admission->applicant_first_name.' '.$admission->applicant_last_name;
            }
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['admission_number', 'status', 'payment_status', 'net_amount'])
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

    public function family(): BelongsTo
    {
        return $this->belongsTo(Family::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function admissionFeeConfiguration(): BelongsTo
    {
        return $this->belongsTo(AdmissionFeeConfiguration::class);
    }

    public function admissionDiscounts(): HasMany
    {
        return $this->hasMany(AdmissionDiscount::class);
    }

    public function admissionPayment(): HasOne
    {
        return $this->hasOne(AdmissionPayment::class);
    }

    public function calculateNetAmount(): float
    {
        if (!$this->exists) {
            // If the model doesn't exist yet, return the admission fee amount
            return $this->admission_fee_amount ?? 0;
        }

        $totalDiscount = $this->admissionDiscounts()->sum('discount_amount');

        return max(0, $this->admission_fee_amount - $totalDiscount);
    }

    /**
     * Get the admission fee configuration for this admission
     */
    public function getFeeConfiguration(): ?AdmissionFeeConfiguration
    {
        if ($this->admission_fee_configuration_id) {
            return $this->admissionFeeConfiguration;
        }

        // Find configuration for this academic year and class
        return AdmissionFeeConfiguration::where('academic_year_id', $this->academic_year_id)
            ->where('class_id', $this->class_id)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Set admission fee from configuration
     */
    public function setFeeFromConfiguration(): void
    {
        $config = $this->getFeeConfiguration();

        if ($config) {
            $this->admission_fee_configuration_id = $config->id;
            $this->admission_fee_amount = $config->total_admission_fee;
            $this->net_amount = $this->calculateNetAmount();
        }
    }

    /**
     * Get fee items for this admission
     */
    public function getFeeItems()
    {
        $config = $this->getFeeConfiguration();

        if (!$config) {
            return collect([]);
        }

        return $config->activeAdmissionFeeItems;
    }
}
