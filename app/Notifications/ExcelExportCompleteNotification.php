<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExcelExportCompleteNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $url;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($url)
    {
        $this->url = $url;
    }


    public function via($notifiable)
    {
        return ['mail', 'database']; // Add 'database' channel
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Excel Export Complete')
            ->line('Your Excel export is complete. You can download the file from the attached link.')
            ->action('Download Excel', $this->url); // Replace $url with the actual download URL
    }

    public function toArray($notifiable)
    {
        return [
            // Data to store in the database notification
        ];
    }

    public function __invoke()
    {
        return;
    }
}
