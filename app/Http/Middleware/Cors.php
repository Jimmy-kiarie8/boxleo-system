<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Cors
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
        return $next($request)
            ->header('Access-Control-Allow-Origin', "*")
            ->header('Access-Control-Allow-Methods', "GET, POST, PUT, PATCH, DELETE, OPTIONS")
            ->header('Access-Control-Allow-Headers', "Origin, X-Requested-With, Accept, Content-Type, Authorization");
        // return $next($request)
        //     ->header('Access-Control-Allow-Origin', '*');
        // return $next($request)->header('Access-Control-Allow-Origin', '*')
        // ->header('Access-Control-Allow-Methods',
        //            'GET, POST, PUT, PATCH, DELETE, OPTIONS')
        //  ->header('Access-Control-Allow-Headers',
        //           'Content-Type, Authorization, X-Requested-With, X-XSRF-TOKEN');
    }
}
