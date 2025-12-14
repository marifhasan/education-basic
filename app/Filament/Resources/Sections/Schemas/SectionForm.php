<?php

namespace App\Filament\Resources\Sections\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class SectionForm
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
                TextInput::make('name')
                    ->required(),
                TextInput::make('capacity')
                    ->required()
                    ->numeric()
                    ->default(40),
                TextInput::make('current_strength')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('last_roll_number')
                    ->required()
                    ->numeric()
                    ->default(0),
                Select::make('class_teacher_id')
                    ->relationship('classTeacher', 'name'),
                Toggle::make('is_active')
                    ->required(),
                Toggle::make('is_archived')
                    ->required(),
            ]);
    }
}
