<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case CASH = 'cash';
    case BANK_TRANSFER = 'bank_transfer';
    case MOBILE_BANKING = 'mobile_banking';
    case CARD = 'card';

    public function getLabel(): string
    {
        return match ($this) {
            self::CASH => 'Cash',
            self::BANK_TRANSFER => 'Bank Transfer',
            self::MOBILE_BANKING => 'Mobile Banking',
            self::CARD => 'Card',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::CASH => 'gray',
            self::BANK_TRANSFER => 'info',
            self::MOBILE_BANKING => 'success',
            self::CARD => 'warning',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::CASH => 'heroicon-o-banknotes',
            self::BANK_TRANSFER => 'heroicon-o-building-library',
            self::MOBILE_BANKING => 'heroicon-o-device-phone-mobile',
            self::CARD => 'heroicon-o-credit-card',
        };
    }
}
