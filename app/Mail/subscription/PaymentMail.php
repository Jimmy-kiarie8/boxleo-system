<?php

namespace App\Mail\subscription;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $url, $subscriber;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($url, $subscriber)
    {
        $this->url = $url;
        $this->subscriber = $subscriber;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mails/subscription/payment');
    }
}
