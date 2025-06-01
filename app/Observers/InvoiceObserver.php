<?php

namespace App\Observers;

use App\Models\AutoGenerate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use App\Models\InvoiceHistory;
use App\Models\Invoice;

class InvoiceObserver
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    /**
     * Handle the invoice "created" event.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return void
     */
    public function created(Invoice $invoice)
    {
        // dd($invoice);
        $reference_no = new AutoGenerate;
        $history = new InvoiceHistory();
        $history->user_id = Auth::id();
        $history->instructions = 'Invoice created by ' . Auth::user()->name;
        $history->invoice_id = $invoice->id;
        $history->reference_no = $reference_no->invReferenceNo();
        $history->save();
    }

    /**
     * Handle the invoice "updated" event.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return void
     */
    public function updated(Invoice $invoice)
    {
        if ($invoice->getDirty()) {
            // dd($invoice->getDirty());
            $reference_no = new AutoGenerate;
            $updated_fields = $invoice->getDirty();
            $history = new InvoiceHistory();
            $history->user_id = Auth::id();
            $history->instructions = $invoice->instructions;
            $history->invoice_id = $invoice->id;
            $history->reference_no = $reference_no->invReferenceNo();
            $history->save();
        }
    }

    /**
     * Handle the invoice "deleted" event.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return void
     */
    public function deleted(Invoice $invoice)
    {
        $reference_no = new AutoGenerate;
        $history = new InvoiceHistory();
        $history->user_id = Auth::id();
        $history->instructions = 'Invoice deleted by ' . Auth::user()->name;
        $history->invoice_id = $invoice->id;
        $history->reference_no = $reference_no->invReferenceNo();
        $history->save();
    }

    /**
     * Handle the invoice "restored" event.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return void
     */
    public function restored(Invoice $invoice)
    {
        $reference_no = new AutoGenerate;
        $history = new InvoiceHistory();
        $history->user_id = Auth::id();
        $history->instructions = 'Invoice restored by ' . Auth::user()->name;
        $history->invoice_id = $invoice->id;
        $history->reference_no = $reference_no->invReferenceNo();
        $history->save();
    }

    /**
     * Handle the invoice "force deleted" event.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return void
     */
    public function forceDeleted(Invoice $invoice)
    {
        $reference_no = new AutoGenerate;
        $history = new InvoiceHistory();
        $history->user_id = Auth::id();
        $history->instructions = 'Invoice force deleted by ' . Auth::user()->name;
        $history->invoice_id = $invoice->id;
        $history->reference_no = $reference_no->invReferenceNo();
        $history->save();
    }
}
