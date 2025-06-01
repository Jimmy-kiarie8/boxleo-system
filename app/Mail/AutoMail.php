<?php

namespace App\Mail;

use App\Http\Controllers\SaleController;
use App\Models\Sale;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AutoMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $email, $order, $message;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email, $order, $message)
    {
        $this->email = $email;
        $this->order = $order;
        $this->message = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $order = Sale::where('id',$this->order->id)->get();
        $transform = new SaleController;

        $order = $transform->transform_sales($order);

        // $this->message ;

        return $this->markdown('mails/auto/Automail')->to($this->email);
    }
}
