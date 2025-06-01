<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Sale;
use Illuminate\Http\Request;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade as PDF;
use App\Logo;
use App\Mail\InvoiceMail;
use App\Seller;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class InvoiceController extends Controller
{
    public function logged_user()
    {
        $user = new User();
        return  $user->logged_user();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return Sale::where('invoiced', true)->get();
        if (Auth::check()) {

            $invoices = Invoice::with(['sale'])->paginate(200);
        } elseif (Auth::guard('seller')->check()) {
            $invoices = Seller::find(Auth::guard('seller')->id());
            $invoices = $invoices->invoices()->with(['sale'])->paginate();
        }
        return $this->transform_inv($invoices);
    }

    public function transform_inv($invoices)
    {
        $invoices->transform(function ($invoice) {
            $invoice->order_no = $invoice->sale->order_no;
            $invoice->client_name = $invoice->sale->client->name;
            return $invoice;
        });
        return $invoices;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request->all();
        $sale = Sale::find($request->id);

        $invoice = Invoice::where('sale_id', $request->id)->first();
        // $invoice = new Invoice();

        $data = [
            'sale_id' => $request->id,
            'client_id' =>  $request->client_id,
            'balance' => $sale->sub_total,
            'total' => $sale->sub_total,
            'user_id' => Auth::id(),
            'invoice_no' => IdGenerator::generate(['table' => 'invoices', 'field' => 'invoice_no', 'length' => 8, 'prefix' => 'INV_']),
            'due_date' => now()
        ];

        $invoice = Invoice::create($data);
        // dd($data);
        // $invoice->invoice($data);

        // $invoice = Invoice::updateOrCreate(
        //     [
        //         'sale_id' => $request->id
        //     ],
        //     [
        //         'client_id' =>  $sale->client_id,
        //         'balance' => $sale->sub_total,
        //         'total' => $sale->sub_total,
        //         'user_id' => Auth::id(),
        //         'invoice_no' => IdGenerator::generate(['table' => 'invoices', 'field' => 'invoice_no', 'length' => 8, 'prefix' => 'INV_']),
        //         'due_date' => now()
        //     ]
        // );

        $sale->invoiced = true;
        $sale->history_comment = 'Order invoiced';
        // $sale->history_comment = 'Order invoiced by ' . ' <b style="color: red;">' . Auth::user()->name . '</b>';
        $sale->save();

        return $invoice;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Invoice::find($id)->delete();
    }

    public function inv_status(Request $request, $id)
    {

        $invoice = Invoice::find($id);
        // return $invoice->sale_id;
        if ($request->status == 'Paid') {
            $invoice->paid = true;
            $invoice->status = 'Paid';
            $invoice->balance = 0;
        }
        $invoice->save();
        return $invoice;
    }

    public function main_inv($id)
    {
        return $request->all();

        $sale = Invoice::find($id)->sale;
        $currency_value = 'KES';
        $logo = new Logo;
        $company = $this->logged_user()->company;
        // $pdf_arr = array('sale' => $sale, 'logo' => $logo->logo(), 'currency' => $currency_value, 'company' => $company);
        // $pdf = PDF::loadView('mails.invoice', $pdf_arr);
        // $pdf_data = base64_encode($pdf->output());
        // return $pdf->stream();

        $email = 'test@test.com';
        // $email = $sale->client->email;
        Mail::to($email)->send(new InvoiceMail($sale, $currency_value, $company));
        return $sale;
    }

    public function download_pdf(Request $request, $id)
    {
        $sale = Invoice::find($id)->sale;
        $currency_value = 'KES';
        // $logo = new Logo;
        // $logo->logo();
        $company = $this->logged_user()->company;
        $pdf_arr = array('sale' => $sale, 'currency' => $currency_value, 'company' => $company);
        $pdf = PDF::loadView('mails.invoice', $pdf_arr);
        // $pdf_data = base64_encode($pdf->output());

        return $pdf->stream();
    }
}
