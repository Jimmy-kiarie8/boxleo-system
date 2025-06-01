<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreShippingReqRequest;
use App\Http\Requests\UpdateShippingReqRequest;
use App\Mail\CustomMail;
use App\Models\ShippingDocs;
use App\Models\ShippingReq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class ShippingReqController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return Auth::guard('seller')->user();


        $ou_id = Auth::user()->ou_id;

        return ShippingReq::with(['documents', 'seller'])->whereHas('warehouse', function ($query) use ($ou_id) {
            $query->where('ou_id', $ou_id);
        })->orderBy('created_at', 'DESC')->paginate(100);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Auth::guard('seller')->check()) {
            $seller_id = Auth::guard('seller')->id();
        } else {
            $seller_id = $request->seller_id;
        }

        $data = [
            'amount' => $request->amount,
            'arrival_date' => $request->arrival_date,
            'description' => $request->description,
            'seller_id' => $seller_id,
            'warehouse_id' => $request->warehouse_id,
            'waybill_no' => $request->waybill_no,
            'weight' => $request->weight,
        ];


        $shipment = ShippingReq::create($data);

        $message = "Shipment {$request->waybill_no} needs your approval!";
        $email = 'mary.boxleo@gmail.com';
        Mail::to($email)
            ->send(new CustomMail($message));

        return $shipment;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ShippingReq  $shippingReq
     * @return \Illuminate\Http\Response
     */
    public function show(ShippingReq $shippingReq)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ShippingReq  $shippingReq
     * @return \Illuminate\Http\Response
     */
    public function edit(ShippingReq $shippingReq)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateShippingReqRequest  $request
     * @param  \App\Models\ShippingReq  $shippingReq
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateShippingReqRequest $request, ShippingReq $shippingReq)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ShippingReq  $shippingReq
     * @return \Illuminate\Http\Response
     */
    public function destroy(ShippingReq $shippingReq)
    {
        //
    }



    public function shipment_document(Request $request, $id)
    {
        // return $request->file('file');
        $file = $request->file('file');

        if ($file) {
            $file_name = $file->getClientOriginalName();
            $ext = $file->getClientOriginalExtension();
            $fileName = $file_name . '-'  . uniqid() . '.' . $ext;

            // Store the file in DigitalOcean Spaces
            Storage::disk('spaces')->put('boxleo/shipments/' . $fileName, file_get_contents($file), 'public');

            // Generate the URL for the stored file
            $pdfUrl = 'https://' . env('DO_SPACES_BUCKET') . '.' . env('DO_SPACES_DB_ENDPOINT') . '/boxleo/shipments/' . $fileName;

            $document = new ShippingDocs();
            $document->shipping_req_id = $id;
            $document->file_name = $fileName;
            $document->path = $pdfUrl; // Store the file path in the database
            $document->save();
        }
        return;
    }

    public function shipment_approve(Request $request, $id)
    {
        $aprove_mail = Auth::user()->email;
        $shipment = ShippingReq::find($id);
        $message = "Shipment {$shipment->waybill_no} needs your approval!";
        if ($shipment->stage === 1) {
            $email = 'christian.moto@boxleocourier.com';
            if ($aprove_mail != 'mary.boxleo@gmail.com') {
                abort(422, "The shipment is supposed to be approved by Mary Auguste");
            }
        } elseif ($shipment->stage === 2) {
            $email = 'cheryl.lukase@boxleocourier.com';
            if ($aprove_mail != 'christian.moto@boxleocourier.com') {
                abort(422, "The shipment is supposed to be approved by Christian Moto");
            }
        } elseif ($shipment->stage === 3) {
            $email = 'moses.oluoch@boxleocourier.com';
            if ($aprove_mail != 'cheryl.lukase@boxleocourier.com') {
                abort(422, "The shipment is supposed to be approved by Cheryl Lukase");
            }
        }



        if ($shipment->stage > 3) {
            $shipment->approve_status = true;
            $message = "Shipment {$shipment->waybill} has been approved!";
            $email = ['philip.boxleo@gmail.com', 'christian.moto@boxleocourier.com'];
        }
        $shipment->stage += 1;
        $shipment->last_aproved_by = $aprove_mail;

        $shipment->save();


        Mail::to($email)
            ->send(new CustomMail($message));
    }

    public function shipment_update(Request $request, $id)
    {
        ShippingReq::find($id)->update(['payment_code' => $request->data['payment_code']]);

        $message = "Shipment payment updated!";
        $email = 'philip.boxleo@gmail.com';

        Mail::to($email)
            ->send(new CustomMail($message));
    }
}
