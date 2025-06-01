<?php

namespace App\Notifications\Mobile;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Notify extends Notification
{
    use Queueable;

    public $user, $message, $action;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $message, $action)
    {
        $this->user = $user;
        $this->message = $message;
        $this->action = $action;
    }

     /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }


    // public function toBroadcast($notifiable)
    // {
    //     return new BroadcastMessage([
    //         'user' => $this->user,
    //         'message' => $this->message
    //     ]);
    // }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'user' => $this->user,
            'message' => $this->message,
            'action' => $this->action,
        ];
    }
}
