<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class MonthlyFeeGeneration extends Model
{
    use LogsActivity;
    protected $fillable = [
        'academic_year_id',
        'class_id',
        'section_id',
        'month',
        'year',
        'due_date',
        'students_count',
        'total_amount',
        'generated_by',
        'remarks',
    ];

    protected $casts = [
        'month' => 'integer',
        'year' => 'integer',
        'due_date' => 'date',
        'students_count' => 'integer',
        'total_amount' => 'decimal:2',
    ];

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function classModel(): BelongsTo
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function generatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    public function studentMonthlyFees(): HasMany
    {
        return $this->hasMany(StudentMonthlyFee::class);
    }

    /**
     * Configure activity log options
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['academic_year_id', 'class_id', 'section_id', 'month', 'year', 'due_date', 'students_count', 'total_amount', 'generated_by'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
