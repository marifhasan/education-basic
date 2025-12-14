<?php

namespace App\Enums;

enum AdmissionStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case ADMITTED = 'admitted';

    public function getLabel(): string
    {
        return match ($this) {
            self::PENDING => 'Pending Review',
            self::APPROVED => 'Approved',
            self::REJECTED => 'Rejected',
            self::ADMITTED => 'Admitted',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::PENDING => 'warning',
            self::APPROVED => 'info',
            self::REJECTED => 'danger',
            self::ADMITTED => 'success',
        };
    }

    public function canEdit(): bool
    {
        return $this === self::PENDING;
    }

    public function canApprove(): bool
    {
        return $this === self::PENDING;
    }

    public function canReject(): bool
    {
        return $this === self::PENDING;
    }

    public function canAdmit(): bool
    {
        return $this === self::APPROVED;
    }
}
