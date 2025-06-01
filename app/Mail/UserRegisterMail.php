<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserRegisterMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user, $password, $url;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $password)
    {
        $this->password = $password;
        $this->user = $user;
        $this->url = env('APP_URL') . '/login';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mails.userRegister')
                    ->to($this->user->email)
                    ->subject('Welcome to ' . env('APP_NAME'));
    }
}
