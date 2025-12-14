<?php

namespace App\Enums;

enum Religion: string
{
    case ISLAM = 'islam';
    case HINDUISM = 'hinduism';
    case BUDDHISM = 'buddhism';
    case CHRISTIANITY = 'christianity';
    case OTHER = 'other';

    public function getLabel(): string
    {
        return match ($this) {
            self::ISLAM => 'Islam',
            self::HINDUISM => 'Hinduism',
            self::BUDDHISM => 'Buddhism',
            self::CHRISTIANITY => 'Christianity',
            self::OTHER => 'Other',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::ISLAM => 'success',
            self::HINDUISM => 'warning',
            self::BUDDHISM => 'info',
            self::CHRISTIANITY => 'primary',
            self::OTHER => 'gray',
        };
    }
}
