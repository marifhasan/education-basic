<?php

namespace App\Enums;

enum EnrollmentStatus: string
{
    case ENROLLED = 'enrolled';
    case PROMOTED = 'promoted';
    case DETAINED = 'detained';
    case WITHDRAWN = 'withdrawn';

    public function getLabel(): string
    {
        return match ($this) {
            self::ENROLLED => 'Enrolled',
            self::PROMOTED => 'Promoted',
            self::DETAINED => 'Detained',
            self::WITHDRAWN => 'Withdrawn',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::ENROLLED => 'success',
            self::PROMOTED => 'info',
            self::DETAINED => 'warning',
            self::WITHDRAWN => 'danger',
        };
    }
}
