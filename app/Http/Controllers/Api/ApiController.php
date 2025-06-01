<?php

namespace App\Http\Controllers\Api;

use App\Events\MobileEvent;
use App\Events\SaleEvent;
use App\Http\Controllers\Controller;
use App\Http\Controllers\StatusController;
use App\Http\Resources\ClientResource;
use App\Http\Resources\OuResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\SaleResource;
use App\Http\Resources\WarehouseResource;
use App\Mobile\Dashboard;
use App\Models\Client;
use App\Models\Comment;
use App\Models\Company;
use App\Models\OrderHistory;
use App\Models\Ou;
use App\Models\Pod;
use App\Models\Product;
use App\Models\RiderSale;
use App\Models\Sale;
use App\Models\Sms;
use App\Models\Status;
use App\Models\Stk;
use App\Models\User;
use App\Models\Warehouse\Warehouse;
use App\Notifications\Mobile\Notify;
use App\Scopes\ClientScope;
use App\Scopes\OrderScope;
use Carbon\Carbon;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class ApiController extends Controller
{
    public function user()
    {
        $user = request()->user();
        $company = Company::first();
        $user->company = $company;
        return $user;
    }

    public function logout_rider()
    {
        request()->user()->currentAccessToken()->delete();
    }
    /**
     * Display a listing of warehouses.
     *
     * @return \Illuminate\Http\Response
     */
    public function warehouse($id)
    {
        return WarehouseResource::collection(Warehouse::where('ou_id', $id)->get());
    }

    /**
     * Display a listing of Operating units.
     *
     * @return \Illuminate\Http\Response
     */
    public function ou()
    {
        return OuResource::collection(Ou::all());
    }

    /**
     * Display a listing of clients.
     *
     * @return \Illuminate\Http\Response
     */
    public function clients()
    {
        return ClientResource::collection(Client::paginate(100));
    }

    /**
     * Display a listing of products.
     *
     * @return \Illuminate\Http\Response
     */
    public function products()
    {
        // return Product::paginate(200);
        $products = Product::with(['warehouses' => function ($query) {
            $query->setEagerLoads([]);
        }, 'seller' => function ($query) {
            $query->setEagerLoads([]);
        }])->paginate(500);

        $pro = new Product;
        // return $pro->transform_display_product($products);
        return ProductResource::collection($pro->transform_display_product($products));
    }


    public function product_variants($search)
    {

        // $products = Product::setEagerLoads([])
        //     ->where('product_name', 'LIKE', "%{$search}%")
        //     ->with([
        //         'inventory',
        //         'warehouses' => function ($query) {
        //             $query->setEagerLoads([]);
        //         },
        //         'seller' => function ($query) {
        //             $query->select('id', 'name')->setEagerLoads([]);
        //         }
        //     ])
        //     ->paginate(100);


        // return response()->json($transformedProducts);

        //  Product::paginate(200);
        $products = Product::setEagerLoads([])->where('product_name', 'LIKE', "%{$search}%")->with(['inventory', 'warehouses' => function ($query) {
            $query->setEagerLoads([]);
        }, 'seller' => function ($query) {
            $query->select('id', 'name')->setEagerLoads([]);
        }])->paginate(100);


        // $products = Product::setEagerLoads([])->where('vendor_id', 287)
        //     ->with(['inventory' => function ($query) {
        //         $query->setEagerLoads([]);
        //     }, 'warehouses' => function ($query) {
        //         $query->setEagerLoads([]);
        //     }])->paginate(100);

        $pro = new Product;
        $products = $pro->transform_display_product($products);


        return $products->map(function ($product) {
            return [
                'product_name' => $product->product_name,
                'sku_no' => $product->sku_no,
                'quantity' => $product->available_for_sale,
            ];
        });

        return ProductResource::collection($pro->transform_display_product($products));




        // $products = Product::withoutGlobalScope(ProductScope::class)->with(['inventory' => function ($query) {
        //     $query->setEagerLoads([]);
        // }, 'warehouses' => function ($query) {
        //     $query->setEagerLoads([]);
        // }, 'seller' => function ($query) {
        //     $query->setEagerLoads([]);
        // }, 'bins' => function ($query) {
        //     $query->setEagerLoads([]);
        // }])->where('active', $active)->paginate(500);
        // $product_t = new Product();
        // return $product_t->transform_display_product($products);
    }


    public function orders(Request $request)
    {
        // $user = $request->user();
        // if ($user->zone_id !== 13)  {
        //     $endDate = (request('end_date') === Carbon::today()) ? Carbon::parse(request('end_date')->subDay()) : request('end_date');

        //     $date = (request('start_date') && request('end_date')) ? [request('start_date'), $endDate] : [];


        // } else {
        //     $date = (request('start_date') && request('end_date')) ? [request('start_date'), request('end_date')] : [];
        // }

        $user = $request->user();
        $startDate = Carbon::parse(request('start_date'))->startOfDay();
        $endDate = Carbon::parse(request('end_date'))->endOfDay();

        // Use Carbon for date parsing if dates are provided
        $parsedStartDate = $startDate ? Carbon::parse($startDate) : null;
        $parsedEndDate = $endDate ? Carbon::parse($endDate) : null;

        // Adjust end date if user zone_id is not 13 and end date is today
        // if ($user->zone_id !== 13 && $parsedEndDate && $parsedEndDate->isToday()) {
        //     $parsedEndDate = $parsedEndDate->subDay();
        // }

        // Set date range if both dates are available
        $date = ($parsedStartDate && $parsedEndDate) ? [$parsedStartDate, $parsedEndDate] : [];


        $rider_id = $request->user()->id;

        $limit = 100;
        // $limit = $request->limit ?? 30;

        if (!empty($date)) {
            $rider_sale = RiderSale::whereBetween('updated_at', $date)
                ->where('rider_id', $rider_id)
                ->get('sale_id');
        } else {
            $rider_sale = RiderSale::whereDate('updated_at', Carbon::today())
                ->where('rider_id', $rider_id)
                ->get('sale_id');
        }

        $ids = $rider_sale->pluck('sale_id')->toArray();

        // $statuses = ['Returned', 'Delivered', 'pending approval', 'Return pending', 'Cancelled', 'Undispatched'];

        $query = Sale::withoutGlobalScope(OrderScope::class)
            ->setEagerLoads([])
            ->with(['client' => function ($q) {
                $q->withoutGlobalScope(ClientScope::class)->setEagerLoads([]);
            }])
            ->whereIn('delivery_status', ['In Transit', 'Dispatched'])
            ->whereIn('id', $ids);
        // ->whereNotIn('delivery_status', $statuses);

        if (!empty($date)) {
            // $query->whereBetween('dispatched_on', $date);
            $query->whereBetween('updated_at', $date);
        } else {
            // $query->whereDate('dispatched_on', Carbon::today());
            $query->whereDate('updated_at', Carbon::today());
        }

        $sales = $query->paginate($limit);

        return SaleResource::collection($sales);
    }



    public function complete(Request $request, $status)
    {
        $rider_id = $request->user()->id;
        $ids = RiderSale::whereBetween('updated_at', [Carbon::today()->subDays(14), Carbon::tomorrow()])->where('rider_id', $rider_id)->pluck('sale_id');
        // $ids = [];
        // foreach ($rider_sale as $key => $value) {
        //     $ids[] = $value->sale_id;
        // }
        $statuses = [];

        if ($status == 'Delivered') {
            $statuses = ['Delivered', 'pending approval'];
        } elseif ($status == 'Returned') {
            $statuses = ['Returned', 'Return pending'];
        }

        return SaleResource::collection(
            Sale::withoutGlobalScope(OrderScope::class)->setEagerLoads([])
                ->when(($status == 'Delivered'), function ($q) {
                    return $q->orderBy('delivered_on', 'DESC')->whereBetween('delivered_on', [Carbon::today()->subDays(14), Carbon::tomorrow()]);
                })
                ->when(($status == 'Returned'), function ($q) {
                    return $q->orderBy('returned_on', 'DESC')->whereBetween('returned_on', [Carbon::today()->subDays(14), Carbon::tomorrow()]);
                })
                ->with(['client' => function ($q) {
                    $q->withoutGlobalScope(ClientScope::class)->setEagerLoads([]);
                }])->whereIn('id', $ids)->whereIn('delivery_status', $statuses)->paginate(50)
        );
    }

    public function order($id)
    {
        $sale = Sale::with(['client' => function ($q) {
            $q->withoutGlobalScope(ClientScope::class)->setEagerLoads([]);
        }])->find($id);
        $sales = new  SaleResource($sale);

        return $sales;
    }

    public function payment_confirmation($id)
    {
        $sale = Sale::setEagerLoads([])->with(['transactions'])->find($id);

        $transactions = $sale->transactions;
        $total = 0;
        $code = [];
        if ($transactions) {
            foreach ($transactions as $key => $transaction) {
                $total += $transaction->TransAmount;
                $code[] = $transaction->TransID;
            }
        }

        return response()->json(['paid_amount' => $total, 'code' => json_encode($code), 'order_amount' => $sale->total_price]);
    }

    public function order_scan(Request $request, $order_no)
    {
        $sale = Sale::where('order_no', $order_no)->first();
        $rider_id = $request->user()->id;
        if ($sale) {
            $rider_sale = RiderSale::where('sale_id', $sale->id)->where('rider_id', $rider_id)->get('sale_id');
            if (count($rider_sale) < 1) {
                abort(422, 'Order not assigned to you');
            }
            return new SaleResource($sale);
        } else {
            abort(422, 'Order not found');
        }
    }

    /**
     * Create a product.
     *
     * @return \Illuminate\Http\Response
     */
    public function create_product(Request $request)
    {
        $product = new Product();
        // $product->description = $request->description;

        $product->product_name = $request->product_name;
        $product->user_id = $this->logged_user()->id;
        $product->vendor_id = $request->vendor_id;
        // $sku_no = new AutoGenerate;
        $product->sku_no =  IdGenerator::generate(['table' => 'products', 'field' => 'sku_no', 'length' => 15, 'prefix' => 'SKU_']);
        $product->save();
    }

    /**
     * Track an order.
     *
     * @return \Illuminate\Http\Response
     */
    public function order_tracking($order)
    {
        return $orders = new SaleResource(Sale::setEagerLoads([])->with(['products', 'client', 'seller', 'orderHistory' => function ($query) {
            $query->setEagerLoads([]);
        }])->where('order_no', $order)->firstOrFail());

        $orders = $orders->order_history->transform(function ($item) {
            $item->update_comment = 'test';
        });

        return $orders;
    }

    public function upload(Request $request)
    {

        return $request->all();


        if ($request->hasFile('files')) {
            $path = $request->file('files')->store('images');
            return 'success';
        } else {
            return 'error';
        }
        return 'error';
        return 'success';

        return Sale::find($request->id)->update(['delivery_status' => $request->status]);
    }
    //    public function image(Request $request)
    //    {
    //     return ($request->all());
    //     dd($request->all());
    //    }
    public function mobile_status(Request $request, $id)
    {
        // return $request->all();

        $user = $request->user();
        $agent_id = $user->id;
        $notes = $request->notes;
        $pod_img = new Pod();
        $image_path = null;
        $signature_path = null;

        $image_data = [
            'user_name' => $user->name,
        ];
        if ($request->image) {
            $image_data['image'] = $request->image;
            $image_path = $pod_img->store_image($image_data, 'image');
        }
        if ($request->signature) {
            $image_data['image'] = $request->signature;
            $signature_path = $pod_img->store_image($image_data, 'signature');
        }
        // $sale = Sale::select('total_price')->setEagerLoads([])->with('client:id,phone,name')->find($id);

        if ($request->status == 'Delivered') {
            $sale = Sale::select('total_price')->setEagerLoads([])->with('client:id,phone,name')->find($id);
            $pod = Pod::updateOrCreate(
                ['sale_id' => $id],
                [
                    'image' => 'storage/' . $image_path,
                    'signature' => 'storage/' . $signature_path,
                    'notes' => $notes
                ]
            );
            $status = ($sale->total_price > 0) ? 'pending approval' : 'Delivered';
            $data = ['delivery_status' => $status];
            if ($request->lon && $request->lat) {
                $sale->update(['longitude' => $request->lon, 'latitude' => $request->lat]);
            }
        } else {
            $status = 'Undispatched';
            // $status = 'Return pending';
            if ($request->reason) {
                $system_user = User::first();
                $comment = new Comment();
                $comment->user_id = $system_user->id;
                $comment->sale_id = $id;
                $comment->comment = $request->reason;
                $comment->save();
                $this->comment($request->reason, $id, $user);
            }


            $history_comment = 'Order Undispatched by ' . $user->name;
            $customer_notes = $request->reason . ' by ' . $user->name;
            $data = ['delivery_status' => $status, 'history_comment' => $history_comment, 'customer_notes' => $customer_notes];

            // $sms = new Sms();
            // $sms->sms([$sale->client->phone], 'Order Undispatched by ' . $user->name . ' due to ' . $request->reason);
        }

        // $data = ['delivery_status' => $status, 'agent_id' => $agent_id];

        $status = new Status();
        $status->status_update(new Request($data), $id);


        // $message = 'New order delivered by <b style="color: red">' . $user->name . '</b>';
        // $action = $id;
        // $users = User::all();
        // broadcast(new MobileEvent($user, $message, $action))->toOthers();
        // Notification::send($users, new Notify($user, $message, $action));
    }

    public function updateCoordinates(Request $request, $id)
    {
        $sale = Sale::whereNull('latitude')->whereNull('longitude')->where('id', $id)->first();

        if ($sale) {
            $sale->update(['longitude' => $request->lon, 'latitude' => $request->lat]);
        }
    }

    public function comment($comment, $id, $user)
    {
        $history  = new OrderHistory();
        $history->action = 'Commented';
        $reference_no = IdGenerator::generate(['table' => 'order_histories', 'field' => 'reference_no', 'length' => 15, 'prefix' => 'REF_']);
        $history->comment = $comment;
        $history->update_comment = 'Rider comment added';
        $history->reference_no = $reference_no;
        $history->user_name = $user->name;

        $history->user_id = 1;
        $history->sale_id = $id;
        $history->save();
    }
    public function dashboard_stats(Request $request)
    {

        $rider_id = $request->user()->id;

        $orders = RiderSale::whereMonth('created_at', Carbon::now()->month)->where('rider_id', $rider_id);
        // $today_success = $orders->where('delivery_status', 'Delivered')->whereDate('created_at', Carbon::today())->get('sale_id');
        $ids_1 = [];
        foreach ($orders->get('sale_id') as $value) {
            $ids_1[] = $value->sale_id;
        }

        $orders = $orders->whereDate('created_at', Carbon::today());

        // return $orders->get();

        $ids_2 = [];
        foreach ($orders->get('sale_id') as $value) {
            $ids_2[] = $value->sale_id;
        }

        // return $ids_2;


        $dashboard = new Dashboard;

        return [
            'today_orders' => $dashboard->today_orders($ids_2),
            'today_delivery' => $dashboard->today_delivery($ids_2),
            'today_returns' => $dashboard->today_returns($ids_2),
            'pending' => $dashboard->pending_count($ids_2),
            'sale_cod' => $dashboard->sale_cod($ids_2),
            'orders' => $dashboard->order_count($ids_1),
            'delivery_count' => $dashboard->delivery_count($ids_1),
            'return_count' => $dashboard->return_count($ids_1),
        ];
    }
    public function on_duty(Request $request) {}

    public function order_status(Request $request, $status)
    {
        $rider_id = $request->user()->id;
        // $rider_sale = RiderSale::where('rider_id', $rider_id)->get('sale_id');
        $rider_sale = RiderSale::where('rider_id', $rider_id)->get('sale_id');
        $ids = [];
        foreach ($rider_sale as $key => $value) {
            $ids[] = $value->sale_id;
        }
        if ($status == 'Delivered') {
            $statuses = ['pending approval', 'Delivered',];
        } else {
            $statuses = ['Return pending', 'Returned',];
        }
        $sale_order = Sale::setEagerLoads([])->with('client')->whereIn('id', $ids)->whereIn('delivery_status', $statuses)->paginate(50);


        return SaleResource::collection($sale_order);
    }

    public function order_filter(Request $request)
    {
        // return $request->all();
        $rider_id = $request->user()->id;
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $rider_sale = RiderSale::whereBetween('created_at', [$start_date, $end_date])->where('rider_id', $rider_id)->get('sale_id');

        $ids = [];
        foreach ($rider_sale as $key => $value) {
            $ids[] = $value->sale_id;
        }

        $statuses = ['Returned', 'Delivered', 'pending approval', 'Return pending'];

        return SaleResource::collection(Sale::setEagerLoads([])->whereNotIn('delivery_status', $statuses)->with('client')->whereIn('id', $ids)->paginate(50));
    }

    public function api_sms(Request $request, $id)
    {
        $rider_phone = $request->user()->phone;
        $order = Sale::setEagerLoads([])->with('client')->find($id);
        $client = $order->client;
        $phone = [$client->phone];
        $country_id = $order->country_id;

        if ($order->delivery_status == 'Dispatched') {
            $order->update(['delivery_status' => 'Undispatched']);
        }
        // $phone = ['0743895505'];
        // $message = 'Dear ' . $client->name . ' our rider is trying to reach you for delivery of your order. You can reach our rider through ' . $rider_phone . ' to collect your order.';

        $message = "I hope this message finds you well. I’ve been trying to reach you regarding your order " . $order->order_no . " delivery scheduled for today to no avail. To ensure a smooth delivery, kindly confirm the delivery address and preferred time soonest. For call back, reach me on " . $rider_phone . " or reply to this message.";
        $sms = new Sms();
        $sms->sms($phone, $message, $country_id);

        return;
    }

    public function search_order(Request $request, $search)
    {
        $rider_id = $request->user()->id;
        $date_arr = [Carbon::now()->subMonths(2), Carbon::today()];

        $rider_sale = RiderSale::whereBetween('created_at', $date_arr)->where('rider_id', $rider_id)->get('sale_id');

        // $today_success = $orders->where('delivery_status', 'Delivered')->whereDate('created_at', Carbon::today())->get('id');
        $ids = [];
        foreach ($rider_sale as $key => $value) {
            $ids[] = $value->sale_id;
        }

        $statuses = ['Returned', 'Delivered', 'pending approval', 'Return pending'];

        // return SaleResource::collection(Sale::setEagerLoads([])->with('client')->whereIn('id', $ids)->whereNotIn('delivery_status', $statuses)->paginate(50));


        return Sale::with('client')->where(function ($query) use ($search) {
            $query->where('order_no', 'LIKE', '%' . $search . '%');
            $query->orWhereHas('client', function ($q) use ($search) {
                $q->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', '%' . $search . '%');
                    $q->orWhere('phone', 'LIKE', '%' . $search . '%');
                    $q->orWhere('address', 'LIKE', '%' . $search . '%');
                    $q->orWhere('email', 'LIKE', '%' . $search . '%');
                });
            });
        })->whereIn('id', $ids)->whereNotIn('delivery_status', $statuses)->paginate(50);
    }

    public function stk_response(Request $request, $order_no)
    {
        // $number = Crypt::decryptString($order_no);
        $number = $order_no;

        $sale = Sale::where('order_no', $number)->first();

        $res = serialize($request->all());

        $stk = new Stk();
        $data = unserialize($stk->res);
        $arr  = $data['Body']['stkCallback']['CallbackMetadata']['Item'];

        $data_arr = [];

        foreach ($arr as $key => $value) {
            $data_arr[$value['Name']] = (array_key_exists('Value', $value)) ? $value['Value'] : null;
        }
        // return  $data_arr;

        // $stk = new Stk();
        $stk->PhoneNumber = $data_arr['PhoneNumber'];
        $stk->MpesaReceiptNumber = $data_arr['MpesaReceiptNumber'];
        $stk->Amount = $data_arr['Amount'];
        $stk->sale_id = $sale->id;
        $stk->save();



        $status_update = new StatusController;


        $data = ['delivery_status' => 'Delivered', 'mpesa_code' => $stk->MpesaReceiptNumber, 'paid' => 1];

        $this->status_update(new Request($data), $sale->id);


        return $status_update->status_update(new Request($data), $sale->id);

        // $sale->
    }

    public function charges($city)
    {
        if (!$city) {
            abort(422, 'City is required');
        }
        if (Str::lower($city) == 'nairobi') {
            return response()->json(['message' => 'success', 'charges' => 300]);
        } else {
            return response()->json(['message' => 'success', 'charges' => 450]);
        }
    }
}
