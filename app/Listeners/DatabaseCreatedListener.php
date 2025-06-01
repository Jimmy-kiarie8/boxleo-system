<?php

namespace App\Listeners;

use App\Events\CreatingAccountEvent;
use Illuminate\Support\Facades\Artisan;

class DatabaseCreatedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        broadcast(new CreatingAccountEvent('Database created', 2, ''));

        // Artisan::call("infyom:scaffold", ['name' => $request['name'], '--fieldsFile' => 'public/Product.json']);

        // dd($event);
        // Log::debug($event);
        // Mail::to('john@johndoe.com')->send(new TestMail);
    }
}
