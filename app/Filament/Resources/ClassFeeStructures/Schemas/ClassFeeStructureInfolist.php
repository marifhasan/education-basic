<?php

namespace App\Filament\Resources\ClassFeeStructures\Schemas;

use App\Models\ClassFeeStructure;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ClassFeeStructureInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('class_id')
                    ->numeric(),
                TextEntry::make('academicYear.name')
                    ->label('Academic year'),
                TextEntry::make('feeItem.name')
                    ->label('Fee item'),
                TextEntry::make('amount')
                    ->numeric(),
                IconEntry::make('is_active')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (ClassFeeStructure $record): bool => $record->trashed()),
            ]);
    }
}
