<?php

namespace App\Filament\Resources\MonthlyFeeGenerations\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MonthlyFeeGenerationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('academic_year_id')
                    ->relationship('academicYear', 'name')
                    ->required(),
                TextInput::make('class_id')
                    ->numeric(),
                Select::make('section_id')
                    ->relationship('section', 'name'),
                TextInput::make('month')
                    ->required()
                    ->numeric(),
                TextInput::make('year')
                    ->required()
                    ->numeric(),
                DatePicker::make('due_date')
                    ->required(),
                TextInput::make('students_count')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('total_amount')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('generated_by')
                    ->required()
                    ->numeric(),
                DateTimePicker::make('generated_at')
                    ->required(),
            ]);
    }
}
