<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class ChangeDatabaseConnection
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
        // dd('deodke');
        if (tenant('id') != 'www' && tenant('id') != NULL) {
            $db_name = 'lux' . tenant('id');
            DB::disconnect();
            Config::set('database.connections.mysql.database', $db_name);
            DB::reconnect();
            $db = Config::get('database.connections.mysql.database');
            return $next($request);
        } else {
            $url = $request->url();
            $parsedUrl = parse_url($url);
            $host = explode('.', $parsedUrl['host']);
            $subdomain = $host[0];
            if ($subdomain != NULL && $subdomain != 'www') {
                $db_name = 'lux' . tenant('id');
                DB::disconnect();
                Config::set('database.connections.mysql.database', $db_name);
                DB::reconnect();
                $db = Config::get('database.connections.mysql.database');
                return $next($request);
            }
        }

        return $next($request);
    }
}
