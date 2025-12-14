<?php

namespace App\Filament\Resources\DiscountTypes\Pages;

use App\Filament\Resources\DiscountTypes\DiscountTypeResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDiscountType extends ViewRecord
{
    protected static string $resource = DiscountTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
