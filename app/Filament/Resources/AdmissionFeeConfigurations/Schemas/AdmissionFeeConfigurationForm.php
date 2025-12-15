<?php

namespace App\Filament\Resources\AdmissionFeeConfigurations\Schemas;

use Filament\Forms;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class AdmissionFeeConfigurationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Configuration Details')
                ->schema([
                    Select::make('academic_year_id')
                        ->relationship('academicYear', 'name')
                        ->label('Academic Year')
                        ->required()
                        ->live()
                        ->afterStateUpdated(fn ($state, callable $set) =>
                            $set('class_id', null)
                        ),

                    Select::make('class_id')
                        ->relationship(
                            'classModel',
                            'name',
                            fn (Builder $query, callable $get) =>
                                $query->whereDoesntHave('admissionFeeConfigurations',
                                    fn (Builder $query) =>
                                        $query->where('academic_year_id', $get('academic_year_id'))
                                )
                        )
                        ->label('Class')
                        ->required()
                        ->searchable()
                        ->preload(),

                    TextInput::make('total_admission_fee')
                        ->label('Total Admission Fee')
                        ->prefix('BDT')
                        ->disabled()
                        ->dehydrated(false)
                        ->formatStateUsing(fn ($state) => number_format($state, 2)),

                    Toggle::make('is_active')
                        ->label('Active')
                        ->default(true)
                        ->helperText('Only active configurations will be used for new admissions'),
                ])
                ->columns(2),
        ]);
    }
}