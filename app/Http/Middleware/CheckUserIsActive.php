<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserIsActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        // Check if user is authenticated
        if (Auth::check()) {
            // Check if user is active
            if (!Auth::user()->active) {
                // User is not active, prevent login
                Auth::logout();

                abort('403', 'Unauthorized access');
                return redirect()->route('login')->with('error', 'Your account is not active.');
            } 
            // elseif (tenant('id') == 'demo1') {
            //     $this->timeActive();
            // }
        }

        return $next($request);
    }
}
