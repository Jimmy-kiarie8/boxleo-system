<?php

namespace App\Http\Controllers\Api\Agent;

use App\Events\MobileEvent;
use App\Http\Controllers\Controller;
use App\Http\Controllers\StatusController;
use App\Http\Resources\ClientResource;
use App\Http\Resources\OuResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\SaleResource;
use App\Http\Resources\OrderResource;
use App\Mobile\Dashboard;
use App\Models\Client;
use App\Models\Comment;
use App\Models\Company;
use App\Models\OrderHistory;
use App\Models\Ou;
use App\Models\Pod;
use App\Models\Product;
use App\Models\ProductSale;
use App\Models\RiderSale;
use App\Models\Sale;
use App\Models\Sku;
use App\Models\Sms;
use App\Models\Status;
use App\Models\Stk;
use App\Models\User;
use App\Models\Warehouse\Warehouse;
use App\Notifications\Mobile\Notify;
use Carbon\Carbon;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AgentApiController extends Controller
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

    public function store(Request $request)
    {
        // Log::debug($request->all());
        $order_no = ($request->reference_id) ?  $request->reference_id :  $request->order_no;

        if (!$order_no) {
            abort(422, 'Order number is required');
        }
        if (Sale::where('order_no', $order_no)->exists()) {
            abort(422, 'Order number ' . $order_no . ' is taken');
        }


        $validator = Validator::make($request->all(), [
            'order_no' => 'required|string',
            'client_email' => 'nullable|email',
            'client_name' => 'required|string',
            'client_phone' => 'required|string',
            'client_address' => 'required|string',
            'client_city' => 'nullable|string',
            'client_name' => 'required|string',
            'client_latitude' => 'nullable',
            'client_longitude' => 'nullable',
            'client_region' => 'nullable|string',
            'client_zipcode' => 'nullable|string',
            'sender_address' => 'nullable|string',
            'sender_city' => 'nullable|string',
            'sender_name' => 'nullable|string',
            'sender_latitude' => 'nullable',
            'sender_longitude' => 'nullable',
            'currency' => 'nullable|string',
            'notes' => 'nullable|string',
            'line_items' => 'nullable|array',
            'line_items.*.price' => 'nullable',
            'line_items.*.quantity' => 'nullable|integer',
            'line_items.*.variant.sku' => 'nullable|string',
            'paid' => 'nullable|boolean',
            'payment_method' => 'nullable|string',
            'addition_cost' => 'nullable',
            'shipping_charge' => 'nullable'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
        

        try {
            DB::beginTransaction();


            $total = 0;
            $currency = $request->currency;
            $customer_notes = $request->notes;
            $status = 'New';
            $delivery_date = null;

            // $warehouse_code = (array_key_exists('label', $request->warehouse)) ? $request->warehouse['label'] : null;

            $customer_name = $request->client_name;
            $customer_email = $request->client_email;
            $customer_mobile = $request->client_phone;
            $address = $request->client_address;
            $city = $request->client_city;
            $paid = $request->paid;
            $payment_method = ($request->payment_method)  ? $request->payment_method : null;
            $shipping_charge = ($request->shipping_charge)  ? $request->shipping_charge : 0;
            $addition_cost = ($request->addition_cost)  ? $request->addition_cost : 0;
            $ou_code = 'KE';
            $user_id = 1;

            $client = Client::where('address', $customer_mobile)->where('name', $customer_name)->first();
            $ou_id = 1;

            $ou = Ou::where('ou_code', $ou_code)->first();
            // if (!$ou) {
            //     abort(422, 'Operating unit not found');
            // }
            $sale = new Sale();

            if ($ou) {
                $ou_id = $ou->id;
            } elseif (auth('api')->check()) {
                $ou_id = auth('api')->user()->ou_id;
            }
            // $ou = Country::where()
            if (!$client) {
                $client = Client::create([
                    'ou_id' => $ou_id,
                    'seller_id' => $user_id,
                    'name' => $customer_name,
                    'email' => $customer_email,
                    'phone' => $customer_mobile,
                    'address' => $address,
                    'city' => $city
                ]);
            }


            // foreach ($request->line_items as $key => $line_item) {
            //     $product_sku = $line_item['variant']['sku'];
            //     $quantity = $line_item['quantity'];
            //     $price = $line_item['price'];
            //     $total += $price;

            //     $product = Product::where('sku_no', $product_sku)->first();

            //     if (!$product) {
            //         abort(422, 'Product with SKU ' . $product_sku .  ' does not exist');
            //     }
            // }



            $total += $shipping_charge + $addition_cost;


            $sale->total_price = $total;
            $sale->sub_total = $total;
            $sale->user_id =  1;
            $sale->client_id = $client->id;
            $sale->seller_id = 227;
            $sale->customer_notes = $customer_notes;
            $sale->order_no = $order_no;

            $sale->paid = $paid;
            $sale->payment_method = $payment_method;
            // $sale->shipping_charge = $shipping_charge;
            // $sale->addition_cost = $addition_cost;

            $sale->delivery_status = ($request->delivery_status) ? $request->delivery_status : 'New';
            $sale->status = (Str::lower($sale->delivery_status) == 'scheduled') ? 'Confirmed' : 'New';
            $sale->delivery_date = ($request->delivery_date) ? $request->delivery_date : Carbon::tomorrow();


            // $warehouse = Warehouse::where('code', $warehouse_code)->first();
            // $sale->warehouse_id = $warehouse->id;
            $sale->ou_id = $ou_id;
            $sale->warehouse_id = Warehouse::where('ou_id', $ou_id)->first()->id;
            $sale->platform = 'API';

            $sale->save();

            $product = Product::find(3294);

            // foreach ($request->line_items as $key => $line_item) {
            //     $product_sku = $line_item['variant']['sku'];
            //     $quantity = $line_item['quantity'];
            //     $price = $line_item['price'];



                $sku_id = Sku::where('product_id', $product->id)->select('id', 'sku_no')->first();
                $product_sale = new ProductSale();
                $product_sale->sale_id = $sale->id;
                $product_sale->product_id = $product->id;
                $product_sale->seller_id = $product->vendor_id;
                $product_sale->sku_id = $sku_id->id;
                $product_sale->sku_no = $sku_id->sku_no;
                $product_sale->price = $product->price;

                $product_sale->quantity = 1;

                $product_sale->quantity_tobe_delivered = 1;
                $product_sale->total_price = $product_sale->price * $product_sale->quantity;
                $product_sale->save();
            // }

            $order = Sale::with(['orderHistory' => function ($query) {
                $query->setEagerLoads([]);
            }])->find($sale->id);

            $order =  new OrderResource($order);

            DB::commit();

            return response()->json(['message' => 'Order ' . $order_no . ' created', 'data' => $order]);
        } catch (\Throwable $e) {
            DB::rollBack();
            // Log::debug($e);
            abort(422, 'Something went wrong');
        }
    }


    public function orders(Request $request)
    {
        // $user_id =  $request->user()->id;
        $page = ($request->page) ? $request->page : 1;
        $limit = ($request->limit) ? $request->limit : 10;
        // $statuses = ['Returned', 'Delivered', 'pending approval', 'Return pending', 'Cancelled'];
        $statuses = ['pending approval', 'Return pending'];

        return SaleResource::collection(Sale::setEagerLoads([])->latest()->with('client')->whereNotIn('delivery_status', $statuses)->paginate($limit));
    }



    public function complete(Request $request, $status)
    {
        $rider_id = $request->user()->id;
        $ids = RiderSale::whereBetween('updated_at', [Carbon::today()->subDays(30), Carbon::tomorrow()])->where('rider_id', $rider_id)->pluck('sale_id');
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
            Sale::setEagerLoads([])
                ->when(($status == 'Delivered'), function ($q) {
                    return $q->whereBetween('delivered_on', [Carbon::today()->subDays(30), Carbon::tomorrow()]);
                })
                ->when(($status == 'Returned'), function ($q) {
                    return $q->whereBetween('returned_on', [Carbon::today()->subDays(30), Carbon::tomorrow()]);
                })
                ->with('client')->whereIn('id', $ids)->whereIn('delivery_status', $statuses)->paginate(50)
        );
    }

    public function order($id)
    {
        $sale = Sale::find($id);
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
        // return $request->signature;
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

        if ($request->status == 'Delivered') {
            $pod = Pod::updateOrCreate(
                ['sale_id' => $id],
                [
                    'image' => 'storage/' . $image_path,
                    'signature' => 'storage/' . $signature_path,
                    'notes' => $notes
                ]
            );
            $status = 'pending approval';
        } else {
            $status = 'Return pending';
            if ($request->reason) {
                $system_user = User::first();
                $comment = new Comment();
                $comment->user_id = $system_user->id;
                $comment->sale_id = $id;
                $comment->comment = $request->reason;
                $comment->save();
                $this->comment($request->reason, $id, $user);
            }
        }

        $data = ['delivery_status' => $status, 'agent_id' => $agent_id];

        $status = new Status();
        $status->status_update(new Request($data), $id);

        $message = 'New order delivered by <b style="color: red">' . $user->name . '</b>';
        $action = $id;
        $users = User::all();
        broadcast(new MobileEvent($user, $message, $action))->toOthers();
        Notification::send($users, new Notify($user, $message, $action));
    }

    public function comment(Request $request, $id)
    {
        // $user = $request->user();

        $history  = new OrderHistory();
        $history->action = 'Commented';
        $reference_no = make_reference_id('CMT', Comment::max('id') + 1);
        $history->comment = $request->comment;
        $history->update_comment = 'Agent comment added';
        $history->reference_no = $reference_no;
        $history->user_name = 'Jimmy';

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
    public function on_duty(Request $request)
    {
    }

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
        // $phone = ['0743895505'];
        $message = 'Dear ' . $client->name . ' our rider is trying to reach you for delivery of your order. You can reach our rider through ' . $rider_phone . ' to collect your order.';
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
