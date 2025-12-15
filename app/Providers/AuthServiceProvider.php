<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // App\Models\Model::App\Policies\ModelPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Implicitly grant "Super Admin" role all permissions
        // This works in the app by using gate-related helpers like auth()->user->can() and @can()
        Gate::before(function ($user, $ability) {
            if ($user->hasRole('super_admin')) {
                return true;
            }
        });

        // Custom permission gates
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