<?php

namespace App\Filament\Resources\MonthlyFeeGenerations\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class MonthlyFeeGenerationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('academicYear.name')
                    ->label('Academic year'),
                TextEntry::make('class_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('section.name')
                    ->label('Section')
                    ->placeholder('-'),
                TextEntry::make('month')
                    ->numeric(),
                TextEntry::make('year')
                    ->numeric(),
                TextEntry::make('due_date')
                    ->date(),
                TextEntry::make('students_count')
                    ->numeric(),
                TextEntry::make('total_amount')
                    ->numeric(),
                TextEntry::make('generated_by')
                    ->numeric(),
                TextEntry::make('generated_at')
                    ->dateTime(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
