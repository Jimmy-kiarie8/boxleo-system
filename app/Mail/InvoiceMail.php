<?php

namespace App\Mail;

use App\Logo;
use App\Models\Sale;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Log;

// implements ShouldQueue

class InvoiceMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $sale, $currency, $company ,$pdf;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Sale $sale, $currency,$company)
    {
        $this->sale = $sale;
        // $this->pdf = $pdf;
        $this->currency = $currency;
        $this->company = $company;
    }

    public function load_data()
    {
        
        $sale = Sale::find($this->sale['id']);
        $currency_value = 'KES';
        // $logo = new Logo;
        $company = $this->company;
        $pdf_arr = array('sale' => $sale, 'currency' => $currency_value, 'company' => $company);
        $pdf = PDF::loadView('mails.invoice', $pdf_arr);
        return $this->pdf = base64_encode($pdf->output());
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mails/invoice')->attachData(base64_decode($this->load_data()), 'invoice.pdf', [
            'mime' => 'application/pdf',
        ]);
    }
}
