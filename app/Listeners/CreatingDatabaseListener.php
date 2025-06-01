<?php

namespace App\Listeners;

use App\Events\CreatingAccountEvent;
use App\Mail\TestMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CreatingDatabaseListener
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
        // Log::debug($event->tenant->id);
        // dd($event->tenant->id);

        broadcast(new CreatingAccountEvent('Creating Database', 1, $event->tenant->id));

        // Log::debug($event);
        // Mail::to('john@johndoe.com')->send(new TestMail);
    }
}
