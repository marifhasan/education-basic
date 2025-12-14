<?php

namespace App\Filament\Resources\Families\Schemas;

use App\Models\Family;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class FamilyInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('family_code'),
                TextEntry::make('father_name'),
                TextEntry::make('father_nid')
                    ->placeholder('-'),
                TextEntry::make('father_phone')
                    ->placeholder('-'),
                TextEntry::make('father_occupation')
                    ->placeholder('-'),
                TextEntry::make('father_income')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('mother_name'),
                TextEntry::make('mother_nid')
                    ->placeholder('-'),
                TextEntry::make('mother_phone')
                    ->placeholder('-'),
                TextEntry::make('mother_occupation')
                    ->placeholder('-'),
                TextEntry::make('guardian_name')
                    ->placeholder('-'),
                TextEntry::make('guardian_relation')
                    ->placeholder('-'),
                TextEntry::make('guardian_phone')
                    ->placeholder('-'),
                TextEntry::make('guardian_nid')
                    ->placeholder('-'),
                TextEntry::make('primary_phone'),
                TextEntry::make('secondary_phone')
                    ->placeholder('-'),
                TextEntry::make('email')
                    ->label('Email address')
                    ->placeholder('-'),
                TextEntry::make('present_address')
                    ->columnSpanFull(),
                TextEntry::make('permanent_address')
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
                    ->visible(fn (Family $record): bool => $record->trashed()),
            ]);
    }
}
