<?php

namespace App\Filament\Resources\Sections\Schemas;

use App\Models\Section;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SectionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('class_id')
                    ->numeric(),
                TextEntry::make('academicYear.name')
                    ->label('Academic year'),
                TextEntry::make('name'),
                TextEntry::make('capacity')
                    ->numeric(),
                TextEntry::make('current_strength')
                    ->numeric(),
                TextEntry::make('last_roll_number')
                    ->numeric(),
                TextEntry::make('classTeacher.name')
                    ->label('Class teacher')
                    ->placeholder('-'),
                IconEntry::make('is_active')
                    ->boolean(),
                IconEntry::make('is_archived')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Section $record): bool => $record->trashed()),
            ]);
    }
}
