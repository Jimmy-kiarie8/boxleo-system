<?php

namespace App\Mail\subscription;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SubscriptionMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user, $url,$password;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $url,$password)
    {
        // Log::debug($email);
        // $this->$account = $account;
        $this->password = $password;
        // $this->email = $email;
        $this->user = $user;
        $this->url = $url;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // Log::debug($this->email);
        return $this->markdown('mails/subscription/NewSubscriber');
    }
}
