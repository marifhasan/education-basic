<?php

namespace App\Filament\Resources\ClassFeeStructures\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ClassFeeStructureForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('class_id')
                    ->required()
                    ->numeric(),
                Select::make('academic_year_id')
                    ->relationship('academicYear', 'name')
                    ->required(),
                Select::make('fee_item_id')
                    ->relationship('feeItem', 'name')
                    ->required(),
                TextInput::make('amount')
                    ->required()
                    ->numeric(),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
