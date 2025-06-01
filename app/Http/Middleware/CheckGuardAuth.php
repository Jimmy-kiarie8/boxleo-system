<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckGuardAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard('seller')->check() || Auth::guard('web')->check()) {
            // if (Auth::check()) {
            //     if (Auth::user()->first_login == null) {
            //         return redirect('/account-setup');
            //     }
            // }
            return $next($request);
        }
        if (! $request->expectsJson()) {
            // return route('login');
            return redirect('/login');
        }
        abort(401);
    }
}
