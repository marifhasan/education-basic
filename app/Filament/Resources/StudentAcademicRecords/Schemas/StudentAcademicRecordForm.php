<?php

namespace App\Filament\Resources\StudentAcademicRecords\Schemas;

use App\Enums\EnrollmentStatus;
use App\Services\AcademicYearContext;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section as FormSection;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class StudentAcademicRecordForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make()
                    ->schema([
                        FormSection::make('Enrollment Information')
                            ->schema([
                                Select::make('student_id')
                                    ->relationship('student', 'full_name')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->columnSpan(2),

                                Select::make('academic_year_id')
                                    ->relationship('academicYear', 'name')
                                    ->required()
                                    ->default(AcademicYearContext::getSelectedYearId())
                                    ->searchable()
                                    ->preload()
                                    ->live()
                                    ->columnSpan(1),

                                Select::make('class_id')
                                    ->relationship('classModel', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->live()
                                    ->columnSpan(1),

                                Select::make('section_id')
                                    ->relationship('section', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->columnSpan(1),

                                TextInput::make('roll_number')
                                    ->label('Roll Number')
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->helperText('Auto-generated when record is created')
                                    ->columnSpan(1),

                                Select::make('enrollment_status')
                                    ->label('Status')
                                    ->options(EnrollmentStatus::class)
                                    ->default('enrolled')
                                    ->required()
                                    ->native(false)
                                    ->columnSpan(1),

                                DatePicker::make('enrollment_date')
                                    ->required()
                                    ->default(now())
                                    ->columnSpan(1),
                            ])
                            ->columns(2),

                        FormSection::make('Academic Performance')
                            ->schema([
                                TextInput::make('final_percentage')
                                    ->label('Final Percentage')
                                    ->numeric()
                                    ->suffix('%')
                                    ->minValue(0)
                                    ->maxValue(100)
                                    ->columnSpan(1),

                                TextInput::make('final_gpa')
                                    ->label('Final GPA')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(5)
                                    ->step(0.01)
                                    ->columnSpan(1),

                                TextInput::make('final_grade')
                                    ->label('Final Grade')
                                    ->maxLength(10)
                                    ->placeholder('e.g., A+, A, B+')
                                    ->columnSpan(1),

                                TextInput::make('class_rank')
                                    ->label('Class Rank')
                                    ->numeric()
                                    ->minValue(1)
                                    ->columnSpan(1),

                                DatePicker::make('promotion_date')
                                    ->label('Promotion Date')
                                    ->columnSpan(1),

                                Textarea::make('remarks')
                                    ->rows(3)
                                    ->columnSpanFull(),
                            ])
                            ->columns(2)
                            ->collapsible(),
                    ]),
            ]);
    }
}
