<?php

namespace App\Mail;

use App\Seller;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PortalMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $seller, $url, $password, $status;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Seller $seller, $password, $url, $status)
    {
        $this->seller = $seller;
        $this->password = $password;
        $this->url = $url;
        $this->status = $status;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mails/portal');
    }
}
