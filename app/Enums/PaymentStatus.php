<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case UNPAID = 'unpaid';
    case PAID = 'paid';
    case WAIVED = 'waived';

    public function getLabel(): string
    {
        return match ($this) {
            self::UNPAID => 'Unpaid',
            self::PAID => 'Paid',
            self::WAIVED => 'Waived',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::UNPAID => 'danger',
            self::PAID => 'success',
            self::WAIVED => 'gray',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::UNPAID => 'heroicon-o-clock',
            self::PAID => 'heroicon-o-check-circle',
            self::WAIVED => 'heroicon-o-minus-circle',
        };
    }
}
