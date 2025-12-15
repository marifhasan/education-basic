<?php

namespace App\Filament\Pages;

use App\Models\AppSetting;
use App\Services\OnboardingChecklist;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Gate;

class SetupWizard extends Page implements HasForms
{
    use InteractsWithForms;

    public static function canAccess(): bool
    {
        // Only users with manage_setup permission or super_admin can access
        return Gate::allows('manage_setup') || auth()->user()?->hasRole('super_admin');
    }

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    protected string $view = 'filament.pages.setup-wizard';

    protected static ?string $navigationLabel = 'Setup Wizard';

    protected static ?string $title = 'System Setup Wizard';

    protected static ?int $navigationSort = -1; // Show at top

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'default_advance_monthly_fee' => AppSetting::get('default_advance_monthly_fee', 0),
        ]);
    }

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('default_advance_monthly_fee')
                ->label('Default Advance Monthly Fee (months)')
                ->helperText('How many months of fees to collect during admission (e.g., 3 means collect 3 months upfront)')
                ->numeric()
                ->minValue(0)
                ->maxValue(12)
                ->default(0)
                ->required(),
        ];
    }

    protected function getFormStatePath(): string
    {
        return 'data';
    }

    public function getSteps(): array
    {
        return OnboardingChecklist::steps();
    }

    public function getCompletionPercentage(): int
    {
        return OnboardingChecklist::completionPercentage();
    }

    public function isAllStepsComplete(): bool
    {
        return OnboardingChecklist::isComplete();
    }

    public function isMarkedComplete(): bool
    {
        return OnboardingChecklist::isMarkedComplete();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('saveConfig')
                ->label('Save Configuration')
                ->icon('heroicon-o-check-circle')
                ->color('info')
                ->action(function () {
                    $data = $this->form->getState();

                    AppSetting::set(
                        'default_advance_monthly_fee',
                        $data['default_advance_monthly_fee'],
                        'integer'
                    );

                    Notification::make()
                        ->title('Configuration saved')
                        ->success()
                        ->send();

                    // Refresh the page to update checklist
                    $this->redirect(static::getUrl());
                }),

            Action::make('markComplete')
                ->label('Mark Setup Complete')
                ->icon('heroicon-o-check-badge')
                ->color('success')
                ->visible(fn() => $this->isAllStepsComplete() && !$this->isMarkedComplete())
                ->requiresConfirmation()
                ->modalHeading('Complete Setup')
                ->modalDescription('Are you sure all setup steps are complete? The admission module will be unlocked.')
                ->modalSubmitActionLabel('Yes, Complete Setup')
                ->action(function () {
                    if (OnboardingChecklist::markComplete()) {
                        Notification::make()
                            ->title('Setup completed!')
                            ->body('The system is now ready for admissions.')
                            ->success()
                            ->send();

                        $this->redirect(static::getUrl());
                    } else {
                        Notification::make()
                            ->title('Cannot complete setup')
                            ->body('Some required steps are still incomplete.')
                            ->danger()
                            ->send();
                    }
                }),

            Action::make('resetOnboarding')
                ->label('Reset Onboarding')
                ->icon('heroicon-o-arrow-path')
                ->color('danger')
                ->visible(fn() => $this->isMarkedComplete())
                ->requiresConfirmation()
                ->modalHeading('Reset Onboarding')
                ->modalDescription('This will unlock the setup wizard and lock admissions again. Use this if you need to reconfigure the system.')
                ->modalSubmitActionLabel('Yes, Reset')
                ->action(function () {
                    AppSetting::set('onboarding_completed', false, 'boolean');

                    Notification::make()
                        ->title('Onboarding reset')
                        ->body('Setup wizard is now unlocked. Complete all steps to enable admissions.')
                        ->warning()
                        ->send();

                    $this->redirect(static::getUrl());
                }),
        ];
    }

    /**
     * Show this page in navigation only if onboarding is incomplete
     * or if user wants to reconfigure
     */
    public static function shouldRegisterNavigation(): bool
    {
        // Always show setup wizard in navigation
        return true;
    }

    /**
     * Add a badge to show completion status
     */
    public static function getNavigationBadge(): ?string
    {
        $percentage = OnboardingChecklist::completionPercentage();

        if ($percentage === 100) {
            return 'Complete';
        }

        return $percentage . '%';
    }

    /**
     * Color the badge based on completion
     */
    public static function getNavigationBadgeColor(): ?string
    {
        $percentage = OnboardingChecklist::completionPercentage();

        if ($percentage === 100) {
            return 'success';
        }

        if ($percentage >= 50) {
            return 'warning';
        }

        return 'danger';
    }
}
