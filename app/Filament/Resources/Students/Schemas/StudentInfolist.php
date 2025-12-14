<?php

namespace App\Filament\Resources\Students\Schemas;

use App\Models\Student;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class StudentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('student_code'),
                TextEntry::make('family.id')
                    ->label('Family'),
                TextEntry::make('first_name'),
                TextEntry::make('last_name'),
                TextEntry::make('full_name'),
                TextEntry::make('gender')
                    ->badge(),
                TextEntry::make('date_of_birth')
                    ->date(),
                TextEntry::make('birth_certificate_number')
                    ->placeholder('-'),
                TextEntry::make('religion')
                    ->placeholder('-'),
                TextEntry::make('blood_group')
                    ->placeholder('-'),
                TextEntry::make('photo_path')
                    ->placeholder('-'),
                TextEntry::make('previous_school')
                    ->placeholder('-'),
                TextEntry::make('previous_class')
                    ->placeholder('-'),
                TextEntry::make('previous_gpa')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('medical_conditions')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('allergies')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('admission_date')
                    ->date(),
                TextEntry::make('leaving_date')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('leaving_reason')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Student $record): bool => $record->trashed()),
            ]);
    }
}
