<?php

namespace App\Filament\Resources\AdmissionFeeConfigurations\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AdmissionFeeConfigurationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Configuration Details')
                    ->components([
                        TextEntry::make('academicYear.name')
                            ->label('Academic Year'),

                        TextEntry::make('classModel.name')
                            ->label('Class'),

                        TextEntry::make('total_admission_fee')
                            ->label('Total Admission Fee')
                            ->money('BDT'),

                        IconEntry::make('is_active')
                            ->label('Active')
                            ->boolean(),

                        TextEntry::make('admissionFeeItems.count')
                            ->label('Fee Items')
                            ->state(fn ($record) => $record->admissionFeeItems()->count()),
                    ])
                    ->columns(2),
            ]);
    }
}