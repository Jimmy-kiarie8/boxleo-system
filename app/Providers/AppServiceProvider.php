<?php

namespace App\Providers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use App\Observers\OrderObserver;
use App\Observers\ProductObserver;
use App\Observers\UserObserver;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;
use Laravel\Passport\Passport;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Passport::ignoreMigrations();
        Passport::routes(null, ['middleware' => [
            // You can make this simpler by creating a tenancy route group
            InitializeTenancyByDomain::class,
            PreventAccessFromCentralDomains::class,
        ]]);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        Sale::observe(OrderObserver::class);
        Product::observe(ProductObserver::class);
        User::observe(UserObserver::class);

        Passport::loadKeysFrom(base_path(config('passport.key_path')));


        // Queue::before(function (JobProcessing $event) {
        //     Log::debug('*************************');
        //     Log::debug($event->connectionName);
        //     Log::debug($event->job->payload());
        //     Log::debug('*************************');
        //     // $event->connectionName
        //     // $event->job
        //     // $event->job->payload()
        // });

        // Queue::after(function (JobProcessed $event) {

        //     Log::debug('*************************');
        //     Log::debug($event->job->payload());
        //     Log::debug('*************************');
        //     // $event->connectionName
        //     // $event->job
        //     // $event->job->payload()
        // });
    }
}
