<?php

namespace App\Http\Controllers;

use App\Exports\RemittanceExport;
use App\Jobs\WaybillJob;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use App\Logo;
use App\Mail\report\RemittanceMail;
use App\Models\Client;
use App\Models\Company;
use App\Models\Ou;
use App\Models\Report;
use App\Models\RiderSale;
use App\Models\Sale;
use App\Models\SaleZone;
use App\Models\User;
use App\Models\Zone;
use App\Rider;
use App\Seller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use DNS1D;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use App\Notifications\ExcelExportCompleteNotification;
use App\Exports\OrderExport;
use App\Exports\PickingListExport;
use App\Exports\RiderPaymentSlipExport;
use App\Models\ProductInventory;
use App\Models\Warehouse\Bin;
use App\Models\Warehouse\ProductBin;
use App\Scopes\ProductScope;
use App\Services\SaleService;

// use Milon\Barcode\DNS1D;

class DownloadController extends Controller
{
    public function logged_user()
    {
        $user = new User();
        return  $user->logged_user();
    }
    public function invoiceDownload(Request $request)
    {
        // $order = Saleorder::userId()->find($request->order);
        // dd(json_decode($request->order));
        $data = json_decode($request->order);
        // dd($data);
        // $data = $data->order;
        $logo = new Logo;
        $products = [];
        $total = 0;
        foreach ($data->order->receiveorders as $key => $value) {
            $products[] = $value->products;
            $order_qty = $value->order_qty;
        }
        // dd($products);
        // dd($total);
        $products = $products;
        $pdf_arr = array('data' => $data, 'logo' => $logo->logo(), 'products' => $products, 'client' => $data->order->clients, 'order_qty' => $order_qty);
        // dd($pdf_arr);
        $pdf = PDF::loadView('pdf.invoiceStream', $pdf_arr);
        return $pdf->stream();
    }

    public function saleOrderDown(Request $request)
    {
        // $order = Saleorder::userId()->find($request->order);
        // dd(json_decode($request->order));
        $data = json_decode($request->order);
        // dd($data->sender_id);
        $client = Client::find($data->sender_id);
        $logo = new Logo;
        $products = [];
        $total = 0;
        foreach ($data->product as $key => $value) {
            $products[] = $value;
            $total = $total + $value->price;
        }
        // dd($data->order_no);

        $order_no = 'data:image/png;base64,' . DNS1D::getBarcodePNG($data->order_no, "C39");
        $data->barcode = $order_no;
        // dd($total);
        $products = $products;
        // $pdf_arr = array('data' => $data, 'logo' => $logo->logo(), 'products' => $products, 'client' => $data->clients, 'total' =>$total);
        $pdf_arr = array('data' => $data, 'logo' => $logo->logo(), 'products' => $products, 'total' => $total, 'client' => $client);
        // dd($pdf_arr);
        $pdf = PDF::loadView('pdf.saleorder', $pdf_arr);
        return $pdf->stream();
    }

    public function lableDownload(Request $request)
    {
        // return $request->all();
        // $order = Saleorder::userId()->find($request->order);
        // dd(json_decode($request->order));
        $data = json_decode($request->inventory);
        $client = Client::find($data->client_id);
        // $date = date('Y-m-d h:i:s', strtotime($data->delivery_date));
        // $date->isoFormat('MMMM Do YYYY');
        $date = Carbon::parse($data->delivery_date)->format('D, jS F Y');
        // dd($date);
        // dd($data);
        $boxes = $data->boxes;
        $products = $data->selectedProducts;
        $products = $products;
        $pdf_arr = array('data' => $data, 'products' => $products, 'client' => $client, 'boxes' => $boxes, 'date' => $date);
        // dd($pdf_arr);
        $pdf = PDF::loadView('pdf.Lables', $pdf_arr);
        return $pdf->stream('lable.pdf');
    }

    public function packageDownload(Request $request)
    {
        $data = json_decode($request->inventory);
        $client = $data->client;
        $receiveorders = $data->order->receiveorders;
        $order = $data->order;
        // dd($receiveorders);
        // $date = Carbon::parse($data->delivery_date)->format('D, jS F Y');
        $pdf_arr = array('data' => $data, 'products' => $receiveorders, 'client' => $client, 'order' => $order);
        $pdf = PDF::loadView('pdf.Package', $pdf_arr);
        return $pdf->stream();
    }


    public function package_download1(Request $request)
    {
        DB::beginTransaction();

        try {
            $data = json_decode($request->packages);
            $start_date = Carbon::parse($data->start_date);
            $end_date = Carbon::parse($data->end_date);
            $vendor_id = $data->client;
            $orders = Sale::setEagerLoads([])->with(['products' => function ($query) {
                $query->withoutGlobalScope(ProductScope::class)->setEagerLoads([]);
            }, 'client', 'agent:id,agent_name', 'seller:id,name'])->whereBetween('delivery_date', [$start_date, $end_date])->where('delivery_status', 'Scheduled')->where('printed', false)->when(!empty($vendor_id), function ($q) use ($vendor_id) {
                return $q->whereIn('seller_id', $vendor_id);
            })->take(50);

            $update_orders = $orders;
            $orders = $orders->get();
            // dd(DB::getQueryLog());


            // Initialize array to store distinct products and their quantities
            $orderProducts = [];

            $orders->each(function ($order) use (&$orderProducts) {
                $order_n = str_replace('_', '', $order->order_no);
                $order_n = str_replace('#', '', $order_n);
                $order->barcode = 'data:image/png;base64,' . DNS1D::getBarcodePNG($order_n, "C39");

                // Iterate over products in the order
                foreach ($order->products as $product) {
                    $productId = $product->id;
                    $productName = $product->product_name;
                    $quantity = $product->pivot->quantity;

                    // If product already exists, increment its quantity
                    if (isset($orderProducts[$productId])) {
                        $orderProducts[$productId]['quantity'] += $quantity;
                    } else { // Otherwise, add the product with its quantity
                        $orderProducts[$productId] = [
                            'name' => $productName,
                            'quantity' => $quantity,
                        ];
                    }
                }
            });


            $orders->transform(function ($order) {
                $order_n = str_replace('_', '', $order->order_no);
                $order_n = str_replace('#', '', $order_n);
                $order->barcode = 'data:image/png;base64,' . DNS1D::getBarcodePNG($order_n, "C39");
                return $order;
            });

            $user = $this->logged_user();
            foreach ($orders as $key => $order) {
                $sale = Sale::find($order->id);
                $sale->printed = true;
                $sale->delivery_status = 'Awaiting Dispatch';

                $sale->printed_at = now();
                $sale->print_count = $sale->print_count + 1;
                $sale->history_comment = 'Order <b>printed</b>';
                $sale->user_id = Auth::id();
                // $sale->save();

                // $this->inventoryUpdate($sale->products, $sale->warehouse_id, $sale->id);
            }

            $ou_id = $user->ou_id;
            $ou = Ou::find($ou_id);
            // $ou = Ou::select('currency_code', 'waybill_terms')->find($ou_id);
            $currency_value = ($ou) ? $ou->currency_code : '';
            $company = $user->company;
            $waybill_terms = ($ou->waybill_terms) ? $ou->waybill_terms : $company->terms;


            $pdf_arr = array('data' => $orders, 'currency' => $currency_value, 'company' => $company, 'waybill_terms' => $waybill_terms, 'ou' => $ou, 'pack_list' => $orderProducts);

            // return view('pdf.template_logix', $pdf_arr);


            // if (Auth::user()->ou_id == 1) {
            //     $pdf = PDF::loadView('pdf.waybills.boxleo-waybills-ke', $pdf_arr)->setPaper('a6');
            //     // $pdf = PDF::loadView('pdf.waybills.boxleo-waybills-ke', $pdf_arr)->setPaper('a6', 'landscape');
            // } else {
            // }

            $pdf = PDF::loadView('pdf.waybills.boxleo-waybills', $pdf_arr);
            // $pdfContent = $pdf->output();
            WaybillJob::dispatch($user, $pdf_arr);
            DB::commit();


            // return $pdf->stream($company->name . ' - waybills.pdf');
            return $pdf->download($company->name . ' - waybills.pdf');
        } catch (\Throwable $th) {
            DB::rollBack();
            // Log::debug($th);

            throw $th;

            // return ($th);
            abort(422, 'Something went wrong');
        }
    }

    public function package_download(Request $request)
    {
        DB::beginTransaction();

        try {
            $data = json_decode($request->packages);
            $start_date = Carbon::parse($data->start_date);
            $end_date = Carbon::parse($data->end_date);
            $vendor_id = $data->client;
            $orders = Sale::setEagerLoads([])->with(['products' => function ($query) {
                $query->withoutGlobalScope(ProductScope::class)->setEagerLoads([]);
            }, 'client', 'agent:id,agent_name', 'seller:id,name'])->whereBetween('delivery_date', [$start_date, $end_date])->where('delivery_status', 'Scheduled')->where('printed', false)->when(!empty($vendor_id), function ($q) use ($vendor_id) {
                return $q->whereIn('seller_id', $vendor_id);
            })->take(50);

            $update_orders = $orders;
            $orders = $orders->get();
            // dd(DB::getQueryLog());
            // return $orders;

            // Initialize array to store products by merchant
            $orderProducts = [];
            $merchantProducts = [];

            $orders->each(function ($order) use (&$orderProducts, &$merchantProducts) {
                // $order_n = str_replace('_', '', $order->order_no);
                // $order_n = str_replace('#', '', $order_n);
                $order_n = str_replace(['_', '#'], '', $order->order_no);

                // $order->barcode = 'data:image/png;base64,' . DNS1D::getBarcodePNG($order_n, "C39");
                if (!empty($order_n)) {
                    try {
                        // For Code 39, we want to keep: 0-9, A-Z, space, and - (hyphen)
                        // We're allowing spaces and hyphens as they're important for scanning
                        $valid_chars = preg_replace('/[^A-Z0-9\- ]/', '', strtoupper($order_n));
                        
                        // Only generate barcode if we have valid characters left
                        if (!empty($valid_chars)) {
                            $order->barcode = 'data:image/png;base64,' . DNS1D::getBarcodePNG($valid_chars, "C39");
                        } else {
                            $order->barcode = null; // or a default barcode image
                        }
                    } catch (\Exception $e) {
                        // Handle or log the error
                        \Log::error('Barcode generation error for order ' . $order->order_no . ': ' . $e->getMessage());
                        $order->barcode = null; // or a default barcode image
                    }
                } else {
                    $order->barcode = null; // or a default barcode image
                }


                $sellerId = $order->seller_id;
                $sellerName = $order->seller ? $order->seller->name : 'Unknown Merchant';

                // Initialize merchant entry if it doesn't exist
                if (!isset($merchantProducts[$sellerId])) {
                    $merchantProducts[$sellerId] = [
                        'name' => $sellerName,
                        'products' => []
                    ];
                }

                // Iterate over products in the order
                foreach ($order->products as $product) {
                    $productId = $product->id;
                    $productName = $product->product_name;
                    $quantity = $product->pivot->quantity;

                    // Global product list (keeping this for backward compatibility)
                    if (isset($orderProducts[$productId])) {
                        $orderProducts[$productId]['quantity'] += $quantity;
                    } else {
                        $orderProducts[$productId] = [
                            'name' => $productName,
                            'quantity' => $quantity,
                        ];
                    }

                    // Merchant-specific product list
                    if (isset($merchantProducts[$sellerId]['products'][$productId])) {
                        $merchantProducts[$sellerId]['products'][$productId]['quantity'] += $quantity;
                    } else {
                        $merchantProducts[$sellerId]['products'][$productId] = [
                            'name' => $productName,
                            'quantity' => $quantity,
                        ];
                    }
                }
            });

            $orders->transform(function ($order) {
                $order_n = str_replace('_', '', $order->order_no);
                $order_n = str_replace('#', '', $order_n);
                // $order->barcode = 'data:image/png;base64,' . DNS1D::getBarcodePNG($order_n, "C39");

                 $order_n = str_replace(['_', '#'], '', $order->order_no);

                // $order->barcode = 'data:image/png;base64,' . DNS1D::getBarcodePNG($order_n, "C39");
                if (!empty($order_n)) {
                    try {
                        // For Code 39, we want to keep: 0-9, A-Z, space, and - (hyphen)
                        // We're allowing spaces and hyphens as they're important for scanning
                        $valid_chars = preg_replace('/[^A-Z0-9\- ]/', '', strtoupper($order_n));
                        
                        // Only generate barcode if we have valid characters left
                        if (!empty($valid_chars)) {
                            $order->barcode = 'data:image/png;base64,' . DNS1D::getBarcodePNG($valid_chars, "C39");
                        } else {
                            $order->barcode = null; // or a default barcode image
                        }
                    } catch (\Exception $e) {
                        // Handle or log the error
                        \Log::error('Barcode generation error for order ' . $order->order_no . ': ' . $e->getMessage());
                        $order->barcode = null; // or a default barcode image
                    }
                } else {
                    $order->barcode = null; // or a default barcode image
                }
                return $order;
            });

            $user = $this->logged_user();
            foreach ($orders as $key => $order) {
                $sale = Sale::find($order->id);
                $sale->printed = true;
                $sale->delivery_status = 'Awaiting Dispatch';

                $sale->printed_at = now();
                $sale->print_count = $sale->print_count + 1;
                $sale->history_comment = 'Order <b>printed</b>';
                $sale->user_id = Auth::id();
                $sale->save();

                // $this->inventoryUpdate($sale->products, $sale->warehouse_id, $sale->id);
            }

            $ou_id = $user->ou_id;
            $ou = Ou::find($ou_id);
            // $ou = Ou::select('currency_code', 'waybill_terms')->find($ou_id);
            $currency_value = ($ou) ? $ou->currency_code : '';
            $company = $user->company;
            $waybill_terms = ($ou->waybill_terms) ? $ou->waybill_terms : $company->terms;

            $pdf_arr = array(
                'data' => $orders,
                'currency' => $currency_value,
                'company' => $company,
                'waybill_terms' => $waybill_terms,
                'ou' => $ou,
                'pack_list' => $merchantProducts,
                'merchant_products' => $merchantProducts  // New array with products organized by merchant
            );

            // return $merchantProducts;

            // return view('pdf.template_logix', $pdf_arr);

            $pdf = PDF::loadView('pdf.waybills.boxleo-waybills', $pdf_arr);
            // $pdfContent = $pdf->output();
            WaybillJob::dispatch($user, $pdf_arr);
            DB::commit();

            // return $pdf->stream($company->name . ' - waybills.pdf');
            return $pdf->download($company->name . ' - waybills.pdf');
        } catch (\Throwable $th) {
            DB::rollBack();
            // Log::debug($th);

            throw $th;

            // return ($th);
            abort(422, 'Something went wrong');
        }
    }

    public function inventoryUpdate($products, $warehouse_id, $id)
    {
        $status = "Awaiting Dispatch";
        $old_status = "Scheduled";

        foreach ($products as $product) {
            $seller_id = $product->pivot->seller_id;
            $update_statuses = ['Delivered', 'Returned', 'Undispatched', 'In Transit', 'Awaiting Dispatch'];
            if (!$product->virtual && in_array($status, $update_statuses)) {
                if ($product->stock_management == 1) {


                    $productInventory = ProductInventory::where('warehouse_id', $warehouse_id)
                        ->where('seller_id', $seller_id)
                        ->where('product_id', $product->id)->first();


                    if ($productInventory) {
                        $productInventory->updateInventory($status, $product->pivot->quantity, $product->id, $id, $old_status);
                    }
                } elseif ($product->stock_management == 2) {
                    $bins = Bin::where('warehouse_id', $warehouse_id)->get('id');
                    $ids = [];
                    foreach ($bins as $bin) {
                        $ids[] = $bin->id;
                    }

                    $productBin = ProductBin::whereIn('bin_id', $ids)->where('product_id', $product->id)->first();

                    if ($productBin) {
                        $productBin->updateInventory($status, $product->pivot->quantity, $product->id, $id, $old_status);
                    }
                }
            }
        }
    }


    public function pack_download(Request $request, $id)
    {
        DB::beginTransaction();

        $status = ['New', 'Pending'];

        $order = Sale::setEagerLoads([])->with(['products' => function ($query) {
            $query->withoutGlobalScope(ProductScope::class)->setEagerLoads([]);
        }, 'client'])->where('id', $id)->firstOrFail();

        if (in_array($order->delivery_status, $status)) {
            abort(403, "Order can't be printed. Order status: " . $order->delivery_status);
        }
        try {
            $user = Auth::user();
            //    return $user->hasPermissionTo('Order print edit');

            if (!$user->hasPermissionTo('Order print edit')) {
                abort(403, 'You are not allowed to print');
            } elseif ($order->printed) {
                abort(403, 'This order has already been printed');
            }

            $order_n = str_replace('_', '', $order->order_no);
            $order_n = str_replace('#', '', $order_n);

            $order->barcode = 'data:image/png;base64,' . DNS1D::getBarcodePNG($order_n, "C39");
            $sale = Sale::find($id);
            $sale->printed = true;

            if ($order->delivery_status == 'Scheduled') {
                $sale->delivery_status = 'Awaiting Dispatch';
            }

            $sale->printed_at = now();
            $sale->print_count = $sale->print_count + 1;
            $sale->history_comment = 'Order <b>printed</b>';
            $sale->user_id = Auth::id();
            $sale->save();


            $ou_id = $this->logged_user()->ou_id;
            $ou = Ou::find($ou_id);
            $currency_value = ($ou) ? $ou->currency_code : '';
            $company = $this->logged_user()->company;
            $waybill_terms = ($ou->waybill_terms) ? $ou->waybill_terms : $company->terms;

            $waybill_terms = str_replace('{% order_no %}', $order->order_no, $waybill_terms);
            // $content = str_replace('{%' . $rows->Field . '%}', $table->{$rows->Field}, $content);


            $pdf_arr = array('item' => $order, 'currency' => $currency_value, 'company' => $company, 'currency' => $currency_value, 'waybill_terms' => $waybill_terms, 'ou' => $ou);

            if (Auth::user()->ou_id == 1) {
                $pdf = PDF::loadView('pdf.waybills.boxleo-waybill-ke', $pdf_arr);
            } else {
                $pdf = PDF::loadView('pdf.waybills.boxleo-waybill', $pdf_arr);
            }
            DB::commit();

            // return $pdf->stream($company->name . ' - waybill.pdf');
            return $pdf->download($company->name . ' - waybill.pdf');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::debug($th);
            // dd($th);
            abort(422, 'Something went wrong');
        }
    }
    public function sticker_download(Request $request)
    {
        DB::beginTransaction();

        try {
            $data = json_decode($request->packages);
            $start_date = Carbon::parse($data->start_date);
            $end_date = Carbon::parse($data->end_date);

            $orders = Sale::setEagerLoads([])->with(['products' => function ($query) {
                $query->withoutGlobalScope(ProductScope::class)->setEagerLoads([]);
            }, 'client'])->whereBetween('delivery_date', [$start_date, $end_date])->where('delivery_status', 'Scheduled')->where('sticker_printed', false)->take(15);
            if (!empty($data->client)) {
                $orders = $orders->whereIn('seller_id', $data->client);
            }

            $update_orders = $orders;
            $orders = $orders->get();
            $orders->transform(function ($order) {
                $order_n = str_replace('_', '', $order->order_no);
                $order_n = str_replace('#', '', $order_n);

                $order->barcode = 'data:image/png;base64,' . DNS1D::getBarcodePNG($order_n, "C39");

                return $order;
            });
            foreach ($orders as $key => $order) {
                $user = $this->logged_user();
                $sale = Sale::find($order->id);
                $sale->sticker_printed = true;
                $sale->sticker_at = now();
                $sale->history_comment = 'Order sticker <b>printed</b>';
                $sale->user_id = Auth::id();
                $sale->save();
            }

            $ou_id = $this->logged_user()->ou_id;
            $ou = Ou::select('currency_code', 'waybill_terms')->find($ou_id);
            $currency_value = ($ou) ? $ou->currency_code : '';
            $waybill_terms = ($ou) ? $ou->waybill_terms : '';

            $company = $this->logged_user()->company;

            // return $logo = tenant('id') . env('CENTRAL_DOMAIN') . '/' . $company->logo;

            $pdf_arr = array('data' => $orders, 'currency' => $currency_value, 'company' => $company, 'waybill_terms' => $waybill_terms);
            $options = [
                'margin-top' => 8,
                'margin-bottom' => 8,
                'margin-left' => 5,
                'margin-right' => 5,
            ];
            // $customPaper = [0, 0, 595.28, 841.89*2];
            $customPaper = [0, 0, 210, 450];


            $pdf = PDF::loadView('pdf.stickers.thermoLabel', $pdf_arr)->setPaper($customPaper);

            // return $pdf->stream($company->name . ' - sticker.pdf');
            DB::commit();
            return $pdf->download($company->name . ' - sticker.pdf');
        } catch (\Throwable $th) {
            DB::rollBack();
            dd($th);
        }
    }



    public function sticker_pdf($id)
    {
        DB::beginTransaction();

        try {
            $user = Auth::user();
            //    return $user->hasPermissionTo('Order print edit');

            $order = Sale::setEagerLoads([])->with(['products' => function ($query) {
                $query->withoutGlobalScope(ProductScope::class)->setEagerLoads([]);
            }, 'client'])->where('id', $id)->firstOrFail();
            if (!$user->hasPermissionTo('Order print edit')) {
                abort(403, 'You are not allowed to print');
            }
            // elseif ($order->sticker_printed) {
            //     abort(403, 'This order has already been printed');
            // }

            $order_n = str_replace('_', '', $order->order_no);
            $order_n = str_replace('#', '', $order_n);
            $order->barcode = 'data:image/png;base64,' . DNS1D::getBarcodePNG($order_n, "C39");

            $sale = Sale::find($id);
            $sale->sticker_printed = true;
            $sale->sticker_at = now();
            $sale->history_comment = 'Order sticker <b>printed</b>';
            $sale->user_id = Auth::id();
            $sale->save();
            $ou_id = $this->logged_user()->ou_id;
            $ou = Ou::select('currency_code', 'waybill_terms')->find($ou_id);
            $currency_value = ($ou) ? $ou->currency_code : '';
            $waybill_terms = ($ou) ? $ou->waybill_terms : '';

            $company = $this->logged_user()->company;
            $pdf_arr = array('item' => $order, 'currency' => $currency_value, 'company' => $company, 'currency' => $currency_value, 'waybill_terms' => $waybill_terms);
            $customPaper = [0, 0, 210, 450];
            $pdf = PDF::loadView('pdf.stickers.thermoLabel1', $pdf_arr)->setPaper($customPaper);

            DB::commit();
            return $pdf->download($company->name . ' - waybill.pdf');
            return $pdf->stream($company->name . ' - waybill.pdf');
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(403);
        }
    }


    public function download_payment_slip(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        return Excel::download(new RiderPaymentSlipExport($startDate, $endDate), 'rider_payment_slips.xlsx');
    }


    public function pickingList(Request $request)
    {
        // Decode the JSON data from the request
        $data = json_decode($request->packages);
        $data->pdf = true;

        // Get orders with their products
        $orders = $this->getOrdersWithProducts($data);

        // Get the company information
        $company = $this->logged_user()->company;

        // Prepare data for the PDF
        $pdf_arr = array('data' => $orders['sales'], 'company' => $company);

        // Generate the PDF
        $pdf = PDF::loadView('pdf.dispatch.picking-list', $pdf_arr)->setPaper('a4');

        // Stream the PDF to the browser
        return $pdf->stream($company->name . ' - picking list.pdf');
    }

    /**
     * Get orders with their products for the picking list
     * 
     * @param object $data
     * @return Collection
     */
    private function getOrdersWithProducts($data)
    {
        // Get filtered orders data
        return $filteredData = $this->dispatch_filter(new Request(collect($data)->toArray()));

        // Extract zone IDs from the filtered data
        $zoneIds = collect($filteredData['sales'])->pluck('zone_id')->toArray();

        // Query to get orders with their products
        $orders = Sale::with(['products', 'client'])
            ->whereHas('zones', function ($query) use ($data, $zoneIds) {
                $query->whereDate('dispatch_date', $data->dispatched_on)
                    ->whereIn('zone_to', $zoneIds);
            })
            ->orderBy('order_no')
            ->get();

        return $orders;
    }

    public function export_dispatch(Request $request)
    {
        $data = json_decode($request->packages);
        // dd($data->client);
        $start_date = Carbon::parse($data->start_date);
        $end_date = Carbon::parse($data->end_date);

        $orders = Sale::with(['products' => function ($query) {
            $query->withoutGlobalScope(ProductScope::class)->setEagerLoads([]);
        }, 'client'])->whereBetween('delivery_date', [$start_date, $end_date])->where('delivery_status', 'Scheduled')->where('printed', false)->take(100)->get();

        $currency_value = $this->logged_user()->ou['currency_code'];
        $company = $this->logged_user()->company;
        $logo = new Logo;
        $pdf_arr = array('data' => $orders, 'logo' => $logo->logo(), 'currency' => $currency_value, 'company' => $company);
        $pdf = PDF::loadView('pdf.saleorder_mult', $pdf_arr);
        // $orders = Saleorder::with(['products', 'receiveorders', 'clients'])->whereBetween('delivery_date', [$start_date, $end_date])->where('status', 'Scheduled')->where('printed', false)->take(100);
        // $update_orders->update(['printed' => true]);
        // foreach ($orders as $key => $value) {
        //     dd($value);
        // }
        // $orders = $orders->count();
        // dd($orders);
        return $pdf->stream($company->name . ' - dispatch.pdf');
    }

    public function dispatch_list(Request $request)
    {
        $data = json_decode($request->packages);
        $data->pdf = true;

        // dd($data->zone_to);
        $zone = ($data->zone_to) ? Zone::find($data->zone_to) : null;
        $data_a = $this->dispatch_filter(new Request(collect($data)->toArray()));
        $count = $data_a['count'];
        $orders = $data_a['sales'];

        $orders->transform(function ($order) {
            $product_name = '';
            foreach ($order->products as $key => $value) {
                if ($key == 0) {
                    $product_name = $value->product_name;
                } else {
                    $product_name = $product_name . ' | ' .  $value->product_name;
                }
            }

            $order->product_name = $product_name;
            return $order;
        });

        $zoneName = Zone::whereIn('id', $data->zone_to)->pluck('name');
        $rider = Rider::whereIn('id', $data->rider_id)->first();
        // $rider = Rider::find($data->rider_id);
        $rider_name = ($rider) ? $rider->name : '';
        $zone_name = '';
        foreach ($zoneName as $key => $value) {
            if ($key === 0) {
                $zone_name = $value;
            } else {
                $zone_name = $zone_name . ' | ' . $value;
            }
        }

        // return $zone_name;

        $currency_value = $this->logged_user()->ou['currency_code'];
        $company = $this->logged_user()->company;
        $pdf_arr = array('data' => $orders, 'currency' => $currency_value, 'company' => $company, 'zone' => $zone, 'zone_name' => $zone_name, 'rider_name' => $rider_name, 'count' => $count);
        $pdf = PDF::loadView('pdf.dispatch.index', $pdf_arr)->setPaper('a4', 'landscape');
        // $orders = Saleorder::with(['products', 'receiveorders', 'clients'])->whereBetween('delivery_date', [$start_date, $end_date])->where('status', 'Scheduled')->where('printed', false)->take(100);
        // $update_orders->update(['printed' => true]);
        // foreach ($orders as $key => $value) {
        //     dd($value);
        // }
        // $orders = $orders->count();
        // dd($orders);
        return $pdf->stream($company->name . ' - dispatch list.pdf');
    }

    public function dispatchList(Request $request)
    {
        // return $request->all();
        $data = json_decode($request->packages);
        $data->pdf = true;

        // dd($data->zone_to);
        $dispatched_on = $data->dispatched_on;
        $data = $this->dispatchFilter(new Request(collect($data)->toArray()));
        // return ($orders);

        // return $zone_name;

        $currency_value = $this->logged_user()->ou['currency_code'];
        $company = $this->logged_user()->company;
        $pdf_arr = array('data' => $data, 'company' => $company, 'date' => $dispatched_on, 'rider_name' => null, 'count' => 0);
        $pdf = PDF::loadView('pdf.dispatch.dispatch-total', $pdf_arr)->setPaper('a4');

        return $pdf->stream($company->name . ' - dispatch list.pdf');
    }

    public function dispatchFilter(Request $request)
    {
        // 1. Validate input parameters
        // $request->validate([
        //     'dispatched_on' => 'required|date',
        // ]);

        // 2. Filter Orders by Dispatch Date
        $dispatched_on = $request->dispatched_on;

        // 3. Build the query to group by zone and count orders
        // Using the zones relationship for grouping and counting
        $statuses = ['In Transit', 'Dispatched', 'Delivered', 'Returned'];

        // 3. Build a query to get count of orders per zone
        $zoneCounts = DB::table('sale_zones')
            ->join('sales', 'sale_zones.sale_id', '=', 'sales.id')
            ->whereDate('sale_zones.dispatch_date', $dispatched_on)
            ->whereNotNull('sale_zones.zone_to')
            ->select('sale_zones.zone_to', DB::raw('count(*) as order_count'))
            ->groupBy('sale_zones.zone_to')
            ->take(10)
            ->get();

        // 4. Get zone details
        $zones = Zone::whereIn('id', $zoneCounts->pluck('zone_to'))->get()->keyBy('id');

        // 5. Combine counts with zone details
        $zoneOrders = $zoneCounts->map(function ($item) use ($zones) {
            return [
                'zone_id' => $item->zone_to,
                'zone_name' => isset($zones[$item->zone_to]) ? $zones[$item->zone_to]->name : 'Unknown',
                'order_count' => $item->order_count
            ];
        });

        // 5. Generate and return a PDF with the transformed data
        // This step depends on your PDF generation library and specific format requirements
        // $pdf = $this->generatePdf($zoneOrders); // Placeholder for PDF generation function

        return $zoneOrders;
    }

    // Placeholder function for PDF generation
    protected function generatePdf($data)
    {
        // Implement PDF generation using your preferred library
    }



    public function dispatch_filter_new(Request $request)
    {
        // 1. Validate input parameters
        $request->validate([
            'dispatched_on' => 'required',
        ]);

        DB::enableQueryLog();

        // 2. Separate filtering criteria
        $delivery_status = $request->input('status');
        $rider_id = $request->input('rider_id');
        $dispatched_on = $request->input('dispatched_on');
        $zone_to = $request->input('zone_to');
        $vendor = $request->input('vendor');

        // 3. Build the query
        $query = Sale::setEagerLoads([])
            ->with([
                'products' => function ($q) {
                    $q->setEagerLoads([])->withoutGlobalScope(ProductScope::class);
                },
                'client',
                'seller' => function ($q) {
                    $q->setEagerLoads([]);
                },
            ])
            ->when($delivery_status, function ($q) use ($delivery_status) {
                $q->where('delivery_status', $delivery_status);
            })
            ->when($vendor, function ($q) use ($vendor) {
                $q->where('seller_id', $vendor);
            })
            ->when($dispatched_on, function ($q) use ($dispatched_on) {
                if ($dispatched_on[0] == $dispatched_on[1]) {
                    $q->whereDate('updated_at', $dispatched_on[0]);
                } else {
                    $q->whereBetween('updated_at', $dispatched_on);
                }
            });

        if (!empty($zone_to)) {
            $zone_ids = SaleZone::whereIn('zone_to', $zone_to)
                ->when($dispatched_on, function ($q) use ($dispatched_on) {
                    if ($dispatched_on[0] == $dispatched_on[1]) {
                        return $q->whereDate('updated_at', $dispatched_on[0]);
                    } else {
                        return $q->whereBetween('updated_at', $dispatched_on);
                    }
                })->pluck('sale_id')->toArray();

            $query->whereIn('id', $zone_ids);
        }

        if ($rider_id) {
            $rider_ids = RiderSale::where('rider_id', $rider_id)
                ->when($dispatched_on, function ($q) use ($dispatched_on) {
                    if ($dispatched_on[0] == $dispatched_on[1]) {
                        return $q->whereDate('dispatch_date', $dispatched_on[0]);
                    } else {
                        return $q->whereBetween('dispatch_date', $dispatched_on);
                    }
                })->pluck('sale_id')->toArray();

            $query->whereIn('id', $rider_ids);
        }

        // 4. Execute the query
        $sales = $query->get();

        // 5. Transform the sales data
        $transformedSales = new Sale;
        $transformedSales->transform_sales($sales);

        Log::warning(DB::getQueryLog());
        return $sales;
    }

    public function dispatch_filter2(Request $request)
    {
        $request->validate([
            'dispatched_on' => 'required',
        ]);

        $delivery_status = $request->status;
        $rider_id = $request->rider_id;
        $dispatched_on = $request->dispatched_on;
        $zone_to = $request->zone_to;
        $vendor = $request->vendor;
        $download = $request->pdf;

        $rider_ids = [];
        $zone_ids = [];

        if (!empty($zone_to)) {
            $zone_ids = SaleZone::whereIn('zone_to', $zone_to)
                ->when($dispatched_on, function ($q) use ($dispatched_on) {
                    if ($dispatched_on[0] == $dispatched_on[1]) {
                        return $q->whereDate('updated_at', $dispatched_on[0]);
                    } else {
                        return $q->whereBetween('updated_at', $dispatched_on);
                    }
                })
                ->get()->pluck('sale_id')->toArray();
        }

        if (!empty($rider_id)) {
            $rider_ids = RiderSale::whereIn('rider_id', $rider_id)
                ->when($dispatched_on, function ($q) use ($dispatched_on) {
                    if ($dispatched_on[0] == $dispatched_on[1]) {
                        return $q->whereDate('dispatch_date', $dispatched_on[0]);
                    } else {
                        return $q->whereBetween('dispatch_date', $dispatched_on);
                    }
                })
                ->get()->pluck('sale_id')->toArray();
        }

        // Combine filters based on both zone and rider if both are selected
        if (!empty($zone_ids) && !empty($rider_ids)) {
            $array = array_intersect($zone_ids, $rider_ids);
        } elseif (!empty($rider_ids)) {
            $array = $rider_ids;
        } elseif (!empty($zone_ids)) {
            $array = $zone_ids;
        } else {
            $array = [];
        }

        $sale = Sale::setEagerLoads([])->with([
            'products' => function ($q) {
                $q->setEagerLoads([])->withoutGlobalScope(ProductScope::class);
            },
            'client',
            'services',
            'seller' => function ($q) {
                $q->setEagerLoads([]);
            }
        ])
            ->when($delivery_status, function ($q) use ($delivery_status) {
                return $q->where('delivery_status', $delivery_status);
            })
            ->when($vendor, function ($q) use ($vendor) {
                return $q->where('seller_id', $vendor);
            })
            ->when($dispatched_on, function ($q) use ($dispatched_on, $delivery_status) {
                if ($delivery_status == 'Awaiting Return') {
                    if ($dispatched_on[0] == $dispatched_on[1]) {
                        return $q->whereDate('updated_at', $dispatched_on[0]);
                    } else {
                        return $q->whereBetween('updated_at', $dispatched_on);
                    }
                } else {
                    if ($dispatched_on[0] == $dispatched_on[1]) {
                        return $q->whereDate('dispatched_on', $dispatched_on[0]);
                    } else {
                        return $q->whereBetween('dispatched_on', $dispatched_on);
                    }
                }
            })
            ->when(!empty($array), function ($q) use ($array) {
                return $q->whereIn('id', $array);
            })
            ->get();

        // Count the statuses
        $statusCounts = [];
        foreach ($sale as $item) {
            $status = $item->delivery_status;
            if (!isset($statusCounts[$status])) {
                $statusCounts[$status] = 1;
            } else {
                $statusCounts[$status]++;
            }
        }

        // Transform the sales
        $transform_sales = new Sale;

        // $sale = $sale->get();

        $transform_sales->transform_sales($sale);


        if ($download) {
            return ['sales' => $sale, 'count' => $statusCounts];
        }

        return $sale;
    }



    public function dispatch_filter(Request $request)
    {
        $request->validate([
            'dispatched_on' => 'required',
        ]);


        $delivery_status = $request->status;
        $rider_id = $request->rider_id;
        $dispatched_on = $request->dispatched_on;
        $zone_to = $request->zone_to;
        $courier_id = $request->courier_id;
        $vendor = $request->vendor;
        $download = $request->pdf;

        $rider_ids = [];
        $zone_ids = [];


        if (isset($delivery_status) && in_array('Awaiting Return', $delivery_status)) {

            $query = Sale::setEagerLoads([])->with(['products' => function ($q) {
                $q->setEagerLoads([]);
            }, 'client', 'services', 'seller' => function ($q) {
                $q->setEagerLoads([]);
            }]);

            // Apply filters
            $query->when($delivery_status, function ($q) use ($delivery_status) {
                return $q->whereIn('delivery_status', $delivery_status);
            })->when($vendor, function ($q) use ($vendor) {
                return $q->where('seller_id', $vendor);
            })->when($dispatched_on, function ($q) use ($dispatched_on, $delivery_status) {
                if ($dispatched_on[0] == $dispatched_on[1]) {
                    return $q->whereDate('updated_at', $dispatched_on[0]);
                } else {
                    return $q->whereBetween('updated_at', $dispatched_on);
                }
            })

                ->when(!empty($zone_to), function ($q) use ($zone_to) {
                    return $q->whereIn('zone_id', $zone_to);
                })
                ->when($courier_id, function ($q) use ($courier_id) {
                    return $q->whereIn('courier_id', $courier_id);
                });



            $sale = $query->get();

            // dd($sale);
            // dd(DB::getQueryLog());

            $statusCounts = [];

            foreach ($sale as $item) {
                $status = $item->delivery_status;
                if (!isset($statusCounts[$status])) {
                    $statusCounts[$status] = 1;
                } else {
                    $statusCounts[$status]++;
                }
            }

            $transform_sales = new Sale;
            $transform_sales->transform_sales($sale);

            if ($download) {
                return ['sales' => $sale, 'count' => $statusCounts];
            }
            return $sale;
        } else {


            if (!empty($zone_to)) {
                // Log::debug(1);
                $zone_ids = SaleZone::whereIn('zone_to', $zone_to)
                    ->when($dispatched_on, function ($q) use ($dispatched_on) {
                        if ($dispatched_on[0] == $dispatched_on[1]) {
                            return $q->whereDate('updated_at', $dispatched_on[0]);
                        } else {
                            return $q->whereBetween('updated_at', $dispatched_on);
                        }
                    })->pluck('sale_id')->toArray();
            }

            if (!empty($rider_id)) {
                $rider_ids = RiderSale::whereIn('rider_id', $rider_id)
                    ->when($dispatched_on, function ($q) use ($dispatched_on) {
                        if ($dispatched_on[0] == $dispatched_on[1]) {
                            return $q->whereDate('dispatch_date', $dispatched_on[0]);
                        } else {
                            return $q->whereBetween('dispatch_date', $dispatched_on);
                        }
                    })->pluck('sale_id')->toArray();
            }

            // Fixed logic for combining filters
            if (!empty($rider_id) && !empty($zone_to)) {
                // If both filters are provided, get the intersection
                $commonElements = array_intersect($rider_ids, $zone_ids);
                $array = $commonElements;
            } elseif (!empty($rider_ids)) {
                // If only rider_id is provided
                $array = $rider_ids;
            } elseif (!empty($zone_ids)) {
                // If only zone_to is provided
                $array = $zone_ids;
            } else {
                // If neither rider_id nor zone_to is provided, don't filter by IDs
                $array = null;
            }

            // DB::enableQueryLog();

            $query = Sale::setEagerLoads([])->with(['products' => function ($q) {
                $q->setEagerLoads([]);
            }, 'client', 'services', 'seller' => function ($q) {
                $q->setEagerLoads([]);
            }]);

            // Apply filters
            $query->when($delivery_status, function ($q) use ($delivery_status) {
                return $q->whereIn('delivery_status', $delivery_status);
            })->when($vendor, function ($q) use ($vendor) {
                return $q->where('seller_id', $vendor);
            })->when($dispatched_on, function ($q) use ($dispatched_on, $delivery_status) {
                if (isset($delivery_status) && in_array('Awaiting Dispatch', $delivery_status)) {
                    if ($dispatched_on[0] == $dispatched_on[1]) {
                        return $q->whereDate('delivery_date', $dispatched_on[0]);
                    } else {
                        return $q->whereBetween('delivery_date', $dispatched_on);
                    }
                } elseif (isset($delivery_status) && in_array('Awaiting Return', $delivery_status)) {
                    if ($dispatched_on[0] == $dispatched_on[1]) {
                        return $q->whereDate('updated_at', $dispatched_on[0]);
                    } else {
                        return $q->whereBetween('updated_at', $dispatched_on);
                    }
                } else {
                    if ($dispatched_on[0] == $dispatched_on[1]) {
                        return $q->whereDate('dispatched_on', $dispatched_on[0]);
                    } else {
                        return $q->whereBetween('dispatched_on', $dispatched_on);
                    }
                }
            })
                ->when($courier_id, function ($q) use ($courier_id) {
                    return $q->whereIn('courier_id', $courier_id);
                })
                ->when($array !== null, function ($q) use ($array) {
                    return $q->whereIn('id', $array);
                });



            $sale = $query->get();

            // dd($sale);
            // dd(DB::getQueryLog());

            $statusCounts = [];

            foreach ($sale as $item) {
                $status = $item->delivery_status;
                if (!isset($statusCounts[$status])) {
                    $statusCounts[$status] = 1;
                } else {
                    $statusCounts[$status]++;
                }
            }

            $transform_sales = new Sale;
            $transform_sales->transform_sales($sale);

            if ($download) {
                return ['sales' => $sale, 'count' => $statusCounts];
            }
            return $sale;
        }
    }

    public function dispatch_filter121(Request $request)
    {
        $request->validate([
            'dispatched_on' => 'required',
        ]);


        $delivery_status = $request->status;
        $rider_id = $request->rider_id;
        $dispatched_on = $request->dispatched_on;
        $zone_to = $request->zone_to;
        $courier_id = $request->courier_id;
        $vendor = $request->vendor;
        $download = $request->pdf;

        $rider_ids = [];
        $zone_ids = [];


        if (isset($delivery_status) && in_array('Awaiting Return', $delivery_status)) {

            $query = Sale::setEagerLoads([])->with(['products' => function ($q) {
                $q->setEagerLoads([]);
            }, 'client', 'services', 'seller' => function ($q) {
                $q->setEagerLoads([]);
            }]);

            // Apply filters
            $query->when($delivery_status, function ($q) use ($delivery_status) {
                return $q->whereIn('delivery_status', $delivery_status);
            })->when($vendor, function ($q) use ($vendor) {
                return $q->where('seller_id', $vendor);
            })->when($dispatched_on, function ($q) use ($dispatched_on, $delivery_status) {
                if ($dispatched_on[0] == $dispatched_on[1]) {
                    return $q->whereDate('updated_at', $dispatched_on[0]);
                } else {
                    return $q->whereBetween('updated_at', $dispatched_on);
                }
            })

                ->when(!empty($zone_to), function ($q) use ($zone_to) {
                    return $q->whereIn('zone_id', $zone_to);
                })
                ->when($courier_id, function ($q) use ($courier_id) {
                    return $q->whereIn('courier_id', $courier_id);
                });



            $sale = $query->get();

            // dd($sale);
            // dd(DB::getQueryLog());

            $statusCounts = [];

            foreach ($sale as $item) {
                $status = $item->delivery_status;
                if (!isset($statusCounts[$status])) {
                    $statusCounts[$status] = 1;
                } else {
                    $statusCounts[$status]++;
                }
            }

            $transform_sales = new Sale;
            $transform_sales->transform_sales($sale);

            if ($download) {
                return ['sales' => $sale, 'count' => $statusCounts];
            }
            return $sale;
        } else {

            if (!empty($zone_to)) {
                $zone_ids = SaleZone::whereIn('zone_to', $zone_to)
                    ->when($dispatched_on, function ($q) use ($dispatched_on, $delivery_status) {
                        if (isset($delivery_status) && in_array('Awaiting Return', $delivery_status)) {
                            if ($dispatched_on[0] == $dispatched_on[1]) {
                                return $q->whereDate('updated_at', $dispatched_on[0]);
                            } else {
                                return $q->whereBetween('updated_at', $dispatched_on);
                            }
                        } else {
                            if ($dispatched_on[0] == $dispatched_on[1]) {
                                return $q->whereDate('dispatch_date', $dispatched_on[0]);
                            } else {
                                return $q->whereBetween('dispatch_date', $dispatched_on);
                            }
                        }
                    })->pluck('sale_id')->toArray();
            }

            if (!empty($rider_id)) {
                $rider_ids = RiderSale::whereIn('rider_id', $rider_id)
                    ->when($dispatched_on, function ($q) use ($dispatched_on) {
                        if ($dispatched_on[0] == $dispatched_on[1]) {
                            return $q->whereDate('dispatch_date', $dispatched_on[0]);
                        } else {
                            return $q->whereBetween('dispatch_date', $dispatched_on);
                        }
                    })->pluck('sale_id')->toArray();
            }

            // Fixed logic for combining filters
            if (!empty($rider_id) && !empty($zone_to)) {
                // If both filters are provided, get the intersection
                $commonElements = array_intersect($rider_ids, $zone_ids);
                $array = $commonElements;
            } elseif (!empty($rider_ids)) {
                // If only rider_id is provided
                $array = $rider_ids;
            } elseif (!empty($zone_ids)) {
                // If only zone_to is provided
                $array = $zone_ids;
            } else {
                // If neither rider_id nor zone_to is provided, don't filter by IDs
                $array = null;
            }

            // DB::enableQueryLog();

            $query = Sale::setEagerLoads([])->with(['products' => function ($q) {
                $q->setEagerLoads([]);
            }, 'client', 'services', 'seller' => function ($q) {
                $q->setEagerLoads([]);
            }]);

            // Apply filters
            $query->when($delivery_status, function ($q) use ($delivery_status) {
                return $q->whereIn('delivery_status', $delivery_status);
            })->when($vendor, function ($q) use ($vendor) {
                return $q->where('seller_id', $vendor);
            })->when($dispatched_on, function ($q) use ($dispatched_on, $delivery_status) {
                if (isset($delivery_status) && in_array('Awaiting Dispatch', $delivery_status)) {
                    if ($dispatched_on[0] == $dispatched_on[1]) {
                        return $q->whereDate('delivery_date', $dispatched_on[0]);
                    } else {
                        return $q->whereBetween('delivery_date', $dispatched_on);
                    }
                } elseif (isset($delivery_status) && in_array('Awaiting Return', $delivery_status)) {
                    if ($dispatched_on[0] == $dispatched_on[1]) {
                        return $q->whereDate('updated_at', $dispatched_on[0]);
                    } else {
                        return $q->whereBetween('updated_at', $dispatched_on);
                    }
                } else {
                    if ($dispatched_on[0] == $dispatched_on[1]) {
                        return $q->whereDate('dispatched_on', $dispatched_on[0]);
                    } else {
                        return $q->whereBetween('dispatched_on', $dispatched_on);
                    }
                }
            })
                ->when($courier_id, function ($q) use ($courier_id) {
                    return $q->whereIn('courier_id', $courier_id);
                })
                ->when($array !== null, function ($q) use ($array) {
                    return $q->whereIn('id', $array);
                });



            $sale = $query->get();

            // dd($sale);
            // dd(DB::getQueryLog());

            $statusCounts = [];

            foreach ($sale as $item) {
                $status = $item->delivery_status;
                if (!isset($statusCounts[$status])) {
                    $statusCounts[$status] = 1;
                } else {
                    $statusCounts[$status]++;
                }
            }

            $transform_sales = new Sale;
            $transform_sales->transform_sales($sale);

            if ($download) {
                return ['sales' => $sale, 'count' => $statusCounts];
            }
            return $sale;
        }
    }


    public function dispatch_filter_1(Request $request)
    {
        $request->validate([
            'dispatched_on' => 'required',
        ]);

        $deliveryStatus = $request->status;
        $riderId = $request->rider_id;
        $dispatchedOn = $request->dispatched_on;
        $zoneTo = $request->zone_to;
        $vendor = $request->vendor;
        $download = $request->pdf;

        $zoneIds = [];
        $riderIds = [];

        if (!empty($zoneTo)) {
            $zoneIds = SaleZone::whereIn('zone_to', $zoneTo)
                ->when($dispatchedOn, function ($q) use ($dispatchedOn) {
                    return $this->filterByDispatchDate($q, $dispatchedOn);
                })->pluck('sale_id')->toArray();
        }

        if ($riderId) {
            $riderIds = RiderSale::where('rider_id', $riderId)
                ->when($dispatchedOn, function ($q) use ($dispatchedOn) {
                    return $this->filterByDispatchDate($q, $dispatchedOn, 'dispatch_date');
                })->pluck('sale_id')->toArray();
        }

        $filteredSaleIds = $this->getFilteredSaleIds($riderIds, $zoneIds);

        $sales = Sale::setEagerLoads([])
            ->with(['products', 'client', 'services', 'seller'])
            ->when($deliveryStatus, fn($q) => $q->where('delivery_status', $deliveryStatus))
            ->when($vendor, fn($q) => $q->where('seller_id', $vendor))
            ->when($dispatchedOn, function ($q) use ($dispatchedOn) {
                return $this->filterByDispatchDate($q, $dispatchedOn, 'dispatched_on');
            })
            ->whereIn('id', $filteredSaleIds)
            ->get();

        $statusCounts = $sales->countBy('delivery_status');

        $transformedSales = (new Sale)->transform_sales($sales);

        if ($download) {
            return ['sales' => $transformedSales, 'count' => $statusCounts];
        }

        return $transformedSales;
    }

    private function filterByDispatchDate($query, $dispatchedOn, $column = 'updated_at')
    {
        if ($dispatchedOn[0] == $dispatchedOn[1]) {
            return $query->whereDate($column, $dispatchedOn[0]);
        }

        return $query->whereBetween($column, $dispatchedOn);
    }

    private function getFilteredSaleIds($riderIds, $zoneIds)
    {
        if (!empty($riderIds) && !empty($zoneIds)) {
            return array_intersect($riderIds, $zoneIds);
        }

        return array_merge($riderIds, $zoneIds);
    }


    public function get_dispatch(Request $request)
    {
        $bulkupdate = $request->bulkupdate;
        $data = $request->data;
        $data_arr = preg_split("/[\r\n,]+/", $data, -1, PREG_SPLIT_NO_EMPTY);

        // Fetch orders that are not yet delivered and are within the provided order numbers
        $orders = Sale::where('delivery_status', '!=', 'Delivered')
            ->whereIn('order_no', $data_arr)
            ->get();

        if ($bulkupdate) {
            return $orders;
        }
        $validOrders = [];
        $invalidOrders = [];

        foreach ($orders as $order) {
            if ($this->isOrderStatusValid($order)) {
                $validOrders[] = $order;
            } else {
                $invalidOrders[] = $order;
            }
        }

        return response()->json([
            'valid_orders' => $validOrders,
            'invalid_orders' => $invalidOrders,
        ]);
    }

    private function isOrderStatusValid($saleOrder)
    {
        $validStatuses = ['Awaiting Dispatch', 'Undispatched', 'Reschedule'];

        // Return true if the order status is in valid statuses, false otherwise
        return in_array($saleOrder->delivery_status, $validStatuses);
    }




    public function get_dispatch1(Request $request)
    {
        // return $request->data;
        $data = $request->data;
        $data_arr = preg_split("/[\r\n,]+/", $data, -1, PREG_SPLIT_NO_EMPTY);




        return Sale::where('delivery_status', '!=', 'Delivered')->whereIn('order_no', $data_arr)->get();
    }




    public function waybill_templates()
    {
        $pdf = PDF::loadView('pdf.templates.waybill1');
        return $pdf->stream();
    }
    /* 
    public function remittance_download(Request $request)
    {
        // return ($request->all());
        $data = json_decode($request->packages);

        $start_date = Carbon::parse($data->start_date);
        $end_date = Carbon::parse($data->end_date);
        // $orders = $data->sales;
        $orders = new Report();
        $orders = $orders->remit($start_date, $end_date, $data->client);

        // $orders = $orders['sales'];
        $currency_value = $this->logged_user()->ou['currency_code'];
        $company = $this->logged_user()->company;
        $pick = array('data' => $orders, 'company' => $company, 'currency' => $currency_value, 'remmit' => $data->remit);
        // return view('pdf.remittance.index', compact('pick'));

        $pdf = PDF::loadView('pdf.remittance.index', $pick);
        
        $pdf_data = base64_encode($pdf->output());

        $users = User::all();

        $mails = [];

        array_push($mails, Auth::user()->email);


        Mail::to($mails)->send(new RemittanceMail($orders, $pdf_data, $currency_value, $company, $data->remit));

        // return $pdf->stream();

        // Shipcharge::where('remmited', false)->whereBetween('created_at', [$start_date, $end_date])->update(['remmited' => true, 'remmited_on' => now()]);
        
        return $pdf->stream($company->name . ' - remmitance.pdf');
    } */

    public function remittance_download(Request $request)
    {
        DB::beginTransaction();


        try {
            $data = json_decode($request->packages);
            $vendor = Seller::setEagerLoads([])->find($data->client);
            $start_date = Carbon::parse($data->start_date);
            $end_date = Carbon::parse($data->end_date);
            // $orders = $data->sales;
            $orders = new Report();
            $orders = $orders->remit($start_date, $end_date, $data->client);

            // $orders = $orders['sales'];
            $currency_value = $this->logged_user()->ou['currency_code'];
            $company = $this->logged_user()->company;
            $pick = array('data' => $orders, 'company' => $company, 'currency' => $currency_value, 'remmit' => $data->remit, 'vendor' => $vendor, 'start_date' => $start_date,  'end_date' => $end_date);
            // return view('pdf.remittance.index', compact('pick'));

            $pdf = PDF::loadView('pdf.remittance.index', $pick);

            $pdf_data = base64_encode($pdf->output());

            $users = User::all();

            $mails = [];
            foreach ($users as $key => $user) {
                if ($user->hasRole('Super admin')) {
                    // if ($user->hasRole('Super admin') && $user->email != env('ADMIN_MAIL', 'support@logixsaas.com')) {
                    $mails[] = $user->email;
                }
            }
            array_push($mails, Auth::user()->email);

            // return $data;

            Mail::to($mails)->send(new RemittanceMail($orders, $pick, $currency_value, $company, $data->remit));

            // $this->download_file($pick);

            // Shipcharge::where('remmited', false)->whereBetween('created_at', [$start_date, $end_date])->update(['remmited' => true, 'remmited_on' => now()]);



            DB::commit();
            return Excel::download(new RemittanceExport($pick), 'Remmitance.xlsx');

            // return $pdf->download($company->name . ' - remmitance.pdf');
            // return $pdf->stream($company->name . ' - remmitance.pdf');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function download_file($data)
    {
        return Excel::download(new RemittanceExport($data), 'Remmitance.xlsx');
    }

    public function lables($id)
    {
        $order = Sale::find($id);
        $company = Company::first();
        $order->barcode = 'data:image/png;base64,' . DNS1D::getBarcodePNG($order->order_no, "C39");

        $product_name = '';

        foreach ($order->products as $key => $value) {
            if ($key > 0) {
                $product_name = $product_name . ' | ' . $value->product_name;
            } else {
                $product_name = $value->product_name;
            }
        }

        $order->product_name = $product_name;

        $data = array('order' => $order, 'company' => $company);
        $pdf = PDF::loadView('pdf.shippinglable', $data);

        // return $pdf->stream($company->name . ' - lable.pdf');
        return view('pdf.shippinglable', compact('order', 'company'));
    }

    public function report(Request $request)
    {
        $file_name = str_random(6) . env('APP_NAME') . 'courier-' . ' ' . Carbon::now()->format('Y-m-d') . '.xlsx';
        $user = Auth::user();

        // Extract necessary data from the request
        // Extract the filter data from the request
        $requestData = $request->only([
            'client',
            'delivery_status',
            'status',
            'created_at',
            'ou_id',
            'agent_id',
            'delivery_date',
            'delivered_on',
            'returned_on',
            'dispatched_on',
            'recall_date'
        ]);

        // Excel::store(new OrderExport($requestData, Ou::first()), $file_name);

        (new OrderExport($requestData, Ou::first()))->queue($file_name, 'public')->chain([
            $user->notify(new ExcelExportCompleteNotification(env('APP_URL') . '/app/public/' . $file_name))
        ]);

        return;
    }

    public function dispatch_print_list(Request $request)
    {
        $orders = json_decode($request->orders);

        // return   $orders;
        $zone_from = $request->zone_from;
        $zone_to = $request->zone_to;
        $rider_id = $request->rider_id;

        // Array to store aggregated product quantities by product ID and seller
        $productTotals = [];
        $productDetails = [];

        $sellers = Seller::all();

        // Process each order
        foreach ($orders as $order) {
            // Check if products exist in the order
            if (isset($order->products) && !empty($order->products)) {
                $seller = $sellers->where('id', $order->seller_id)->first();
                foreach ($order->products as $product) {
                    $productId = $product->id;
                    $productName = $product->product_name;
                    $sellerId = $product->pivot->seller_id ?? $order->seller_id ?? null;
                    $sellerName = isset($seller) ? $seller->name : 'Unknown Seller';
                    $quantity = $product->pivot->quantity ?? 1; // Default to 1 if quantity not specified

                    // Create a unique key combining product ID and seller ID
                    $key = $productId . '_' . $sellerId;

                    // Store product details
                    if (!isset($productDetails[$key])) {
                        $productDetails[$key] = [
                            'id' => $productId,
                            'name' => $productName,
                            'seller_id' => $sellerId,
                            'seller_name' => $sellerName,
                            'total' => 0
                        ];
                    }

                    // Update quantity
                    $productDetails[$key]['total'] += $quantity;
                }
            }
        }

        // Convert to array format for view
        $productSummary = array_values($productDetails);

        $pdf = PDF::loadView('pdf.dispatch_print_list', compact('productSummary', 'zone_from', 'zone_to', 'rider_id'));
        return $pdf->stream('product_summary.pdf');
    }
}
