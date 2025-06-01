<?php

namespace App\Policies;

use App\Seller;
use App\Models\User;
use App\Subscription\Restriction;
use Illuminate\Auth\Access\HandlesAuthorization;

class SellerPolicy
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
     * @param  \App\Models\Seller  $seller
     * @return mixed
     */
    public function view(User $user, Seller $seller)
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
        if (!$user->hasPermissionTo('Vendor create')) {
            abort(422, "You don't have permission to create a vendor");
        }
        if (tenant()->subscriber->at_trial) {
            return true;
        }
        $restrict = new Restriction;

        // dd($restrict->portals());
        if (!$restrict->portals()) {
            abort(422, 'You have reached your portals limit.');
        }
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Seller  $seller
     * @return mixed
     */
    public function update(User $user, Seller $seller)
    {
        if (!$user->hasPermissionTo('Vendor edit')) {
            abort(422, "You don't have permission to edit a user");
        }
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Seller  $seller
     * @return mixed
     */
    public function delete(User $user, Seller $seller)
    {
        if (!$user->hasPermissionTo('Vendor delete')) {
            abort(422, "You don't have permission to create a user");
        }
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Seller  $seller
     * @return mixed
     */
    public function restore(User $user, Seller $seller)
    {
        if (!$user->hasPermissionTo('Vendor restore')) {
            abort(422, "You don't have permission to restore a user");
        }
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Seller  $seller
     * @return mixed
     */
    public function forceDelete(User $user, Seller $seller)
    {
        if (!$user->hasPermissionTo('Vendor force delete')) {
            abort(422, "You don't have permission to restore a user");
        }
        return true;
    }
}
