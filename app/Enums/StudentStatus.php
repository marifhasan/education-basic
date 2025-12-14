<?php

namespace App\Enums;

enum StudentStatus: string
{
    case ACTIVE = 'active';
    case ALUMNI = 'alumni';
    case DROPOUT = 'dropout';
    case TRANSFERRED = 'transferred';

    public function getLabel(): string
    {
        return match ($this) {
            self::ACTIVE => 'Active',
            self::ALUMNI => 'Alumni',
            self::DROPOUT => 'Dropout',
            self::TRANSFERRED => 'Transferred',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::ACTIVE => 'success',
            self::ALUMNI => 'info',
            self::DROPOUT => 'danger',
            self::TRANSFERRED => 'warning',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::ACTIVE => 'heroicon-o-check-circle',
            self::ALUMNI => 'heroicon-o-academic-cap',
            self::DROPOUT => 'heroicon-o-x-circle',
            self::TRANSFERRED => 'heroicon-o-arrow-right-circle',
        };
    }
}
