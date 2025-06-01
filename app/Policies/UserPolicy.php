<?php

namespace App\Policies;

use App\Models\User;
use App\Subscription\Restriction;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $User
     * @return mixed
     */
    public function view(User $user, User $User)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        if (!$user->hasPermissionTo('User create')) {
            abort(422, "You don't have permission to create a user");
        }

        // if (tenant()->subscriber->at_trial) {
        //     return true;
        // }
        $restrict = new Restriction;
        if (!$restrict->users()) {
            abort(422, 'You have reached your user limit.');
        }
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $User
     * @return mixed
     */
    public function update(User $user, User $User)
    {
        if (!$user->hasPermissionTo('User edit')) {
            abort(422, "You don't have permission to edit a user");
        }
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $User
     * @return mixed
     */
    public function delete(User $user, User $User)
    {
        if (!$user->hasPermissionTo('User delete')) {
            abort(422, "You don't have permission to create a user");
        }
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $User
     * @return mixed
     */
    public function restore(User $user, User $User)
    {
        if (!$user->hasPermissionTo('User restore')) {
            abort(422, "You don't have permission to restore a user");
        }
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $User
     * @return mixed
     */
    public function forceDelete(User $user, User $User)
    {
        if (!$user->hasPermissionTo('User force delete')) {
            abort(422, "You don't have permission to restore a user");
        }
        return true;
    }
}
