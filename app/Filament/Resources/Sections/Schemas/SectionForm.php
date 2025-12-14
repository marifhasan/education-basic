<?php

namespace App\Filament\Resources\Sections\Schemas;

use App\Services\AcademicYearContext;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section as FormSection;
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
                Grid::make()
                    ->schema([
                        FormSection::make('Section Information')
                            ->schema([
                                Select::make('academic_year_id')
                                    ->relationship('academicYear', 'name')
                                    ->required()
                                    ->default(AcademicYearContext::getSelectedYearId())
                                    ->searchable()
                                    ->preload()
                                    ->live()
                                    ->columnSpan(2),

                                Select::make('class_id')
                                    ->relationship('classModel', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->columnSpan(2),

                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('e.g., Section A')
                                    ->columnSpan(1),

                                TextInput::make('code')
                                    ->label('Section Code')
                                    ->required()
                                    ->maxLength(1)
                                    ->default('0')
                                    ->placeholder('0-9')
                                    ->helperText('0 = Default, 1-9 = Named sections')
                                    ->columnSpan(1),

                                TextInput::make('capacity')
                                    ->required()
                                    ->numeric()
                                    ->default(40)
                                    ->minValue(1)
                                    ->maxValue(999)
                                    ->suffix('students')
                                    ->columnSpan(1),

                                Select::make('class_teacher_id')
                                    ->relationship('classTeacher', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->placeholder('Select a teacher')
                                    ->columnSpan(1),
                            ])
                            ->columns(2),

                        FormSection::make('Status')
                            ->schema([
                                Toggle::make('is_active')
                                    ->label('Active')
                                    ->default(true)
                                    ->inline(false),

                                Toggle::make('is_archived')
                                    ->label('Archived')
                                    ->default(false)
                                    ->helperText('Archive old sections to hide them from active lists')
                                    ->inline(false),
                            ])
                            ->columns(2),
                    ]),
            ]);
    }
}
