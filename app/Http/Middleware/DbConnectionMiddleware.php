<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Tenant;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class DbConnectionMiddleware
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
        $currentPath =  Route::getRoutes()->match($request); //use Illuminate\Support\Facades\Route;

            
        DB::disconnect();
        Config::set('database.connections.mysql.database', env('DB_DATABASE'));
        DB::reconnect();
        // $currentPath= Route::getFacadeRoot()->current()->uri();
        // dd($currentPath->uri);

        if ($currentPath->uri == "authenticate/oauth") {

            // dd(1);

            // dd(\Request::route()->getName());
            $subdomain = array_first(explode('.', request()->getHost()));

            $database = config('tenancy.database.prefix') . $subdomain;
            // dd($database);
            DB::disconnect();
            Config::set('database.connections.mysql.database', $database);
            DB::reconnect();
            // dd('ddd');
            // config(['database.default' => 'tenant']);

            // // DB::disconnect('mysql');

            // Config::set('database.connections.tenant.database', $database);

            // DB::purge('tenant');
            // DB::reconnect('tenant');

            // config(['database.connections.mysql' => [
            //     'driver' => 'mysql',
            //     'host' => env('DB_HOST', '127.0.0.1'),
            //     'port' => env('DB_PORT', '3306'),
            //     'database' => $database,
            //     'password' => env('DB_PASSWORD', '')
            // ]]);

            // DB::connection('mysql');



            // $data = DB::connection('another_connection')->select(...);
            // config(['database.default' => 'tenant']);
            // Config::set("database.connections.tenant", [
            //     'host' => env('DB_HOST', '127.0.0.1'),
            //     'port' => env('DB_PORT', '3306'),
            //     'database' => $database,
            //     'username' => env('DB_USERNAME', 'forge'),
            //     'password' => env('DB_PASSWORD', '')
            // ]);

            // $user = new User();
            // // $user->setConnection('tenant');
            // $user1 = $user->find(1);
            // dd($user1);

            // // return $next($request);
            // Config::set("DB_DATABASE", $database);
            // Config::set("DB_USERNAME", env('DB_USERNAME'));
            // Config::set("DB_PASSWORD", env('DB_PASSWORD'));

            // DB::reconnect();
            // //    dd(DB::connection()->getPdo());
        } else {
            // dd(2);
            
            DB::disconnect();
            Config::set('database.connections.mysql.database', env('DB_DATABASE'));
            DB::reconnect();
        }

        return $next($request);
    }
}
