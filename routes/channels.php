<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.User.{id}', function () {
    // return (int) $user->id === (int) $id;
    return true;
});

use App\models\Sale;
use Illuminate\Support\Facades\Log;

Broadcast::channel('order', function () {
    // return $user->id === Sale::findOrNew($orderId)->user_id;
    return true;
    // return $user;
});


Broadcast::channel('location', function ($user) {
    // return $user->id === Sale::findOrNew($orderId)->user_id;
    return true;
});


Broadcast::channel('chat', function () {
    return true;
});



Broadcast::channel('order-upload', function ($user) {
    // return $user->id === Sale::findOrNew($orderId)->user_id;
    return true;
});

Broadcast::channel('account', function () {
    // return $user->id === Sale::findOrNew($orderId)->user_id;
    return true;
});


Broadcast::channel('calls', function ($user) {
    // return true;
    return $user;
});