<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \Fruitcake\Cors\HandleCors::class, # this line
        \App\Http\Middleware\TrustProxies::class,
        \App\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        \App\Http\Middleware\HttpsProtocolMiddleware::class,
        // \App\Http\Middleware\BlockIpUserAgent::class,
        // \App\Http\Middleware\Cors::class,
        // \App\Http\Middleware\DbConnectionMiddleware::class
        // \App\Http\Middleware\RedirectToNonWwwMiddleware::class,
        // \App\Http\Middleware\SuspendMiddleware::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Laravel\Jetstream\Http\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            // \App\Http\Middleware\ChangeDatabaseConnection::class
            \App\Http\Middleware\CheckUserIsActive::class,

        ],

        'api' => [
            'throttle:60,1',
            'bindings',
            // \Stancl\Tenancy\Middleware\PreventAccessFromTenantDomains::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'rider.auth' => \App\Http\Middleware\RedirectIfNotRider::class,
        'rider.guest' => \App\Http\Middleware\RedirectIfRider::class,
        'branch.auth' => \App\Http\Middleware\RedirectIfNotBranch::class,
        'branch.guest' => \App\Http\Middleware\RedirectIfBranch::class,
        'seller.auth' => \App\Http\Middleware\RedirectIfNotSeller::class,
        'seller.guest' => \App\Http\Middleware\RedirectIfSeller::class,
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'authcheck' => \App\Http\Middleware\CheckGuardAuth::class,
        // 'tenantAccount' => \App\Http\Middleware\TenantAccountMiddleware::class,
        // 'accountExp' => \App\Http\Middleware\SuspendMiddleware::class,
        'checksinglesession' => \App\Http\Middleware\CheckSingleSession::class,

        'mpesaIp' => \App\Http\Middleware\MpesaIPMiddleware::class,


        // '2fa' => \App\Http\Middleware\TwoFactorMiddleware::class,

        // 'cors' => \App\Http\Middleware\Cors::class, // added

        // 'dbconnect' => \App\Http\Middleware\DbConnectionMiddleware::class,

        // 'dbConnect' =>  \App\Http\Middleware\ChangeDatabaseConnection::class

    ];

    /**
     * The priority-sorted list of middleware.
     *
     * This forces non-global middleware to always be in the given order.
     *
     * @var array
     */
    protected $middlewarePriority = [
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\Authenticate::class,
        \Illuminate\Routing\Middleware\ThrottleRequests::class,
        \Illuminate\Session\Middleware\AuthenticateSession::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
        \Illuminate\Auth\Middleware\Authorize::class,
    ];
}
