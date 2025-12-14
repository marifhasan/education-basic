<?php

namespace App\Filament\Resources\DiscountTypes\Pages;

use App\Filament\Resources\DiscountTypes\DiscountTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDiscountTypes extends ListRecords
{
    protected static string $resource = DiscountTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
