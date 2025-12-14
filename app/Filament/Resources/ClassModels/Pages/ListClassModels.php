<?php

namespace App\Filament\Resources\ClassModels\Pages;

use App\Filament\Resources\ClassModels\ClassModelResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListClassModels extends ListRecords
{
    protected static string $resource = ClassModelResource::class;

    protected static ?string $title = 'Classes';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Create Class'),
        ];
    }
}
