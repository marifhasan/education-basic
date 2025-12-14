<?php

namespace App\Filament\Resources\Families\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FamilyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(1)->schema([
                    Section::make('Family Code')
                        ->schema([
                            TextInput::make('family_code')
                                ->label('Family Code')
                                ->disabled()
                                ->dehydrated(false)
                                ->placeholder('Auto-generated')
                                ->helperText('Will be auto-generated: FAM-00001'),
                        ])
                        ->columns(1)
                        ->visible(fn ($record) => $record === null),

                    Section::make('Father Information')
                        ->schema([
                            TextInput::make('father_name')
                                ->label('Full Name')
                                ->required()
                                ->maxLength(255),

                            TextInput::make('father_nid')
                                ->label('National ID / Passport')
                                ->maxLength(50),

                            TextInput::make('father_phone')
                                ->label('Phone Number')
                                ->tel()
                                ->maxLength(20),

                            TextInput::make('father_occupation')
                                ->label('Occupation')
                                ->maxLength(255),

                            TextInput::make('father_income')
                                ->label('Monthly Income')
                                ->numeric()
                                ->prefix('BDT')
                                ->minValue(0),
                        ])
                        ->columns(2),

                    Section::make('Mother Information')
                        ->schema([
                            TextInput::make('mother_name')
                                ->label('Full Name')
                                ->required()
                                ->maxLength(255),

                            TextInput::make('mother_nid')
                                ->label('National ID / Passport')
                                ->maxLength(50),

                            TextInput::make('mother_phone')
                                ->label('Phone Number')
                                ->tel()
                                ->maxLength(20),

                            TextInput::make('mother_occupation')
                                ->label('Occupation')
                                ->maxLength(255),
                        ])
                        ->columns(2),

                    Section::make('Guardian Information (if applicable)')
                        ->schema([
                            TextInput::make('guardian_name')
                                ->label('Full Name')
                                ->maxLength(255),

                            TextInput::make('guardian_relation')
                                ->label('Relation to Student')
                                ->placeholder('e.g., Uncle, Aunt, Grandfather')
                                ->maxLength(100),

                            TextInput::make('guardian_phone')
                                ->label('Phone Number')
                                ->tel()
                                ->maxLength(20),

                            TextInput::make('guardian_nid')
                                ->label('National ID / Passport')
                                ->maxLength(50),
                        ])
                        ->columns(2)
                        ->collapsible()
                        ->collapsed(),

                    Section::make('Contact Information')
                        ->schema([
                            TextInput::make('primary_phone')
                                ->label('Primary Phone')
                                ->tel()
                                ->required()
                                ->maxLength(20)
                                ->helperText('Main contact number for communication'),

                            TextInput::make('secondary_phone')
                                ->label('Secondary Phone')
                                ->tel()
                                ->maxLength(20),

                            TextInput::make('email')
                                ->label('Email Address')
                                ->email()
                                ->maxLength(255),

                            Textarea::make('present_address')
                                ->label('Present Address')
                                ->required()
                                ->rows(3)
                                ->columnSpanFull(),

                            Toggle::make('same_as_present')
                                ->label('Permanent address same as present address')
                                ->live()
                                ->afterStateUpdated(function ($state, Get $get, $set) {
                                    if ($state) {
                                        $set('permanent_address', $get('present_address'));
                                    }
                                })
                                ->columnSpanFull(),

                            Textarea::make('permanent_address')
                                ->label('Permanent Address')
                                ->rows(3)
                                ->disabled(fn (Get $get) => $get('same_as_present'))
                                ->columnSpanFull(),
                        ])
                        ->columns(3),
                ]),
            ]);
    }
}
