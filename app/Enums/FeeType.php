<?php

namespace App\Enums;

enum FeeType: string
{
    case ADMISSION = 'admission';
    case MONTHLY = 'monthly';
    case ANNUAL = 'annual';
    case ONE_TIME = 'one_time';

    public function getLabel(): string
    {
        return match ($this) {
            self::ADMISSION => 'Admission Fee',
            self::MONTHLY => 'Monthly Fee',
            self::ANNUAL => 'Annual Fee',
            self::ONE_TIME => 'One Time Fee',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::ADMISSION => 'warning',
            self::MONTHLY => 'info',
            self::ANNUAL => 'success',
            self::ONE_TIME => 'gray',
        };
    }
}
