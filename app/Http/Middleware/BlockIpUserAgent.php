<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BlockIpUserAgent
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
        $blockedIps = [
            '45.33.61.147',
            // Add other IPs you want to block
        ];

        $blockedUserAgents = [
            'python-requests/2.27.1',
            // Add other user agents you want to block
        ];

        if (in_array($request->ip(), $blockedIps)) {
            return response('Access Denied!', 403);
        }

        if (in_array($request->userAgent(), $blockedUserAgents)) {
            return response('Access Denied!', 403);
        }

        return $next($request);
    }
}
