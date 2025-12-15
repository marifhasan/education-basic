<?php

namespace App\Providers;

use App\Models\AcademicYear;
use App\Models\ClassFeeStructure;
use App\Models\ClassModel;
use App\Models\Curriculum;
use App\Models\DiscountType;
use App\Models\Family;
use App\Models\FeeItem;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentAcademicRecord;
use App\Observers\AuditObserver;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register audit observer for critical models
        AcademicYear::observe(AuditObserver::class);
        Curriculum::observe(AuditObserver::class);
        ClassModel::observe(AuditObserver::class);
        Section::observe(AuditObserver::class);
        FeeItem::observe(AuditObserver::class);
        ClassFeeStructure::observe(AuditObserver::class);
        DiscountType::observe(AuditObserver::class);
        Student::observe(AuditObserver::class);
        Family::observe(AuditObserver::class);
        StudentAcademicRecord::observe(AuditObserver::class);

        // Define custom gates for special operations
        Gate::define('approve_admission', fn ($user) => $user->hasPermissionTo('approve_admission'));
        Gate::define('reject_admission', fn ($user) => $user->hasPermissionTo('reject_admission'));
        Gate::define('generate_monthly_fees', fn ($user) => $user->hasPermissionTo('generate_monthly_fees'));
        Gate::define('collect_payments', fn ($user) => $user->hasPermissionTo('collect_payments'));
        Gate::define('view_financial_reports', fn ($user) => $user->hasPermissionTo('view_financial_reports'));
        Gate::define('manage_setup', fn ($user) => $user->hasPermissionTo('manage_setup'));
        Gate::define('manage_users', fn ($user) => $user->hasPermissionTo('manage_users'));
        Gate::define('view_activity_logs', fn ($user) => $user->hasPermissionTo('view_activity_logs'));
    }
}
