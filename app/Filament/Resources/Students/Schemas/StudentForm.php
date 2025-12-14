<?php

namespace App\Filament\Resources\Students\Schemas;

use App\Enums\BloodGroup;
use App\Enums\Gender;
use App\Enums\Religion;
use App\Enums\StudentStatus;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class StudentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(1)->schema([
                    Section::make('Personal Information')
                        ->schema([
                            TextInput::make('first_name')
                                ->label('First Name')
                                ->required()
                                ->maxLength(255)
                                ->live(onBlur: true),

                            TextInput::make('last_name')
                                ->label('Last Name')
                                ->required()
                                ->maxLength(255)
                                ->live(onBlur: true),

                            Select::make('gender')
                                ->options(Gender::class)
                                ->required()
                                ->native(false),

                            DatePicker::make('date_of_birth')
                                ->label('Date of Birth')
                                ->required()
                                ->native(false)
                                ->maxDate(now())
                                ->displayFormat('d M Y'),

                            TextInput::make('birth_certificate_number')
                                ->label('Birth Certificate Number')
                                ->maxLength(50)
                                ->columnSpanFull(),

                            Select::make('religion')
                                ->label('Religion')
                                ->options(Religion::class)
                                ->native(false),

                            Select::make('blood_group')
                                ->label('Blood Group')
                                ->options(BloodGroup::class)
                                ->native(false),

                            FileUpload::make('photo_path')
                                ->label('Student Photo')
                                ->image()
                                ->directory('student-photos')
                                ->imageEditor()
                                ->maxSize(2048)
                                ->columnSpanFull(),
                        ])
                        ->columns(2),

                    Section::make('Admission & Status')
                        ->schema([
                            DatePicker::make('admission_date')
                                ->label('Admission Date')
                                ->required()
                                ->default(now())
                                ->native(false)
                                ->displayFormat('d M Y'),

                            Select::make('status')
                                ->options(StudentStatus::class)
                                ->default(StudentStatus::ACTIVE)
                                ->required()
                                ->native(false),

                            DatePicker::make('leaving_date')
                                ->label('Leaving Date')
                                ->native(false)
                                ->displayFormat('d M Y')
                                ->visible(fn(Get $get) => in_array($get('status'), [
                                    StudentStatus::ALUMNI->value,
                                    StudentStatus::DROPOUT->value,
                                    StudentStatus::TRANSFERRED->value,
                                ])),

                            Textarea::make('leaving_reason')
                                ->label('Leaving Reason')
                                ->rows(3)
                                ->visible(fn(Get $get) => in_array($get('status'), [
                                    StudentStatus::ALUMNI->value,
                                    StudentStatus::DROPOUT->value,
                                    StudentStatus::TRANSFERRED->value,
                                ]))
                                ->columnSpanFull(),
                        ])
                        ->columns(2),
                ]),
                Grid::make(1)->schema([
                    Section::make('Student Code & Family')
                        ->schema([
                            TextInput::make('student_code')
                                ->label('Student Code')
                                ->disabled()
                                ->dehydrated(false)
                                ->placeholder('Auto-generated')
                                ->helperText('Will be auto-generated: STD-00001'),

                            Select::make('family_id')
                                ->label('Family')
                                ->relationship('family', 'family_code')
                                ->searchable(['family_code', 'father_name', 'father_phone'])
                                ->getOptionLabelFromRecordUsing(fn($record) => "{$record->family_code} - {$record->father_name}")
                                ->required()
                                ->preload(),
                        ])
                        ->columns(2),
                    Section::make('Previous Education')
                        ->schema([
                            TextInput::make('previous_school')
                                ->label('Previous School Name')
                                ->maxLength(255),

                            TextInput::make('previous_class')
                                ->label('Previous Class/Grade')
                                ->maxLength(100),

                            TextInput::make('previous_gpa')
                                ->label('Previous GPA/Result')
                                ->numeric()
                                ->minValue(0)
                                ->maxValue(5)
                                ->step(0.01),
                        ])
                        ->columns(3)
                        ->collapsible()
                        ->collapsed(),

                    Section::make('Health Information')
                        ->schema([
                            Textarea::make('medical_conditions')
                                ->label('Medical Conditions')
                                ->placeholder('e.g., Asthma, Diabetes, etc.')
                                ->rows(3)
                                ->columnSpanFull(),

                            Textarea::make('allergies')
                                ->label('Allergies')
                                ->placeholder('e.g., Peanuts, Penicillin, etc.')
                                ->rows(3)
                                ->columnSpanFull(),
                        ])
                        ->columns(1)
                        ->collapsible()
                        ->collapsed(),
                ]),
            ]);
    }
}
