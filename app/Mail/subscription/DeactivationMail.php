<?php

namespace App\Mail\subscription;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DeactivationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $subscriber, $url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subscriber, $url)
    {
        $this->subscriber = $subscriber;
        $this->url = $url;
    }

    /** 
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('ACCOUNT DEACTIVATE')->markdown('subscription/deactivate');
    }
}
