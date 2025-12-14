<?php

namespace App\Filament\Resources\ClassModels\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ClassModelForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(1)->schema([
                    Section::make('Class Information')
                        ->schema([
                            Select::make('curriculum_id')
                                ->label('Curriculum')
                                ->relationship('curriculum', 'name')
                                ->required()
                                ->searchable()
                                ->preload(),

                            TextInput::make('name')
                                ->label('Class Name')
                                ->required()
                                ->maxLength(255)
                                ->placeholder('e.g., Class 1, Grade 5, Nursery'),

                            TextInput::make('code')
                                ->label('Class Code')
                                ->required()
                                ->maxLength(50)
                                ->placeholder('e.g., CLS-1, G5')
                                ->unique(ignoreRecord: true),

                            TextInput::make('order')
                                ->label('Display Order')
                                ->required()
                                ->numeric()
                                ->default(0)
                                ->minValue(0)
                                ->helperText('Used for sorting (lower numbers appear first)'),

                            Textarea::make('description')
                                ->label('Description')
                                ->rows(3)
                                ->columnSpanFull(),

                            Toggle::make('is_active')
                                ->label('Active')
                                ->default(true),
                        ])
                        ->columns(2),
                ]),
            ]);
    }
}
