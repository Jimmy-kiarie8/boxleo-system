<?php

namespace App\Providers;

use App\Models\Sale;
use App\Policies\SalePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        // Sale::class => SalePolicy::class
        'App\Models\Sale' => 'App\Policies\SalePolicy',
        'App\Models\User' => 'App\Policies\UserPolicy',

    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Gate::resource('sales', 'SalePolicy');
        // Gate::define('sales', 'App\Policies\SalePolicy@create');
        // Gate::define('sales', 'App\Policies\SalePolicy@edit');


        // Passport::routes();

        //
    }
}
