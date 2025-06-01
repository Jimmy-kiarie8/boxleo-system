<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaleValidationRequest;
use App\Http\Resources\SaleResource;
use App\Models\api\OrderApi;
use App\Models\AutoGenerate;
use App\Models\Client;
use App\Models\OrderGeocoder;
use App\Models\ProductSale;
use App\Models\RiderSale;
use App\Models\Sale;
use App\Models\SaleDocs;
use App\Models\SaleZone;
use App\Models\Sku;
use App\Models\Status;
use App\Models\User;
use App\Models\Warehouse\Warehouse;
use App\Models\Zone;
use App\Rider;
use App\Scopes\OrderScope;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Support\Facades\Storage;
use App\Services\SaleService;
use App\Scopes\ProductScope;
use Carbon\Carbon;

class SaleController1 extends Controller
{
    public function logged_user()
    {
        $user = new User();
        return  $user->logged_user();
    }


    protected $saleService;

    public function __construct(SaleService $saleService)
    {
        $this->saleService = $saleService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return $request->all();
        $sales = Sale::with(['orderHistory', 'user' => function ($query) {
            $query->setEagerLoads([]);
        }, 'products' => function ($query) {
            $query->withoutGlobalScope(ProductScope::class)->setEagerLoads([]);
        }, 'client', 'zones'])
        ->paginate(10);

        // => function($query) {
        // $query->latest();
        // }
        $sale_transform = new Sale;
        return $sale_transform->trans_sales($sales, 'custom', '');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SaleValidationRequest $request)
    {
        // return $request->all();

        if (Auth::check()) {
            $request->validate([
                'vendor_id' => 'required|integer'
            ]);
            $this->authorize('create', Sale::class);
        }
        // return $request->all();
        DB::beginTransaction();

        try {


            if (Auth::check()) {
                $ou_id = Auth::check();
            } elseif (Auth::guard('seller')->check()) {
                $warehouse = Warehouse::find($request->warehouse_id);
                $ou_id = $warehouse->ou_id;
            }

            $default_zone = Zone::where('ou_id', $ou_id)->where('default_zone', true)->first('id');

            $discount = ($request->discount) ? $request->discount : 0;
            $sale = new OrderApi();
            $products = ($request->products_arr) ? $request->products_arr : $request->cart;
            $data = $request->all();
            $data['ou_id'] = $ou_id;
            $sale = $sale->store($data, $products, $request->client, $request->pos);


            $zone = new SaleZone();
            $zone->create($sale->id, $default_zone->id, $default_zone->id);

            DB::commit();
            return $sale;
        } catch (\Throwable $th) {
            DB::rollBack();

            abort(422, $th->getMessage());
        }
    }

    public function pos_sale(Request $request)
    {

        // $tenant = new Tenant;
        // $tenant->limit('sales');
        // return $request->all();
        $request->validate([
            // 'client_id' => 'required',
            // 'cart' => 'required'
        ]);
        $discount = ($request->discount) ? $request->discount : 0;
        // return $request->product['subcategories'];

        if (!$request->client_id) {

            $client = Client::updateOrCreate(
                [
                    'name' => 'Anonymus',
                    'phone' => ''
                ],
                [
                    'address' => '',
                    'email' => '',
                    // 'seller_id' => 1,
                    'user_id' => Auth::id(),
                    'ou_id' => Auth::user()->ou_id
                ]
            );
        }

        // return $request->total_price;

        $sale = new Sale;
        $auto = new AutoGenerate;
        // return $id = IdGenerator::generate(['table' => 'sales', 'field' => 'order_no', 'length' => 10, 'prefix' =>'INV-']);
        //output: INV-000001
        $order_no = $auto->auto_generate('sales', 'order_no', 'ORD-');
        // $order_no =
        $sale->order_no = $order_no;
        $sale->total_price = $request->total_price;
        $sale->sub_total = $request->sub_total;
        $sale->discount = $discount;
        $sale->user_id = Auth::id();
        // $sale->user_id = $this->logged_user()->id;
        $sale->client_id = ($request->client_id) ? $request->client_id : $client->id;
        // $sale->drawer_id = $drawers->id;
        // $sale->order_no = $request->price;
        $sale->delivery_date = now();
        $sale->delivery_status = 'Complete';
        $sale->status = 'Closed';
        $sale->customer_notes = $request->customer_notes;
        $sale->shipping_charges = ($request->shipping_charges) ? $request->shipping_charges : 0;
        // $sale->order_no = $request->order_no;

        $sale->ou_id = Auth::user()->ou_id;

        $sale->warehouse_id = 1;
        $sale->user_id = Auth::id();

        $sale->save();
        // return $sale;

        $products = $request->products_arr;
        $products = ($request->products_arr) ? $request->products_arr : $request->cart;
        foreach ($products as $product) {
            // return($product['vendor_id']);
            $sku_id = Sku::where('sku_no', $product['sku_no'])->first('id');
            // return $sku_id;
            $product_sale = new ProductSale;
            $product_sale->sale_id = $sale->id;
            $product_sale->product_id = $product['id'];
            $product_sale->seller_id = $product['vendor_id'];
            $product_sale->sku_id = $sku_id->id;
            $product_sale->sku_no = $product['sku_no'];
            $product_sale->price = $product['skus'][0]['price'];
            // return product['order'];

            $quantity = $product['ordered'];

            $product_sale->quantity = $quantity;

            $product_sale->quantity_tobe_delivered = $quantity;
            $product_sale->total_price = $product_sale->price * $product_sale->quantity;
            $product_sale->save();
        }
        // return $sale->products;
        $warehouse_update = new StatusController;
        $warehouse_update->warehouse($sale, 1, 'Scheduled', 'In progress');
        $warehouse_update->warehouse($sale, 1, 'Delivered', 'Scheduled');

        // return $request->all();
        // $drawer = Drawer::latest()->where('user_id', Auth::id())->first();
        // $products = $request->cart;

        // // $drawer = Drawer::where('user_id', Auth::id())->first();
        // $drawer->sale_total += $request->total;;
        // $drawer->expected_closing_amount += $request->total;;
        // $drawer->save();
        return $sale;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Sale  $sale
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cacheKey = 'order_show:' . $id;




        $orderDate = Carbon::parse(request('created_at'));

        // Determine the start and end of the current half-year
        if ($orderDate->month <= 6) {
            // First half of the year (January 1st to June 30th)
            $startOfHalfYear = Carbon::create($orderDate->year, 1, 1); // January 1st
            $endOfHalfYear = Carbon::create($orderDate->year, 6, 30)->endOfDay(); // June 30th, end of the day
        } else {
            // Second half of the year (July 1st to December 31st)
            $startOfHalfYear = Carbon::create($orderDate->year, 7, 1); // July 1st
            $endOfHalfYear = Carbon::create($orderDate->year, 12, 31)->endOfDay(); // December 31st, end of the day
        }

        $dates = [$startOfHalfYear, $endOfHalfYear];



        // return Cache::remember($cacheKey, now()->addMinutes(60), function () use ($id) {
        // DB::enableQueryLog();
        $saleorders = Sale::withoutGlobalScope(OrderScope::class)->with(['orderHistory' => function ($q) {
            $q->setEagerLoads([]);
        }, 'services', 'user' => function ($q) {
            $q->setEagerLoads([])->select('users.id', 'name', 'company_id', 'ou_id')->with(['company', 'ou']);
        }, 'client' => function ($q) {
            $q->select('id', 'name', 'phone', 'city',)->with(['billing', 'shipping']);
        }, 'seller' => function ($q) {
            $q->select('id', 'name');
        }, 'package', 'documents', 'calls' => function ($q) {
            $q->select('calls.id', 'calls.call_status', 'calls.lastBridgeHangupCause', 'durationInSeconds', 'sale_id', 'created_at');
        }, 'products' => function ($query) {
            $query->withoutGlobalScope(ProductScope::class)->setEagerLoads([]);
        },])->find($id);
        // Log::debug(DB::getQueryLog());

        if ($saleorders) {
            // if (count($saleorders) > 0) {
            // $saleorders->transform(function ($saleorder) {
            //     $saleorder->products->transform(function ($product) use ($saleorder) {
            //         $product->price = $product['pivot']['price'];
            //         $product->quantity = $product['pivot']['quantity'];
            //         $saleorder->vendor_id = $product['pivot']['vendor_id'];
            //         return $product;
            //     });
            //     $saleorder->invoiced = ($saleorder->invoice != null) ? true : false;
            //     return $saleorder;
            // });

            $riders = null;
            $riders_sale = RiderSale::where('sale_id', $id)->latest()->first();

            if ($riders_sale) {
                $riders = Rider::find($riders_sale->rider_id);
            }

            if ($riders) {
                $saleorders['riders'] = [$riders];
            } else {
                $saleorders['riders'] = null;
            }

            return $saleorders;
        } else {
            return [];
        }
        // });
    }

    public function scan1($search)
    {
        // DB::enableQueryLog(); // Enable query log

        $saleorders = Sale::setEagerLoads([])->with('client', 'products')->where('order_no', $search)->first();

        if (!$saleorders) {
            abort(422, "Order doesn't exist");
        }

        $status_ = ['Awaiting Dispatch', 'Undispatched', 'Reschedule'];
        if (!in_array($saleorders->delivery_status, $status_)) {
            abort(422, "Order cannot be dispatched with status " . $saleorders->delivery_status);
        }

        if (in_array($saleorders->delivery_status, ['Dispatched', 'Delivered', 'In Transit'])) {
            abort(422, "Order current status is " . $saleorders->delivery_status);
        }


        $saleorders->client_name = ($saleorders->client) ? $saleorders->client->name : 'anonymus';
        $saleorders->client_phone = ($saleorders->client) ? $saleorders->client->phone : 'anonymus';
        $saleorders->client_email = ($saleorders->client) ? $saleorders->client->email : 'anonymus';
        $saleorders->client_address = ($saleorders->client) ? $saleorders->client->address : 'anonymus';




        // $geocode = new OrderGeocoder();
        // $geocoded = $geocode->order_geocode($saleorders->client_address, $saleorders->id);
        $geocoded = null;

        // $saleorders->geocodes = $geocoded;
        if ($geocoded) {
            $saleorders->geocode = $geocoded->id;
        } else {
            $saleorders->geocode = null;
        }

        return $saleorders;
    }


    public function scan($search)
    {
        // $cacheKey = 'order_scan:' . $search;

        $saleOrder = $this->findSaleOrder($search);
        $this->validateOrderStatus($saleOrder);
        $this->enrichClientInfo($saleOrder);

        return $saleOrder;
    }

    private function findSaleOrder($search)
    {
        // Build base query with common elements
        $baseQuery = Sale::from(DB::raw('`sales` FORCE INDEX (PRIMARY)'))
            ->with([
                'client:id,name,phone,email,address',
                'products' => function ($query) {
                    $query->setEagerLoads([])
                          ->withoutGlobalScope(ProductScope::class)
                          ->select('products.id', 'product_name');
                }
            ])
            ->where('order_no', $search);

        // If specific date range is provided, use UNIX_TIMESTAMP for the search
        if (request()->has(['start_date', 'end_date'])) {
            $startDate = Carbon::parse(request('start_date'))->startOfDay();
            $endDate = Carbon::parse(request('end_date'))->endOfDay();
            
            $baseQuery->whereBetween(DB::raw('UNIX_TIMESTAMP(created_at)'), [
                $startDate->timestamp,
                $endDate->timestamp
            ]);
        } else {
            // If no date range provided, determine the current partition range
            $now = Carbon::now();
            
            // Define partition ranges
            $partitionRanges = [
                [
                    'start' => Carbon::create(2023, 7, 1)->startOfDay(),
                    'end' => Carbon::create(2024, 1, 1)->startOfDay()
                ],
                [
                    'start' => Carbon::create(2024, 1, 1)->startOfDay(),
                    'end' => Carbon::create(2024, 7, 1)->startOfDay()
                ],
                [
                    'start' => Carbon::create(2024, 7, 1)->startOfDay(),
                    'end' => Carbon::create(2025, 1, 1)->startOfDay()
                ],
                [
                    'start' => Carbon::create(2025, 1, 1)->startOfDay(),
                    'end' => Carbon::create(2025, 7, 1)->startOfDay()
                ]
            ];

            // Find the current partition range
            $currentRange = collect($partitionRanges)
                ->first(function ($range) use ($now) {
                    return $now >= $range['start'] && $now < $range['end'];
                });

            if (!$currentRange) {
                $currentRange = end($partitionRanges);
            }

            // Search in the current partition
            $baseQuery->whereBetween(DB::raw('UNIX_TIMESTAMP(created_at)'), [
                $currentRange['start']->timestamp,
                $currentRange['end']->timestamp
            ]);
        }

        // Try to find the order
        $saleOrder = $baseQuery->first();

        // If not found and no specific date range was provided, try previous partition
        if (!$saleOrder && !request()->has(['start_date', 'end_date'])) {
            // Get the previous partition range
            $currentRangeStart = $currentRange['start'];
            $previousRange = collect($partitionRanges)
                ->first(function ($range) use ($currentRangeStart) {
                    return $range['end']->equalTo($currentRangeStart);
                });

            if ($previousRange) {
                $saleOrder = Sale::from(DB::raw('`sales` FORCE INDEX (PRIMARY)'))
                    ->with([
                        'client:id,name,phone,email,address',
                        'products' => function ($query) {
                            $query->setEagerLoads([])
                                  ->withoutGlobalScope(ProductScope::class)
                                  ->select('products.id', 'product_name');
                        }
                    ])
                    ->where('order_no', $search)
                    ->whereBetween(DB::raw('UNIX_TIMESTAMP(created_at)'), [
                        $previousRange['start']->timestamp,
                        $previousRange['end']->timestamp
                    ])
                    ->first();
            }
        }

        if (!$saleOrder) {
            Log::info('Order not found in specified date range', [
                'order_no' => $search,
                'search_dates' => request()->has(['start_date', 'end_date']) ? [
                    'start' => request('start_date'),
                    'end' => request('end_date')
                ] : [
                    'current_partition' => isset($currentRange) ? [
                        'start' => $currentRange['start']->format('Y-m-d H:i:s'),
                        'end' => $currentRange['end']->format('Y-m-d H:i:s')
                    ] : null,
                    'previous_partition' => isset($previousRange) ? [
                        'start' => $previousRange['start']->format('Y-m-d H:i:s'),
                        'end' => $previousRange['end']->format('Y-m-d H:i:s')
                    ] : null
                ]
            ]);
            abort(422, "Order doesn't exist in the specified date range");
        }

        return $saleOrder;
    }

    private function validateOrderStatus($saleOrder)
    {
        $validStatuses = ['Awaiting Dispatch', 'Undispatched', 'Reschedule'];
        $invalidStatuses = ['Dispatched', 'Delivered', 'In Transit'];

        if (!in_array($saleOrder->delivery_status, $validStatuses)) {
            abort(422, "Order cannot be dispatched with status " . $saleOrder->delivery_status);
        }

        if (in_array($saleOrder->delivery_status, $invalidStatuses)) {
            abort(422, "Order current status is " . $saleOrder->delivery_status);
        }
    }

    private function enrichClientInfo($saleOrder)
    {
        $client = $saleOrder->client;
        $saleOrder->client_name = $client ? $client->name : 'anonymous';
        $saleOrder->client_phone = $client ? $client->phone : 'anonymous';
        $saleOrder->client_email = $client ? $client->email : 'anonymous';
        $saleOrder->client_address = $client ? $client->address : 'anonymous';
    }

    // private function addGeocodeInfo($saleOrder)
    // {
    //     $geocode = new OrderGeocoder();
    //     $geocoded = $geocode->order_geocode($saleOrder->client_address, $saleOrder->id);
    //     $saleOrder->geocode = $geocoded ? $geocoded->id : null;
    // }

    public function clearOrderScanCache($orderNo)
    {
        Cache::forget('order_scan:' . $orderNo);
    }

    public function undispatch($search)
    {
        // DB::enableQueryLog(); // Enable query log

        $saleorders = Sale::setEagerLoads([])->with(['client', 'products', 'riders'])->where('order_no', $search)->first();

        if (!$saleorders) {
            abort(422, "Order doesn't exist");
        }

        if (!in_array($saleorders->delivery_status, ['In Transit', 'Dispatched'])) {
            abort(422, "Order current status is " . $saleorders->delivery_status);
        }

        $rider = RiderSale::where('sale_id', $saleorders->id)->latest()->first();
        $zone = SaleZone::where('sale_id', $saleorders->id)->latest()->first();

        $saleorders->client_name = ($saleorders->client) ? $saleorders->client->name : 'anonymus';
        $saleorders->client_phone = ($saleorders->client) ? $saleorders->client->phone : 'anonymus';
        $saleorders->client_email = ($saleorders->client) ? $saleorders->client->email : 'anonymus';
        $saleorders->client_address = ($saleorders->client) ? $saleorders->client->address : 'anonymus';

        $saleorders->rider_id = ($rider) ? $rider->rider_id : null;
        $saleorders->zone_to = ($zone) ? $zone->zone_to : null;



        // $geocode = new OrderGeocoder();
        // $geocoded = $geocode->order_geocode($saleorders->client_address, $saleorders->id);
        $geocoded = null;

        // $saleorders->geocodes = $geocoded;
        if ($geocoded) {
            $saleorders->geocode = $geocoded->id;
        } else {
            $saleorders->geocode = null;
        }

        return $saleorders;
    }

    public function scan_status($search)
    {
        // DB::enableQueryLog(); // Enable query log

        $saleorders = Sale::setEagerLoads([])->with('client', 'products')->where('order_no', $search)->first();

        if (!$saleorders) {
            abort(422, "Order doesn't exist");
        }

        if (in_array($saleorders->delivery_status, ['Delivered'])) {
            abort(422, "Order current status is " . $saleorders->delivery_status);
        }


        $saleorders->client_name = ($saleorders->client) ? $saleorders->client->name : 'anonymus';
        $saleorders->client_phone = ($saleorders->client) ? $saleorders->client->phone : 'anonymus';
        $saleorders->client_email = ($saleorders->client) ? $saleorders->client->email : 'anonymus';
        $saleorders->client_address = ($saleorders->client) ? $saleorders->client->address : 'anonymus';




        // $geocode = new OrderGeocoder();
        // $geocoded = $geocode->order_geocode($saleorders->client_address, $saleorders->id);
        $geocoded = null;

        // $saleorders->geocodes = $geocoded;
        if ($geocoded) {
            $saleorders->geocode = $geocoded->id;
        } else {
            $saleorders->geocode = null;
        }

        return $saleorders;
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Sale  $sale
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $sale = Sale::find($id);
        $this->authorize('update', $sale);
        DB::beginTransaction();

        if (Auth::user()->hasRole('Super admin')) {
            $sale->order_no = $request->order_no;
        } else {
            abort('You are not authorized to edit order!', 422);
        }


        try {

            $client = $request->client;
            $sale->total_price = $request->total_price - $request->adjustment;
            $sale->sub_total = $request->total_price;
            $sale->client_id = $client['id'];
            $sale->delivery_date = $request->delivery_date;
            $sale->customer_notes = $request->customer_notes;
            $sale->delivery_status = $request->delivery_status;

            $sale->user_id = Auth::id();

            $sale->history_comment = 'Order details updated details by ' . Auth::user()->name;

            $sale->save();
            $products = $request->products;

            $exist_id =  collect($products)->pluck('id');

            ProductSale::whereNotIn('product_id', $exist_id)->where('sale_id', $id)->delete();

            // $products = ($request->products_arr) ? $request->products_arr : $request->cart;
            /* foreach ($products as $product) {
                // dd($product);
                $sku_id = Sku::where('sku_no', $product['sku_no'])->first('id');
                // return $sku_id;
                // $product_sale = ProductSale::find($product['pivot']['id']);
                $quantity = $product['quantity'];
                $product_id = (array_key_exists('pivot', $product)) ? $product['pivot']['id'] : 0;

                ProductSale::updateOrCreate(
                    [
                        'id' => $product_id
                    ],
                    [
                        'sale_id' => $sale->id,
                        'product_id' => $product['id'],
                        'seller_id' => $product['vendor_id'],
                        'sku_id' => $sku_id->id,
                        'sku_no' => $product['sku_no'],
                        'price' => (!empty($product['skus'])) ? $product['skus'][0]['price'] : 0,
                        'quantity' => $quantity,
                        'quantity_tobe_delivered' => $quantity,
                        'price' => (!empty($product['skus'])) ? $product['skus'][0]['price'] * $quantity : 0
                    ]
                );
            }*/
            DB::commit();
            return $sale;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw ($th);
            abort(422, 'Something went wrong');
        }
    }

    public function notes_update(Request $request, $id)
    {
        $history_comment = 'Order notes updated updated';
        $user_id = Auth::id();

        Sale::withoutGlobalScope(OrderScope::class)->find($id)->update(['customer_notes' => $request->customer_notes, 'user_id' => $user_id, 'history_comment' => $history_comment]);
    }

    public function order_update(Request $request, $id)
    {

        $lock = Cache::lock('orderUpdate', 10);

        try {
            $lock->block(10);
            $sale = Sale::withoutGlobalScope(OrderScope::class)->find($id);
            $this->authorize('update', $sale);
            try {
                DB::beginTransaction();

                // $restrictedStatuses = ['Delivered', 'Dispatched', 'In Transit'];
                // if (in_array($sale->delivery_status, $restrictedStatuses)) {
                //     if (!Auth::user()->hasRole('Super admin')) {
                //         abort(422, 'Order can not be edited');
                //     }
                // }

                $client = $request->client;

                $client = Client::find($client['id']);

                $original_address = $client->address;
                $original_phone = $client->phone;
                $original_name = $client->name;
                $original_city = $client->city;


                $client->phone = $request->client_phone;
                $client->address = $request->client_address;
                $client->name = $request->client_name;
                $client->city = $request->client_city;


                $column = $client->getDirty();
                $key = key((array)$column);

                $client->save();

                // return $client->getDirty();


                if ($key == 'phone') {
                    // $original_phone = $client->getOriginal('phone');
                    $sale->history_comment = 'Order phone number updated from <b>' . $original_phone . ' </b>to<b> ' . $client->phone . ' </b>';
                } elseif ($key == 'address') {
                    // $original_address = $client->getOriginal('address');
                    $sale->history_comment = 'Order address updated from <b>' . $original_address . ' </b>to<b> ' . $client->address . ' </b>';
                } elseif ($key == 'name') {
                    // $original_address = $client->getOriginal('address');
                    $sale->history_comment = 'Client name updated from <b>' . $original_name . ' </b>to<b> ' . $client->name . ' </b>';
                } elseif ($key == 'city') {
                    // $original_address = $client->getOriginal('address');
                    $sale->history_comment = 'Client city updated from <b>' . $original_city . ' </b>to<b> ' . $client->city . ' </b>';
                }

                if (Auth::check()) {
                    $sale->user_id = Auth::id();
                }
                $sale->save();
                DB::commit();
                return $client;
            } catch (\Throwable $th) {
                DB::rollBack();
                abort(422, 'Something went wrong');
            }
            // Lock acquired after waiting a maximum of 5 seconds...
        } catch (LockTimeoutException $e) {
            // Log::debug($e);
            // Unable to acquire lock...
        } finally {
            optional($lock)->release();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sale  $sale
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('delete', Sale::find($id));
        Sale::find($id)->delete();
    }

    public function confirm($id)
    {
        $sale = Sale::withoutGlobalScope(OrderScope::class)->find($id);
        $sale->confirmed = true;
        $sale->user_id = Auth::id();
        $sale->history_comment = 'Order confirmed';
        $sale->save();
    }

    public function tracking($status)
    {
        $sales =  Sale::with(['user', 'orderHistory', 'products', 'client']);


        if (Auth::guard('web')->check()) {
            $sales = $sales->with(['zones']);
        }
        if ($status == 'Scheduled') {
            $sales = $sales->where('delivery_date', '<', now());
        }
        if ($status !== 'Pedding') {
            $sales = $sales->where('delivery_status', $status);
        } else {
            $sales = $sales->whereNotIn('delivery_status', ['Cancelled', 'Returned', 'Delivered', 'Dispatched', 'Scheduled']);
        }
        $sales = $sales->paginate(200);

        $sale_transform = new Sale;
        return $sale_transform->transform_sales($sales);
    }


    public function sales_delete(Request $request)
    {
        // return  $request->all();
        // $id = [];
        foreach ($request->all() as $key => $value) {
            $this->authorize('delete', Sale::find($value['id']));
            // $id[] = $value['id'];
            Sale::find($value['id'])->delete();
        }
        // return $id;
        // Sale::whereIn('id', $id)->delete();
    }
    /*
    public function order_search(Request $request, $search)
    {
        $sales = Sale::with(['user', 'orderHistory', 'products', 'client'])
            ->where(function ($query) use ($search) {
                $query->where('order_no', 'LIKE', '%' . $search . '%');
                $query->orWhere('mpesa_code', 'LIKE', '%' . $search . '%');
                $query->orWhereHas('client', function ($q) use ($search) {
                    $q->where(function ($q) use ($search) {
                        $q->where('name', 'LIKE', '%' . $search . '%');
                        $q->orWhere('phone', 'LIKE', '%' . $search . '%');
                        $q->orWhere('address', 'LIKE', '%' . $search . '%');
                        $q->orWhere('email', 'LIKE', '%' . $search . '%');
                    });
                });
                $query->orWhereHas('products', function ($q) use ($search) {
                    $q->where(function ($q) use ($search) {
                        $q->where('product_name', 'LIKE', '%' . $search . '%');
                    });
                });
            });

        if ($request->search_top) {
            return $sales = $sales->take(100)->get();
        } else {
            $sales = $sales->paginate(200);
        }


        $sale_transform = new Sale;
        return $sale_transform->trans_sales($sales, '', '');
    } */

    public function orderSearch($search)
    {
        $data_arr = $this->saleService->prepareSearchData($search);

        $query = $this->saleService->buildSearchQuery($data_arr);

        $sales = $query->paginate(100);

        $sale_transform = new Sale;
        return $sale_transform->trans_sales($sales, '', '');
    }


    public function order_search(Request $request)
    {
        $search = $request->search;
        if ($search === 'undefined') {
            return ['sales' => ['data' => []]];
        }

        $data_arr = $this->saleService->prepareSearchData($search);
        
        // Build the base query with partitioning support
        $query = $this->saleService->buildSearchQuery($data_arr);
        
        // Apply custom date range if provided
        // if ($request->has(['start_date', 'end_date'])) {
        //     $query = $this->saleService->setDateRange($query, $request->start_date, $request->end_date);
        // }

        $sales = $query->paginate(20);

        $sale_transform = new Sale;
        return $sale_transform->trans_sales($sales, '', '');
    }



    public function order_tracking($order_no)
    {
        return Sale::setEagerLoads([])->with(['seller', 'orderHistory' => function ($query) {
            $query->setEagerLoads([]);
        }])->where('order_no', $order_no)->firstOrFail();
    }

    public function deleted_sales()
    {
        $sales = Sale::onlyTrashed()->with(['user' => function ($query) {
            $query->setEagerLoads([]);
        }, 'products', 'client', 'zones'])->paginate(300);


        $sale_transform = new Sale;
        return $sale_transform->trans_sales($sales, 'custom', '');
    }

    public function sales_restore($id)
    {
        $this->authorize('restore', Sale::find($id));
        $sale = Sale::withTrashed()->find($id);

        $exists = Sale::where('order_no', $sale->order_no)->exists();
        if ($exists) {
            abort(422, 'An order with order No. ' . $sale->order_no . ' exists');
        }
        $sale->restore();
    }

    public function delivery_date(Request $request, $id)
    {
        $sale = Sale::find($id);
        $sale->delivery_date = $request->delivery_date;

        if (Auth::check()) {
            $sale->user_id = Auth::id();
        }
        $sale->history_comment = 'Order delivery date updated from <b>' . $sale->delivery_date . ' </b>to<b> ' . $request->delivery_date . ' </b>';

        $sale->save();
        return $sale;
    }


    public function upsell($id)
    {
        if (Auth::user()->hasRole('Admin')) {
            $sale = Sale::find($id);
            $sale->upsell = !$sale->upsell;
            $sale->save();
            return $sale;
        }
    }

    public function order_edit(Request $request, $id)
    {

        $lock = Cache::lock('orderUpdate', 10);

        try {
            $lock->block(10);
            $sale = Sale::withoutGlobalScope(OrderScope::class)->find($id);
            $this->authorize('update', $sale);
            try {

                DB::beginTransaction();

                // return $request->all();
                $this->authorize('update', $sale);
                $restrictedStatuses = ['Delivered', 'Dispatched', 'In Transit'];
                if (in_array($sale->delivery_status, $restrictedStatuses)) {
                    if (!Auth::user()->hasRole('Super admin')) {
                        // abort(422, 'Order can not be edited');
                    }
                }
                // return $request->all();
                $update = $request->update;
                if ($update == 'total_price') {
                    // $price = preg_replace('/[^0-9]/', '', $request->data['total_price']);
                    $price = $request->data['total_price'];
                    $sale->history_comment = 'Order COD updated from <b>' . $sale->total_price . ' </b>to<b> ' . $price . ' </b>';
                    $sale->total_price  = $price;
                } elseif ($update == 'shipping_charges') {
                    $sale->shipping_charges  = $request->data['shipping_charges'];
                    $sale->history_comment = 'Order shipping_charges updated from <b>' . $sale->shipping_charges . ' </b>to<b> ' . $request->data['shipping_charges'] . ' </b>';
                } elseif ($update == 'weight') {
                    $sale->weight  = $request->data['weight'];
                } elseif ($update == 'invoice_value') {
                    $sale->invoice_value  = $request->data['invoice_value'];
                    $sale->history_comment = 'Order  updated from <b>' . $sale->invoice_value . ' </b>to<b> ' . $request->data['weight'] . ' </b>';
                } elseif ($update == 'delivery_date') {
                    $sale->history_comment = 'Order delivery date updated from <b>' . $sale->delivery_date . ' </b>to<b> ' . $request->data['delivery_date'] . ' </b>';
                    $sale->delivery_date  =  $request->data['delivery_date'];
                } elseif ($update == 'customer_notes') {
                    $sale->history_comment = 'Order delivery date updated from <b>' . $sale->customer_notes . ' </b>to<b> ' . $request->data['customer_notes'] . ' </b>';
                    $sale->customer_notes  =  $request->data['customer_notes'];
                } elseif ($update == 'delivered_on') {
                    $sale->history_comment = 'Order delivery date updated from <b>' . $sale->delivered_on . ' </b>to<b> ' . $request->data['delivered_on'] . ' </b>';
                    $sale->delivered_on  =  $request->data['delivered_on'];
                } elseif ($update == 'returned_on') {
                    $sale->history_comment = 'Order return date updated from <b>' . $sale->returned_on . ' </b>to<b> ' . $request->data['returned_on'] . ' </b>';
                    $sale->returned_on  =  $request->data['returned_on'];
                }
                $sale->user_id = Auth::id();

                $sale->save();
                DB::commit();
                return $sale;
            } catch (\Throwable $th) {
                DB::rollBack();
                throw ($th);
                abort(422, 'Something went wrong');
            }
            // Lock acquired after waiting a maximum of 5 seconds...
        } catch (LockTimeoutException $e) {
            // Log::debug($e);
            // Unable to acquire lock...
        } finally {
            optional($lock)->release();
        }
    }

    public function in_transit(Request $request)
    {

        if (!Auth::user()->hasPermissionTo('Order update status')) {
            abort(422, "You don't have permission edit the order status");
        }

        // $this->authorize('status_update');
        // return $request->all();  
        $delivery_status = 'In Transit';

        $orders = $request->orders;
        $status = new Status();
        foreach ($orders as $order) {
            $order['delivery_status'] = $delivery_status;
            $order_exist = Sale::where('id', $order['id'])->where('delivery_status', 'Dispatched')->exists();
            if ($order_exist) {
                $status->status_update(new Request($order), $order['id']);
            }
        }
    }

    public function sale_docs(Request $request, $id)
    {
        // return $request->file('file');
        $file = $request->file('file');

        if ($file) {
            // $fileName = time() . '.' . $file->getClientOriginalExtension();
            // $file->storeAs('uploads', $fileName); // Save the file to the "storage/app/uploads" directory
            // $path = $file->storeAs('documents', $fileName, 'public'); // Adjust the storage path as needed
            // Storage::disk('spaces')->put('boxleo/waybills/' . $pdfFileName, $pdfContent);
            $file_name = $file->getClientOriginalName();
            $ext = $file->getClientOriginalExtension();
            $fileName = $file_name . '-'  . uniqid() . '.' . $ext;

            // Store the file in DigitalOcean Spaces
            Storage::disk('spaces')->put('boxleo/documents/' . $fileName, file_get_contents($file), 'public');

            // Generate the URL for the stored file
            $pdfUrl = 'https://' . env('DO_SPACES_BUCKET') . '.' . env('DO_SPACES_DB_ENDPOINT') . '/boxleo/documents/' . $fileName;

            // You can also store it in public directory for public access:
            // $path = $file->storeAs('public/documents', $file);

            // Create a new SaleDocs record for each uploaded file
            $document = new SaleDocs;
            $document->sale_id = $id;
            $document->file_name = $fileName;
            $document->path = $pdfUrl; // Store the file path in the database
            $document->save();
        }
        return;
    }


    public function undispatch_sales(Request $request)
    {
        $data = $request->all();

        foreach ($data as $order) {
            $sale = Sale::find($order['id']);
            $sale->status = 'Undispatched';
            $sale->delivery_status = 'Undispatched';
            $sale->history_comment = 'Order Undispatched';
            $sale->customer_notes = (array_key_exists('customer_notes', $order)) ? $order['customer_notes'] : $sale->customer_notes;
            $sale->save();

        }
    }
}
