<?php

namespace App\Policies;

use App\Models\User;
use App\Models\InternalCorporate;
use Illuminate\Auth\Access\HandlesAuthorization;

class InternalCorporatePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_internal::corporate');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, InternalCorporate $internalCorporate): bool
    {
        return $user->can('view_internal::corporate');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_internal::corporate');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, InternalCorporate $internalCorporate): bool
    {
        return $user->can('update_internal::corporate');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, InternalCorporate $internalCorporate): bool
    {
        return $user->can('delete_internal::corporate');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_internal::corporate');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, InternalCorporate $internalCorporate): bool
    {
        return $user->can('force_delete_internal::corporate');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_internal::corporate');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, InternalCorporate $internalCorporate): bool
    {
        return $user->can('restore_internal::corporate');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_internal::corporate');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, InternalCorporate $internalCorporate): bool
    {
        return $user->can('replicate_internal::corporate');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_internal::corporate');
    }
}
