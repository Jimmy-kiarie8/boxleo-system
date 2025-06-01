<?php

namespace App\Listeners;

use App\Events\CreatingAccountEvent;

class DomainCreatedListener
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
        broadcast(new CreatingAccountEvent('Domain Created', 6, ''));



        // dd($event);
        // Log::debug($event);
        // Mail::to('john@johndoe.com')->send(new TestMail);
    }
}
