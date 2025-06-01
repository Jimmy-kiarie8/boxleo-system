<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDispatchRequest;
use App\Http\Requests\UpdateDispatchRequest;
use App\Mail\CustomMail;
use App\Models\Dispatch;
use App\Seller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class DispatchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Dispatch::paginate();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreDispatchRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $waybill_no = make_reference_id('WAYBILL', Dispatch::max('id') + 1);

        $vendor = Seller::find($request->vendor_id);
        $dispatch = new Dispatch();
        $dispatch->dispatch_date = $request->dispatch_date;
        $dispatch->vendor_name = $vendor->name;
        $dispatch->warehouse_notes = $request->warehouse_notes;
        $dispatch->quality_checked = false;
        $dispatch->quality_notes = $request->quality_notes;
        $dispatch->approval_level = 0;
        $dispatch->approval_notes = $request->approval_notes;
        $dispatch->waybill_no = $waybill_no;
        $dispatch->products = json_encode($request->products);
        $dispatch->save();

        $security_email = env('SECURITY_EMAIL');

        $subject = 'New Dispatch Created';
        $body = 'A new dispatch has been created with waybill number: ' . $waybill_no;
        Mail::to($security_email)->send(new CustomMail($body, $subject));

        return response()->json(['success' => 'Dispatch created successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Dispatch  $dispatch
     * @return \Illuminate\Http\Response
     */
    public function show(Dispatch $dispatch)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Dispatch  $dispatch
     * @return \Illuminate\Http\Response
     */
    public function edit(Dispatch $dispatch)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateDispatchRequest  $request
     * @param  \App\Models\Dispatch  $dispatch
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDispatchRequest $request, Dispatch $dispatch)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Dispatch  $dispatch
     * @return \Illuminate\Http\Response
     */
    public function destroy(Dispatch $dispatch)
    {
        //
    }

    public function dispatch_approval(Request $request)
    {
        $dispatch = Dispatch::find($request->dispatch_id);
        $step = $request->step + 1;
        $dispatch->status = $request->status;
        $dispatch->approval_level = $step;

        $security_email = env('SECURITY_EMAIL');
        $inventory_email = env('INVENTORY_EMAIL');
        $warehouse_email = env('WAREHOUSE_EMAIL');



        if ($step == 1) {
            $dispatch->approval_notes = $request->notes;
        }
        if ($step == 2) {
            if (Auth::user()->email !== $security_email) {
                abort(422, 'Unauthorized action.');
            }

            $dispatch->approval_notes2 = $request->notes;
            $subject = 'Dispatch Approval';
            $body = 'A dispatch has been approved by the security team. Waybill number: ' . $dispatch->waybill_no;
            Mail::to($inventory_email)->send(new CustomMail($body, $subject));
        }
        if ($step == 3) {
            if (Auth::user()->email !== $inventory_email) {
                abort(422, 'Unauthorized action.');
            }
            $dispatch->approval_notes3 = $request->notes;
            $dispatch->status = 'Approved';
            $subject = 'Dispatch Approval';
            $body = 'A dispatch has been approved by the inventory team. Waybill number: ' . $dispatch->waybill_no;
            Mail::to($security_email)->send(new CustomMail($body, $subject));
        }
        if ($step == 4) {
            if (Auth::user()->email !== $warehouse_email) {
                abort(422, 'Unauthorized action.');
            }
            $dispatch->approval_notes4 = $request->notes;
            $dispatch->status = 'Approved';
            $subject = 'Dispatch Approval';
            $body = "Dispatch has been approved by the warehouse team. Waybill number: " . $dispatch->waybill_no;
            Mail::to([$security_email, $warehouse_email, $inventory_email])->send(new CustomMail($body, $subject));
        }
        if ($step == 5) {
            $dispatch->approval_notes5 = $request->notes;
            $dispatch->status = 'Approved';
        }
        if ($step == 6) {
            $dispatch->approval_notes6 = $request->notes;
            $dispatch->status = 'Approved';
        }
        $dispatch->save();
    }

    public function print_dispatch($id)
    {
        $dispatch = Dispatch::find($id);
        $products = json_decode($dispatch->products);
        $pdf = Pdf::loadView('pdf/dispatch-print', compact('dispatch', 'products'));
        return $pdf->stream('dispatch.pdf');
    }
}
