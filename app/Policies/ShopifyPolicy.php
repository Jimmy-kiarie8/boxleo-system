<?php

namespace App\Policies;

use App\Models\Shopify;
use App\Models\User;
use App\Subscription\Restriction;
use Illuminate\Auth\Access\HandlesAuthorization;

class ShopifyPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Shopify  $shopify
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Shopify $shopify)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return true;
        /* if (!$user->hasPermissionTo('Vendor create')) {
            abort(422, "You don't have permission to create a vendor");
        }
        if (tenant()->subscriber->at_trial) {
            return true;
        } */
        $restrict = new Restriction;

        // dd($restrict->portals());
        if (!$restrict->shopify()) {
            abort(422, 'You have reached your shopify integrations limit.');
        }
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Shopify  $shopify
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Shopify $shopify)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Shopify  $shopify
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Shopify $shopify)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Shopify  $shopify
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Shopify $shopify)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Shopify  $shopify
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Shopify $shopify)
    {
        //
    }
}
