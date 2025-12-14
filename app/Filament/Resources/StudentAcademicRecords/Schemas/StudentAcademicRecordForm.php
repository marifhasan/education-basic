<?php

namespace App\Filament\Resources\StudentAcademicRecords\Schemas;

use App\Enums\EnrollmentStatus;
use Filament\Forms\Components\DatePicker;
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
                Select::make('student_id')
                    ->relationship('student', 'id')
                    ->required(),
                Select::make('academic_year_id')
                    ->relationship('academicYear', 'name')
                    ->required(),
                TextInput::make('class_id')
                    ->required()
                    ->numeric(),
                Select::make('section_id')
                    ->relationship('section', 'name')
                    ->required(),
                TextInput::make('roll_number')
                    ->required(),
                Select::make('enrollment_status')
                    ->options(EnrollmentStatus::class)
                    ->default('enrolled')
                    ->required(),
                DatePicker::make('enrollment_date')
                    ->required(),
                DatePicker::make('promotion_date'),
                TextInput::make('final_percentage')
                    ->numeric(),
                TextInput::make('final_gpa')
                    ->numeric(),
                TextInput::make('final_grade'),
                TextInput::make('class_rank')
                    ->numeric(),
                Textarea::make('remarks')
                    ->columnSpanFull(),
            ]);
    }
}
