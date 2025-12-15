<?php

namespace App\Filament\Resources\AdmissionFeeConfigurations\Pages;

use App\Filament\Resources\AdmissionFeeConfigurations\AdmissionFeeConfigurationResource;
use App\Models\AdmissionFeeItem;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateAdmissionFeeConfiguration extends CreateRecord
{
    protected static string $resource = AdmissionFeeConfigurationResource::class;

    protected function afterCreate(): void
    {
        // Create default fee items
        $configuration = $this->record;

        // Create common admission fee items
        $defaultItems = [
            [
                'item_name' => 'Admission Form Fee',
                'amount' => 500,
                'description' => 'One-time fee for admission form processing',
            ],
            [
                'item_name' => 'Registration Fee',
                'amount' => 1000,
                'description' => 'Registration and documentation charges',
            ],
            [
                'item_name' => 'Library Deposit',
                'amount' => 500,
                'description' => 'Refundable library security deposit',
            ],
            [
                'item_name' => 'First Month Tuition Fee',
                'amount' => 0, // Will be set based on class fee structure
                'description' => 'Tuition fee for the first month',
            ],
        ];

        foreach ($defaultItems as $item) {
            $configuration->admissionFeeItems()->create($item);
        }

        // Update total fee
        $configuration->updateTotalFee();
    }
}