<?php

namespace App\Policies;

use App\Models\Sale;
use App\Models\User;
use App\Subscription\Restriction;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Access\Response;

class SalePolicy
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
     * @param  \App\Models\Sale  $Sale
     * @return mixed
     */
    public function view(User $user, Sale $Sale)
    {
        if (!$user->hasPermissionTo('Order view')) {
            abort(422, "You don't have permission to create an order");
        }
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        if (!$user->hasPermissionTo('Order create')) {
            abort(422, "You don't have permission to create an order");
        }

        return true;

        // return $restrict->orders() ? Response::allow()
        // : Response::deny('You have reached your limit.');;
    }

    public function upload(User $user)
    {
        if (!$user->hasPermissionTo('Order upload')) {
            abort(422, "You don't have permission to upload orders");
        }
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Sale  $Sale
     * @return mixed
     */
    public function update(User $user, Sale $sale)
    {
        if (!$user->hasPermissionTo('Order edit')) {
            abort(422, "You don't have permission to edit an order");
        }
        // $restrictedStatuses = ['Delivered', 'Dispatched', 'In Transit'];
        // if (in_array($sale->delivery_status, $restrictedStatuses)) {
        //     if (!$user->hasRole('Super admin')) {
        //         abort(422, 'Order can not be edited');
        //     }
        // }

        return true;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Sale  $Sale
     * @return mixed
     */
    public function delete(User $user, Sale $Sale)
    {
        if (!$user->hasPermissionTo('Order delete')) {
            abort(422, "You don't have permission to delete an order");
        }
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Sale  $Sale
     * @return mixed
     */
    public function restore(User $user, Sale $Sale)
    {
        if (!$user->hasPermissionTo('Order restore')) {
            abort(422, "You don't have permission to restore an order");
        }
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Sale  $Sale
     * @return mixed
     */
    public function forceDelete(User $user, Sale $Sale)
    {
        if (!$user->hasPermissionTo('Order force delete')) {
            abort(422, "You don't have permission to force delete an order");
        }
        return true;
    }

    public function status_update(User $user)
    {  
        // Log::debug($user->hasPermissionTo('Order update status'));

        if (!$user->hasPermissionTo('Order update status')) {
            abort(422, "You don't have permission edit the order status");
        } 
    }
}
