<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function logged_user()
    {
        $user = new User();
        return  $user->logged_user();
    }
    public function notifications()
    {
        $user = $this->logged_user();
        // $notifications = $user->unreadNotifications;
        $notifications = $user->unreadNotifications()->latest()->take(10)->get();

        // $notifications = $user->notifications()->take(10)->latest()->get();
        $notifications->transform(function($notification) {
            // $notification->created_at = Carbon::parse($notification->created_at)->format('Y-m-d');
            $notification->created_on = Carbon::parse($notification->created_at)->diffForHumans();
            return $notification;
        });
        // $noty_count = count($user->unreadNotifications);
        $noty_count = count($notifications);


        $data = array('notifications' => $notifications, 'count' => $noty_count);

        // $notifications = Arr::prepend($notifications->toArray(), $noty_count, 'count');

        return $data;

    }

    public function read_noty()
    {
        $user = $this->logged_user();
        $user->unreadNotifications->markAsRead();
        $notifications = $user->notifications()->take(10)->latest()->get();
        // return $user->unreadNotifications;


        $noty_count = count($user->unreadNotifications);

        $data = array('notifications' => $notifications, 'count' => $noty_count);

        // $notifications = Arr::prepend($notifications->toArray(), $noty_count, 'count');

        return $data;
    }
}
