<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\MonthlyFeeGeneration;
use Illuminate\Auth\Access\HandlesAuthorization;

class MonthlyFeeGenerationPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:MonthlyFeeGeneration');
    }

    public function view(AuthUser $authUser, MonthlyFeeGeneration $monthlyFeeGeneration): bool
    {
        return $authUser->can('View:MonthlyFeeGeneration');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:MonthlyFeeGeneration');
    }

    public function update(AuthUser $authUser, MonthlyFeeGeneration $monthlyFeeGeneration): bool
    {
        return $authUser->can('Update:MonthlyFeeGeneration');
    }

    public function delete(AuthUser $authUser, MonthlyFeeGeneration $monthlyFeeGeneration): bool
    {
        return $authUser->can('Delete:MonthlyFeeGeneration');
    }

    public function restore(AuthUser $authUser, MonthlyFeeGeneration $monthlyFeeGeneration): bool
    {
        return $authUser->can('Restore:MonthlyFeeGeneration');
    }

    public function forceDelete(AuthUser $authUser, MonthlyFeeGeneration $monthlyFeeGeneration): bool
    {
        return $authUser->can('ForceDelete:MonthlyFeeGeneration');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:MonthlyFeeGeneration');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:MonthlyFeeGeneration');
    }

    public function replicate(AuthUser $authUser, MonthlyFeeGeneration $monthlyFeeGeneration): bool
    {
        return $authUser->can('Replicate:MonthlyFeeGeneration');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:MonthlyFeeGeneration');
    }

}