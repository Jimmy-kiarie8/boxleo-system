<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->mapApiRoutes();

        $this->mapWebRoutes();

        $this->mapSellerRoutes();

        $this->mapBranchRoutes();

        $this->mapRiderRoutes();
        // $this->routes(function () {
        //     Route::prefix('api')
        //         ->middleware('api')
        //         ->namespace($this->namespace)
        //         ->group(base_path('routes/api.php'));

        //     Route::middleware('web')
        //         ->namespace($this->namespace)
        //         ->group(base_path('routes/web.php'));
        // });
    }



    /**
     * Define the "vendor" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapVendorRoutes()
    {
        Route::prefix('vendor')
             ->middleware(['web'])
             ->namespace($this->namespace)
             ->group(base_path('routes/vendor.php'));
    }

    /**
     * Define the "school" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapRiderRoutes()
    {
        Route::prefix('rider')
             ->middleware(['web'])
             ->namespace($this->namespace)
             ->group(base_path('routes/rider.php'));
    }
    /**
     * Define the "school" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapBranchRoutes()
    {
        Route::prefix('branch')
             ->middleware(['web'])
             ->namespace($this->namespace)
             ->group(base_path('routes/branch.php'));
    }

    /**
     * Define the "seller" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapSellerRoutes()
    {
        Route::prefix('seller')
             ->middleware(['web'])
             ->namespace($this->namespace)
             ->group(base_path('routes/seller.php'));
    }







    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
