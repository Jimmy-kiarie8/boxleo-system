<?php

namespace App\Providers;

use App\Listeners\CreatingDatabaseListener;
use App\Listeners\CreatingDomainListener;
use App\Listeners\DatabaseCreatedListener;
use App\Listeners\DomainCreatedListener;
use App\Listeners\SeedingCompleteListener;
use App\Listeners\SeedingDatabaseListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Stancl\Tenancy\Events\CreatingDatabase;
use Stancl\Tenancy\Events\CreatingDomain;
use Stancl\Tenancy\Events\DatabaseCreated;
use Stancl\Tenancy\Events\DatabaseMigrated;
use Stancl\Tenancy\Events\DatabaseSeeded;
use Stancl\Tenancy\Events\DomainCreated;
use Stancl\Tenancy\Events\MigratingDatabase;
use Stancl\Tenancy\Events\SeedingDatabase;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        MigratingDatabase::class => [
            CreatingDatabaseListener::class,
        ],
        DatabaseMigrated::class => [
            DatabaseCreatedListener::class,
        ],
        CreatingDomain::class => [
            CreatingDomainListener::class,
        ],
        DomainCreated::class => [
            DomainCreatedListener::class,
        ],
        SeedingDatabase::class => [
            SeedingDatabaseListener::class,
        ],
        DatabaseSeeded::class => [
            SeedingCompleteListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
