<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestrictIP
{
    /**
     * List of allowed IP addresses.
     *
     * @var array
     */
    protected $allowedIps = [
        '192.168.1.0/24', // Example range
        '203.0.113.5',    // Example single IP
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $clientIp = $request->ip();

        foreach ($this->allowedIps as $allowedIp) {
            if ($this->ipMatches($clientIp, $allowedIp)) {
                return $next($request);
            }
        }

        return response('Forbidden', Response::HTTP_FORBIDDEN);
    }

    /**
     * Check if the given IP matches the allowed IP or range.
     *
     * @param  string  $ip
     * @param  string  $allowedIp
     * @return bool
     */
    protected function ipMatches($ip, $allowedIp)
    {
        // Check if the allowed IP is a range
        if (strpos($allowedIp, '/') !== false) {
            return $this->ipInRange($ip, $allowedIp);
        }

        // Otherwise, check if it is a single IP
        return $ip === $allowedIp;
    }

    /**
     * Check if the IP is within the given range.
     *
     * @param  string  $ip
     * @param  string  $range
     * @return bool
     */
    protected function ipInRange($ip, $range)
    {
        list($range, $netmask) = explode('/', $range, 2);

        $ip = ip2long($ip);
        $range = ip2long($range);
        $netmask = ~((1 << (32 - $netmask)) - 1);

        return ($ip & $netmask) === ($range & $netmask);
    }
}
