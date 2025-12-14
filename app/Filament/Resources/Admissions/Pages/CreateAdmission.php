<?php

namespace App\Filament\Resources\Admissions\Pages;

use App\Filament\Resources\Admissions\AdmissionResource;
use App\Http\Middleware\EnsureOnboardingComplete;
use Filament\Resources\Pages\CreateRecord;

class CreateAdmission extends CreateRecord
{
    protected static string $resource = AdmissionResource::class;

    public function getMiddleware(): array
    {
        return [
            ...parent::getMiddleware(),
            EnsureOnboardingComplete::class,
        ];
    }
}
