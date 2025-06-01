<?php

namespace App\Mail;

use App\Models\Sale;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WaybillMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $url, $current_time;
    // public $data,$user;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($url, $current_time)
    {
        $this->url = $url;
        $this->current_time = $current_time;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mails/waybill');
    }
    
}
