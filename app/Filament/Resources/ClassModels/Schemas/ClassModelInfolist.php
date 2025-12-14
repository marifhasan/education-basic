<?php

namespace App\Filament\Resources\ClassModels\Schemas;

use App\Models\ClassModel;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ClassModelInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('curriculum.name')
                    ->label('Curriculum'),
                TextEntry::make('name'),
                TextEntry::make('code'),
                TextEntry::make('order')
                    ->numeric(),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
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
                    ->visible(fn (ClassModel $record): bool => $record->trashed()),
            ]);
    }
}
