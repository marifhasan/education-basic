<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ClassFeeStructure;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClassFeeStructurePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ClassFeeStructure');
    }

    public function view(AuthUser $authUser, ClassFeeStructure $classFeeStructure): bool
    {
        return $authUser->can('View:ClassFeeStructure');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ClassFeeStructure');
    }

    public function update(AuthUser $authUser, ClassFeeStructure $classFeeStructure): bool
    {
        return $authUser->can('Update:ClassFeeStructure');
    }

    public function delete(AuthUser $authUser, ClassFeeStructure $classFeeStructure): bool
    {
        return $authUser->can('Delete:ClassFeeStructure');
    }

    public function restore(AuthUser $authUser, ClassFeeStructure $classFeeStructure): bool
    {
        return $authUser->can('Restore:ClassFeeStructure');
    }

    public function forceDelete(AuthUser $authUser, ClassFeeStructure $classFeeStructure): bool
    {
        return $authUser->can('ForceDelete:ClassFeeStructure');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ClassFeeStructure');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ClassFeeStructure');
    }

    public function replicate(AuthUser $authUser, ClassFeeStructure $classFeeStructure): bool
    {
        return $authUser->can('Replicate:ClassFeeStructure');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ClassFeeStructure');
    }

}