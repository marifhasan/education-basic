<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Admission;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdmissionPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Admission');
    }

    public function view(AuthUser $authUser, Admission $admission): bool
    {
        return $authUser->can('View:Admission');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Admission');
    }

    public function update(AuthUser $authUser, Admission $admission): bool
    {
        return $authUser->can('Update:Admission');
    }

    public function delete(AuthUser $authUser, Admission $admission): bool
    {
        return $authUser->can('Delete:Admission');
    }

    public function restore(AuthUser $authUser, Admission $admission): bool
    {
        return $authUser->can('Restore:Admission');
    }

    public function forceDelete(AuthUser $authUser, Admission $admission): bool
    {
        return $authUser->can('ForceDelete:Admission');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Admission');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Admission');
    }

    public function replicate(AuthUser $authUser, Admission $admission): bool
    {
        return $authUser->can('Replicate:Admission');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Admission');
    }

}