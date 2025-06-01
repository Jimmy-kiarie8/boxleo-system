<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AutomaticMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    public $content, $subject, $recepient;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($content, $subject, $recepient)
    {
        $this->content = $content;
        $this->subject = $subject;
        $this->recepient = $recepient;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    { 
        return $this->view('mails.auto.Automail')
                        ->subject($this->subject)
                        ->to($this->recepient);
    }
}
