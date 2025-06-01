<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Spatie\WelcomeNotification\WelcomeController as BaseWelcomeController;
use Spatie\WelcomeNotification\WelcomeNotification;

class WelcomeController extends BaseWelcomeController
{
    public function wel()
    {
        $user = User::first();
        $expiresAt = now()->addDay();
        $user->sendWelcomeNotification($expiresAt);
        Notification::send($user, new WelcomeNotification($expiresAt));
    }
    
    public function redirectPath()
    {
        if (method_exists($this, 'redirectTo')) {
            return $this->redirectTo();
        }
        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/';
    }
}
