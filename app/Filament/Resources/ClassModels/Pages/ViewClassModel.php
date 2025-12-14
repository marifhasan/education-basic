<?php

namespace App\Filament\Resources\ClassModels\Pages;

use App\Filament\Resources\ClassModels\ClassModelResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewClassModel extends ViewRecord
{
    protected static string $resource = ClassModelResource::class;
    
    protected static ?string $title = 'Class Details';

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()->label('Edit Class'),
        ];
    }
}
