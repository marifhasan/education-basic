<?php

namespace App\Filament\Resources\DiscountTypes\Schemas;

use App\Enums\DiscountCalculationType;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class DiscountTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('code')
                    ->required(),
                Select::make('calculation_type')
                    ->options(DiscountCalculationType::class)
                    ->required(),
                TextInput::make('default_value')
                    ->required()
                    ->numeric()
                    ->default(0),
                Textarea::make('description')
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->required(),
                Toggle::make('requires_approval')
                    ->required(),
            ]);
    }
}
