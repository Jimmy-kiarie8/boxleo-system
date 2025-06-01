<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SuspendMiddleware
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
        // $url_array = explode('.', parse_url($request->url(), PHP_URL_HOST));
        // $domain = $url_array[0];
        // $tenant = Tenant::where('id', $domain)->first()->subscriber;
        // if ($tenant->expired) {
        //     return redirect('/payment');
        // }
        return $next($request);
    }
}
