<?php

namespace App\Filament\Resources\AcademicYears\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AcademicYearForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(1)->schema([
                    Section::make('Academic Year Details')
                        ->schema([
                            Select::make('curriculum_id')
                                ->label('Curriculum')
                                ->relationship('curriculum', 'name')
                                ->required()
                                ->searchable()
                                ->preload()
                                ->helperText('Select the curriculum this academic year belongs to'),

                            TextInput::make('name')
                                ->label('Academic Year Name')
                                ->required()
                                ->maxLength(255)
                                ->placeholder('e.g., 2024-2025')
                                ->helperText('Format: YYYY-YYYY'),

                            DatePicker::make('start_date')
                                ->label('Start Date')
                                ->required()
                                ->native(false)
                                ->displayFormat('d M Y'),

                            DatePicker::make('end_date')
                                ->label('End Date')
                                ->required()
                                ->native(false)
                                ->displayFormat('d M Y')
                                ->after('start_date'),
                        ])
                        ->columns(2),

                    Section::make('Status')
                        ->schema([
                            Toggle::make('is_active')
                                ->label('Active')
                                ->helperText('Only one academic year can be active per curriculum')
                                ->default(false),

                            Toggle::make('is_closed')
                                ->label('Closed')
                                ->helperText('Mark as closed when the academic year ends')
                                ->default(false),
                        ])
                        ->columns(2),
                ]),
            ]);
    }
}
