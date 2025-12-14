<?php

namespace App\Filament\Resources\ClassFeeStructures\Schemas;

use App\Services\AcademicYearContext;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section as FormSection;
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
                Grid::make()
                    ->schema([
                        FormSection::make('Fee Structure Details')
                            ->schema([
                                Select::make('academic_year_id')
                                    ->relationship('academicYear', 'name')
                                    ->required()
                                    ->default(AcademicYearContext::getSelectedYearId())
                                    ->searchable()
                                    ->preload()
                                    ->columnSpan(2),

                                Select::make('class_id')
                                    ->relationship('classModel', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->columnSpan(1),

                                Select::make('fee_item_id')
                                    ->relationship('feeItem', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->columnSpan(1),

                                TextInput::make('amount')
                                    ->label('Amount (BDT)')
                                    ->required()
                                    ->numeric()
                                    ->minValue(0)
                                    ->prefix('৳')
                                    ->placeholder('0.00')
                                    ->columnSpan(1),

                                Toggle::make('is_active')
                                    ->label('Active')
                                    ->default(true)
                                    ->helperText('Only active structures are used for fee calculation')
                                    ->inline(false)
                                    ->columnSpan(1),
                            ])
                            ->columns(2),
                    ]),
            ]);
    }
}
