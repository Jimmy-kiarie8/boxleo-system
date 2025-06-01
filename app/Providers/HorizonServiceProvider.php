<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Laravel\Horizon\Horizon;
use Laravel\Horizon\HorizonApplicationServiceProvider;

class HorizonServiceProvider extends HorizonApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        // Horizon::routeSmsNotificationsTo('15556667777');
        // Horizon::routeMailNotificationsTo('example@example.com');
        // Horizon::routeSlackNotificationsTo('slack-webhook-url', '#channel');

        // Horizon::night();
    }

    /**
     * Get the tags that should be assigned to the job.
     *
     * @return  array
     */
    public function tags()
    {
        return [
            'tenant:' . tenant('id'),
        ];
    }

    /**
     * Register the Horizon gate.
     *
     * This gate determines who can access Horizon in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        // $user = Auth::user();
        // dd($user);
        // Gate::define('viewHorizon', function ($user) {
        // dd($user);
        // return in_array($user->email, [
        //         //
        //     ]);
        // });
/* 
        Gate::define('viewHorizon', function ($user = null) {
            $u = Auth::guard('web')->user();
            return in_array($u->email, [
                env('ADMIN_MAIL', 'support@logixsaas.com')
            ]);
        });
 */
        Gate::define('viewHorizon', function ($user) {
        return in_array($user->email, [
            env('ADMIN_MAIL', 'support@logixsaas.com')
            ]);
        });
    }
}
