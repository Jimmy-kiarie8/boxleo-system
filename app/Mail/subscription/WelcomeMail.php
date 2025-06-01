<?php

namespace App\Mail\subscription;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name, $url, $plan, $encrypt_domain;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $url, $plan, $encrypt_domain)
    {
        // $this->password = $password;
        // $this->email = $email;
        $this->name = $name;
        $this->url = $url;
        $this->plan = $plan;
        $this->encrypt_domain = $encrypt_domain;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mails.subscription.Welcome');
    }
}
