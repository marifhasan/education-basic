<?php

namespace App\Http\Middleware;

use App\Services\AcademicYearContext;
use App\Services\OnboardingChecklist;
use Closure;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOnboardingComplete
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if onboarding is complete
        if (!OnboardingChecklist::canAccessAdmission()) {
            Notification::make()
                ->title('Setup Required')
                ->body('Please complete the system setup wizard before accessing admissions.')
                ->warning()
                ->persistent()
                ->send();

            return redirect()->route('filament.admin.pages.setup-wizard');
        }

        // Check if academic year is selected
        if (!AcademicYearContext::hasSelectedYear()) {
            Notification::make()
                ->title('Academic Year Required')
                ->body('Please select an academic year before accessing admissions.')
                ->warning()
                ->persistent()
                ->send();

            return redirect()->route('filament.admin.pages.setup-wizard');
        }

        return $next($request);
    }
}
