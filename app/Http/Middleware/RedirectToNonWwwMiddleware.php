<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redirect;

class RedirectToNonWwwMiddleware
{
     /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (substr($request->header('host'), 0, 4) == 'www.') {
            $request->headers->set('host', env('DOMAIN', 'logixsaas.com'));

            return Redirect::to($request->path());
        }
        
        return $next($request);
    }
}
