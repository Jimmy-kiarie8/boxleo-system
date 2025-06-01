<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $subject, $name, $email, $phone, $content, $sender;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, $name, $email, $phone, $content, $sender)
    {
        $this->subject = $subject;
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->content = $content;
        $this->sender = $sender;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mails/contact')->from(env('ADMIN_MAIL', 'support@logixsaas.com'))->subject($this->subject);
    }
}
