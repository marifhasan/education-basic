<?php

namespace App\Filament\Resources\StudentAcademicRecords\Pages;

use App\Filament\Resources\StudentAcademicRecords\StudentAcademicRecordResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewStudentAcademicRecord extends ViewRecord
{
    protected static string $resource = StudentAcademicRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
