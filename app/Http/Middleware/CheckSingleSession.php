<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CheckSingleSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = User::find(Auth::id());
        $previous_session = $user->session_id;
        if ($previous_session !== Session::getId()) {
            Session::getHandler()->destroy($previous_session);
            $request->session()->regenerate();
            $user->session_id = Session::getId();
            $user->save();
        }
        return $next($request);
    }
}
