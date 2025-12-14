<?php

namespace App\Enums;

enum DiscountCalculationType: string
{
    case PERCENTAGE = 'percentage';
    case FIXED = 'fixed';

    public function getLabel(): string
    {
        return match ($this) {
            self::PERCENTAGE => 'Percentage',
            self::FIXED => 'Fixed Amount',
        };
    }

    public function calculate(float $amount, float $value): float
    {
        return match ($this) {
            self::PERCENTAGE => ($amount * $value) / 100,
            self::FIXED => $value,
        };
    }
}
