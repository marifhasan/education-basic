<?php

namespace App\Filament\Resources\FeeItems\Schemas;

use App\Enums\FeeType;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section as FormSection;
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
                Grid::make()
                    ->schema([
                        FormSection::make('Fee Item Details')
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('e.g., Tuition Fee, Library Fee')
                                    ->columnSpan(2),

                                TextInput::make('code')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(50)
                                    ->placeholder('e.g., TUITION, LIBRARY')
                                    ->alphaDash()
                                    ->columnSpan(1),

                                Select::make('fee_type')
                                    ->label('Fee Type')
                                    ->options(FeeType::class)
                                    ->required()
                                    ->native(false)
                                    ->columnSpan(1),

                                Textarea::make('description')
                                    ->placeholder('Brief description of this fee item')
                                    ->rows(3)
                                    ->columnSpanFull(),

                                TextInput::make('display_order')
                                    ->label('Display Order')
                                    ->numeric()
                                    ->default(0)
                                    ->minValue(0)
                                    ->helperText('Lower numbers appear first')
                                    ->columnSpan(1),
                            ])
                            ->columns(2),

                        FormSection::make('Settings')
                            ->schema([
                                Toggle::make('is_mandatory')
                                    ->label('Mandatory')
                                    ->default(false)
                                    ->helperText('If enabled, this fee must be paid')
                                    ->inline(false),

                                Toggle::make('is_active')
                                    ->label('Active')
                                    ->default(true)
                                    ->helperText('Only active fees appear in fee structures')
                                    ->inline(false),
                            ])
                            ->columns(2),
                    ]),
            ]);
    }
}
