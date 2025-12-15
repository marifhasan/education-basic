<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\FeeItem;
use Illuminate\Auth\Access\HandlesAuthorization;

class FeeItemPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:FeeItem');
    }

    public function view(AuthUser $authUser, FeeItem $feeItem): bool
    {
        return $authUser->can('View:FeeItem');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:FeeItem');
    }

    public function update(AuthUser $authUser, FeeItem $feeItem): bool
    {
        return $authUser->can('Update:FeeItem');
    }

    public function delete(AuthUser $authUser, FeeItem $feeItem): bool
    {
        return $authUser->can('Delete:FeeItem');
    }

    public function restore(AuthUser $authUser, FeeItem $feeItem): bool
    {
        return $authUser->can('Restore:FeeItem');
    }

    public function forceDelete(AuthUser $authUser, FeeItem $feeItem): bool
    {
        return $authUser->can('ForceDelete:FeeItem');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:FeeItem');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:FeeItem');
    }

    public function replicate(AuthUser $authUser, FeeItem $feeItem): bool
    {
        return $authUser->can('Replicate:FeeItem');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:FeeItem');
    }

}