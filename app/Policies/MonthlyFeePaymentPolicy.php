<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\MonthlyFeePayment;
use Illuminate\Auth\Access\HandlesAuthorization;

class MonthlyFeePaymentPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:MonthlyFeePayment');
    }

    public function view(AuthUser $authUser, MonthlyFeePayment $monthlyFeePayment): bool
    {
        return $authUser->can('View:MonthlyFeePayment');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:MonthlyFeePayment');
    }

    public function update(AuthUser $authUser, MonthlyFeePayment $monthlyFeePayment): bool
    {
        return $authUser->can('Update:MonthlyFeePayment');
    }

    public function delete(AuthUser $authUser, MonthlyFeePayment $monthlyFeePayment): bool
    {
        return $authUser->can('Delete:MonthlyFeePayment');
    }

    public function restore(AuthUser $authUser, MonthlyFeePayment $monthlyFeePayment): bool
    {
        return $authUser->can('Restore:MonthlyFeePayment');
    }

    public function forceDelete(AuthUser $authUser, MonthlyFeePayment $monthlyFeePayment): bool
    {
        return $authUser->can('ForceDelete:MonthlyFeePayment');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:MonthlyFeePayment');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:MonthlyFeePayment');
    }

    public function replicate(AuthUser $authUser, MonthlyFeePayment $monthlyFeePayment): bool
    {
        return $authUser->can('Replicate:MonthlyFeePayment');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:MonthlyFeePayment');
    }

}