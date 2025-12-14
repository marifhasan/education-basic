<?php

namespace App\Filament\Resources\FeeItems\Schemas;

use App\Enums\FeeType;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class FeeItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('code')
                    ->required(),
                Select::make('fee_type')
                    ->options(FeeType::class)
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                Toggle::make('is_mandatory')
                    ->required(),
                Toggle::make('is_active')
                    ->required(),
                TextInput::make('display_order')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
