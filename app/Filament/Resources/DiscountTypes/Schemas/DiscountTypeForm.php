<?php

namespace App\Filament\Resources\DiscountTypes\Schemas;

use App\Enums\DiscountCalculationType;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section as FormSection;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class DiscountTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make()
                    ->schema([
                        FormSection::make('Discount Type Details')
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('e.g., Sibling Discount, Merit Scholarship')
                                    ->columnSpan(2),

                                TextInput::make('code')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(50)
                                    ->placeholder('e.g., SIBLING, MERIT')
                                    ->alphaDash()
                                    ->columnSpan(1),

                                Select::make('calculation_type')
                                    ->label('Calculation Type')
                                    ->options(DiscountCalculationType::class)
                                    ->required()
                                    ->native(false)
                                    ->live()
                                    ->columnSpan(1),

                                TextInput::make('default_value')
                                    ->label('Default Value')
                                    ->required()
                                    ->numeric()
                                    ->default(0)
                                    ->minValue(0)
                                    ->suffix(fn(Get $get): string => $get('calculation_type') === 'percentage' ? '%' : 'BDT')
                                    ->helperText(fn(Get $get): string => $get('calculation_type') === 'percentage' ? 'Enter percentage (e.g., 10 for 10%)' : 'Enter fixed amount in BDT')
                                    ->columnSpan(1),

                                Textarea::make('description')
                                    ->placeholder('Brief description of this discount type')
                                    ->rows(3)
                                    ->columnSpanFull(),
                            ])
                            ->columns(2),

                        FormSection::make('Settings')
                            ->schema([
                                Toggle::make('is_active')
                                    ->label('Active')
                                    ->default(true)
                                    ->helperText('Only active discount types can be applied')
                                    ->inline(false),

                                Toggle::make('requires_approval')
                                    ->label('Requires Approval')
                                    ->default(false)
                                    ->helperText('If enabled, discounts must be approved before applying')
                                    ->inline(false),
                            ])
                            ->columns(2),
                    ]),
            ]);
    }
}
