<?php

namespace App\Jobs;

use App\Mail\WaybillMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class WaybillJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user, $data;

    public function __construct($user, $data)
    {
        $this->user = $user;
        $this->data = $data;
    }
    /*
    public function handle()
    {
        // Generate the PDF content
        $pdfContent = $this->generatePDF();

        // Generate a unique file name based on the current timestamp
        $timestamp = now()->format('Y-m-d_H-i-s');
        $pdfFileName = $timestamp . '_document.pdf';

        // Specify the custom temporary directory
        $tempDir = public_path('app/temp');

        // Ensure the custom temporary directory exists
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true); // Recursively create the directory
        }

        // Specify the full file path within the custom temporary directory
        $pdfFilePath = $tempDir . '/' . $pdfFileName;

        // Save the PDF content to the specified file path
        file_put_contents($pdfFilePath, $pdfContent);

        // Generate the URL for the stored PDF
        $pdfUrl = env('APP_URL') . '/app/temp/' . $pdfFileName;

        // Send an email with a link to the PDF
        $emails = [$this->user->email, 'support@boxleocourier.com'];
        $timezone = config('app.timezone');

        $current_time = Carbon::parse(now())->timezone($timezone)->format('D d M Y H:i');
        Mail::to($emails)->send(new WaybillMail($pdfUrl, $current_time));
    }

    public function generatePDF()
    {
        try {
            $pdf_arr = $this->data;
            $pdf = PDF::loadView('pdf.waybills.boxleo-waybills', $pdf_arr);
            return $pdf->setOptions(['logOutputFile' => null])->output();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::debug($th);

            throw $th;
            abort(422, 'Something went wrong');
        }
    }*/

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            // Generate the PDF content
            $pdfContent = $this->generatePDF();

            // Generate a unique filename based on timestamp and a random string.
            $pdfFileName = 'document_' . uniqid() . '.pdf';

            // Store the PDF file in Digital Ocean Spaces using the Storage facade.
            Storage::disk('spaces')->put('boxleo/waybills/' . $pdfFileName, $pdfContent, 'public');

            // Generate the URL for the stored PDF
            $pdfUrl = 'https://' . env('DO_SPACES_BUCKET') . '.' . env('DO_SPACES_DB_ENDPOINT') . '/boxleo/waybills/' . $pdfFileName;

            // Send an email with a link to the PDF
            $emails = [$this->user->email, 'support@boxleocourier.com'];
            $timezone = config('app.timezone');
            $current_time = Carbon::parse(now())->timezone($timezone)->format('D d M Y H:i');

            Mail::to($emails)->send(new WaybillMail($pdfUrl, $current_time));

            return;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function generatePDF()
    {
        try {
            $pdf_arr = $this->data;
            $pdf = PDF::loadView('pdf.waybills.boxleo-waybills', $pdf_arr);
            return $pdf->setOptions(['logOutputFile' => null])->output();
        } catch (\Throwable $th) {
            // Log::debug($th);
            throw $th;
        }
    }
}
