<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ClassModel;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClassModelPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ClassModel');
    }

    public function view(AuthUser $authUser, ClassModel $classModel): bool
    {
        return $authUser->can('View:ClassModel');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ClassModel');
    }

    public function update(AuthUser $authUser, ClassModel $classModel): bool
    {
        return $authUser->can('Update:ClassModel');
    }

    public function delete(AuthUser $authUser, ClassModel $classModel): bool
    {
        return $authUser->can('Delete:ClassModel');
    }

    public function restore(AuthUser $authUser, ClassModel $classModel): bool
    {
        return $authUser->can('Restore:ClassModel');
    }

    public function forceDelete(AuthUser $authUser, ClassModel $classModel): bool
    {
        return $authUser->can('ForceDelete:ClassModel');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ClassModel');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ClassModel');
    }

    public function replicate(AuthUser $authUser, ClassModel $classModel): bool
    {
        return $authUser->can('Replicate:ClassModel');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ClassModel');
    }

}