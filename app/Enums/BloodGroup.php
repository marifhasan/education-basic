<?php

namespace App\Enums;

enum BloodGroup: string
{
    case A_POSITIVE = 'A+';
    case A_NEGATIVE = 'A-';
    case B_POSITIVE = 'B+';
    case B_NEGATIVE = 'B-';
    case AB_POSITIVE = 'AB+';
    case AB_NEGATIVE = 'AB-';
    case O_POSITIVE = 'O+';
    case O_NEGATIVE = 'O-';

    public function getLabel(): string
    {
        return $this->value;
    }

    public function getColor(): string
    {
        return match ($this) {
            self::O_POSITIVE, self::O_NEGATIVE => 'danger',
            self::A_POSITIVE, self::A_NEGATIVE => 'success',
            self::B_POSITIVE, self::B_NEGATIVE => 'info',
            self::AB_POSITIVE, self::AB_NEGATIVE => 'warning',
        };
    }
}
