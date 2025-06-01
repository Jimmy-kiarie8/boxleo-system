<?php

namespace App\Listeners;

use App\Events\CreatingAccountEvent;

class SeedingCompleteListener
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
        broadcast(new CreatingAccountEvent('Seeding complete', 4, ''));



        // dd($event);
        // Log::debug($event);
        // Mail::to('john@johndoe.com')->send(new TestMail);
    }
}
