<?php

namespace App\Jobs;

use App\Mail\OrderPdfMail;
use App\Models\Company;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ReturnPrintJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $order;
    public $zones;
    public $company;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($order, $zones, $company)
    {
        $this->order = $order;
        $this->zones = $zones;
        $this->company = $company;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Configure DomPDF to use a writable directory for logs
        $options = new \Dompdf\Options();
        $options->setLogOutputFile(storage_path('logs/dompdf_log.htm'));
        $options->setIsRemoteEnabled(true);

        Log::info($this->company);

        $pdf = Pdf::loadView('pdf.return-pdf', ['order' => $this->order, 'zones' => $this->zones, 'company' => $this->company]);
        $pdf->getDomPDF()->setOptions($options);
        $pdfContent = $pdf->output();
        $filename = 'return_' . time() . '.pdf';

        // Use Laravel's storage system instead of direct file operations
        $path = storage_path('app/temp/' . $filename);
        
        // Ensure the temp directory exists
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }
        
        file_put_contents($path, $pdfContent);
        
        // Mail::to(['jimlaravel@gmail.com'])->send(new OrderPdfMail($path, $filename));
        Mail::to(['jimlaravel@gmail.com', 'Brightone.ouma@boxleocourier.com','support@boxleocourier.com', 'philip.boxleo@gmail.com', 'warehousedata.boxleo@gmail.com'])->send(new OrderPdfMail($path, $filename));
        
        // Clean up the temporary file
        if (file_exists($path)) {
            unlink($path);
        }
    }
}
