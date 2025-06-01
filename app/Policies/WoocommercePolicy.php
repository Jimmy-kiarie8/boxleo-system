<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Woocommerce;
use App\Subscription\Restriction;
use Illuminate\Auth\Access\HandlesAuthorization;

class WoocommercePolicy
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
     * @param  \App\Models\Woocommerce  $woocommerce
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Woocommerce $woocommerce)
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
        
        $restrict = new Restriction;

        // dd($restrict->portals());
        if (!$restrict->woocommerce()) {
            abort(422, 'You have reached your woocommerce integrations limit.');
        }
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Woocommerce  $woocommerce
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Woocommerce $woocommerce)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Woocommerce  $woocommerce
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Woocommerce $woocommerce)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Woocommerce  $woocommerce
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Woocommerce $woocommerce)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Woocommerce  $woocommerce
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Woocommerce $woocommerce)
    {
        //
    }
}
