<?php

namespace App\Filament\Resources\Curricula\Schemas;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CurriculumForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Basic Information')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->label('Curriculum Name')
                            ->placeholder('e.g., National Curriculum, Madrasah Curriculum'),
                        TextInput::make('code')
                            ->required()
                            ->maxLength(50)
                            ->unique(ignoreRecord: true)
                            ->label('Curriculum Code')
                            ->placeholder('e.g., NAT, MAD'),
                        Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull()
                            ->maxLength(500),
                    ])
                    ->columns(2),

                Section::make('Academic Year Configuration')
                    ->schema([
                        Select::make('academic_year_start_month')
                            ->required()
                            ->options([
                                1 => 'January', 2 => 'February', 3 => 'March',
                                4 => 'April', 5 => 'May', 6 => 'June',
                                7 => 'July', 8 => 'August', 9 => 'September',
                                10 => 'October', 11 => 'November', 12 => 'December',
                            ])
                            ->default(1)
                            ->label('Academic Year Start Month'),
                        Select::make('academic_year_end_month')
                            ->required()
                            ->options([
                                1 => 'January', 2 => 'February', 3 => 'March',
                                4 => 'April', 5 => 'May', 6 => 'June',
                                7 => 'July', 8 => 'August', 9 => 'September',
                                10 => 'October', 11 => 'November', 12 => 'December',
                            ])
                            ->default(12)
                            ->label('Academic Year End Month'),
                        Toggle::make('is_active')
                            ->default(true)
                            ->label('Active Status')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
