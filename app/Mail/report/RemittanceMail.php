<?php

namespace App\Mail\report;

use App\Models\Sale;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class RemittanceMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $sale, $currency, $pdf, $company, $remmit;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($sale, $pdf, $currency, $company, $remmit)
    {
        $this->sale = $sale;
        $this->remmit = $remmit;
        $this->pdf = $pdf;
        $this->currency = $currency;
        $this->company = $company;
    }

    public function load_data()
    {

        // $sale = Sale::find($this->sale['id']);
        // $currency_value = 'KES';
        // $logo = new Logo;
        // $company = $this->company;
        // $pdf_arr = array('data' => $this->sale, 'company' => $this->company, 'currency' => $this->currency, 'remmit' => $this->remmit);
        $pdf = PDF::loadView('pdf.remittance.index', $this->pdf);
        return $this->pdf = base64_encode($pdf->output());
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mails/report/remitance')->attachData(base64_decode($this->load_data()),  Carbon::now()->format('D-d-M-Y') . '-remitance.pdf', [
            'mime' => 'application/pdf',
        ]);
    }
}
