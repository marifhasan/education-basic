<?php

namespace App\Filament\Resources\StudentAcademicRecords\Schemas;

use App\Models\StudentAcademicRecord;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class StudentAcademicRecordInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('student.id')
                    ->label('Student'),
                TextEntry::make('academicYear.name')
                    ->label('Academic year'),
                TextEntry::make('class_id')
                    ->numeric(),
                TextEntry::make('section.name')
                    ->label('Section'),
                TextEntry::make('roll_number'),
                TextEntry::make('enrollment_status')
                    ->badge(),
                TextEntry::make('enrollment_date')
                    ->date(),
                TextEntry::make('promotion_date')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('final_percentage')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('final_gpa')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('final_grade')
                    ->placeholder('-'),
                TextEntry::make('class_rank')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('remarks')
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
                    ->visible(fn (StudentAcademicRecord $record): bool => $record->trashed()),
            ]);
    }
}
