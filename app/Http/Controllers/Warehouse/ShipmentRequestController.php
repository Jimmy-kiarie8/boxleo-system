<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\ShipmentDocument;
use App\Models\ShipmentProduct;
use App\Models\ShipmentRequest;
use App\Models\Warehouse\Bin;
use App\Models\Warehouse\ProductBin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ShipmentRequestController extends Controller
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

        return ShipmentRequest::with(['documents', 'seller', 'products' => function ($q) {
            $q->with(['product' => function ($query) {
                $query->select('id', 'product_name')->setEagerLoads([]);
            }]);
        }])->whereHas('warehouse', function ($query) use ($ou_id) {
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
            $vendor_id = Auth::guard('seller')->id();
        } else {
            $vendor_id = $request->vendor_id;
        }

        
        $data = [
            'seller_id' => $vendor_id,
            'arrival_date' => $request->arrival_date,
            'warehouse_id' => $request->warehouse_id,
            'products' => $request->products,
        ];
        $shipment = new ShipmentRequest();
        $shipment->createShipment($data);
    }


    public function receive(Request $request, $id)
    {
        // return $request->all();
        $shipment = ShipmentRequest::find($id);
        if ($shipment->status == 'Received') {
            abort(422, 'This shipment has already been received');
        }
        DB::beginTransaction();
        $status = 'Pending';
        try {
            foreach ($request->products as $key => $product) {
                $shipment_product = ShipmentProduct::where('shipment_id', $id)->where('product_id', $product['product_id'])->first();

                

                if ((int)$product['quantity'] == $shipment_product->remaining) {
                    $status = 'Received';
                } elseif ((int)$product['received_quantity'] > $shipment_product->remaining) {
                    abort(422, 'Quantity received is greater than sent quantity');
                } else {
                    $status = 'Partially Received';
                }
                $bin = Bin::setEagerLoads([])->select('id', 'warehouse_id')->where('code', $product['position'])->first();

                if (!$bin) {
                    abort(422, 'Bin not found!');
                }
                $productBin = ProductBin::where('product_id', $product['product_id'])->where('bin_id', $bin['id'])->first();

                if ($productBin) {
                    $productBin->warehouseReceive($product['product_id'], $product['quantity']);
                } else {

                    $bin_model = new ProductBin();
                    $bin_model->createBin($bin->id, $product['product_id'], $product['quantity'], $bin->warehouse_id);
                }
                
                $shipment_product->status = $status;
                $shipment_product->received_quantity += $product['received_quantity'];
                $shipment_product->save();
            }
            ShipmentRequest::find($id)->update(['status' => $status]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return ShipmentRequest::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $shipment = ShipmentRequest::find($id);

        return $request->all();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        ShipmentRequest::find($id)->delete();
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
            $pdfUrl = 'https://' . env('DO_SPACES_BUCKET') . '.' . env('DO_SPACES_DB_ENDPOINT') . '/boxleo/documents/' . $fileName;
            
            $document = new ShipmentDocument();
            $document->shipment_id = $id;
            $document->file_name = $fileName;
            $document->path = $pdfUrl; // Store the file path in the database
            $document->save();
        }
        return;
    }

    public function shipment_approve(Request $request, $id)
    {
        $shipment = ShipmentRequest::find($id);
        if($shipment->stage > 3) {
            $shipment->approve_status = true; 
        }
        $shipment->stage += 1;

        $shipment->save();
    }

    public function shipment_update(Request $request, $id)
    {
        ShipmentRequest::find($id)->update(['payment_code' => $request->payment_code]);
    }
}
