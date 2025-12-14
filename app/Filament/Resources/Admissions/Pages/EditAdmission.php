<?php

namespace App\Filament\Resources\Admissions\Pages;

use App\Filament\Resources\Admissions\AdmissionResource;
use App\Http\Middleware\EnsureOnboardingComplete;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditAdmission extends EditRecord
{
    protected static string $resource = AdmissionResource::class;

    public function getMiddleware(): array
    {
        return [
            ...parent::getMiddleware(),
            EnsureOnboardingComplete::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
