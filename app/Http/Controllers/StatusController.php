<?php

namespace App\Http\Controllers;

use App\Jobs\BulkUpdateJob;
use App\Jobs\ReturnPrintJob;
use App\Models\Company;
use App\Models\DailyStats;
use App\Models\OrderHistory;
use App\Models\ProductInventory;
use App\Models\Warehouse\Product_warehouse;
use App\Models\ProductSale;
use App\Models\RiderSale;
use App\Models\Sale;
use App\Models\SaleZone;
use App\Models\Status;
use App\Models\User;
use App\Models\Warehouse\Bin;
use App\Models\Warehouse\ProductBin;
use App\Models\Warehouse\Warehouse;
use App\Models\Zone;
use App\Shopify\ShopifyService;
use Barryvdh\DomPDF\Facade\Pdf;
use DefStudio\Telegraph\Models\TelegraphChat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StatusController extends Controller
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

        if (Auth::check()) {
            $cacheKey = 'status' . Auth::id();
            return Cache::remember($cacheKey, now()->addHours(10), function () {
                if (Auth::user()) {

                    if (Auth::user()->hasRole('Call center')) {
                        $statusArray = [
                            ['name' => 'Cancelled'],
                            ['name' => 'Scheduled'],
                            ['name' => 'Out Of Stock'],
                            ['name' => 'Pending'],
                            ['name' => 'Pending Confirmation'],
                            ['name' => 'New']
                        ];

                        $transformedArray = array_map(function ($status) {
                            return ['status' => $status['name']];
                        }, $statusArray);
                        return $transformedArray;
                    }
                }
                return Status::all();
            });
        }
        return Status::all();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return Status::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\model\Status  $status
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
     * @param  \App\model\Status  $status
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $status = Status::find($id);
        $status->status = $request->status;
        $status->save();
        return $status;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\model\Status  $status
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Status::find($id)->delete();
    }





    protected function getValidator(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'delivery_status' => 'required|string',
            // Add other relevant fields for validation
        ]);

        $status = $request->delivery_status;

        $validator->sometimes('mpesa_code', 'required', function ($input) use ($status) {
            return $status == 'Delivered' && $input->payment_method == 'Mpesa';
        });

        $validator->sometimes(['zone_from', 'zone_to'], 'required', function ($input) use ($status) {
            return $status == 'Dispatched' && Zone::where('ou_id', $this->logged_user()->ou_id)->where('default_zone', true)->exists();
        });

        $validator->sometimes('delivery_date', 'required', function ($input) use ($status) {
            return $status == 'Scheduled';
        });

        $validator->after(function ($validator) use ($request, $status) {
            if ($status == 'Refund' && !(Auth::user()->hasRole('Super admin') || Auth::user()->hasRole('Admin'))) {
                $validator->errors()->add('delivery_status', 'You are not permitted to update this status');
            }
        });

        return $validator;
    }



    public function dispatch_orders1(Request $request)
    {
        $request->validate([
            'zone_from' => 'required',
            'zone_to' => 'required'
        ]);
        $status = new Status();
        foreach ($request->orders as $order) {
            // return $request->branch_id;
            $order['zone_from'] = $request->zone_from;
            $order['zone_to'] = $request->zone_to;
            $order['rider_id'] = $request->rider_id;
            $order['delivery_status'] = ($request->zone_to == 1 || $request->zone_to == 13) ? 'Dispatched' : 'In Transit';
            $status->status_update(new Request($order), $order['id']);
        }
        return;
    }

    public function assign_agent(Request $request, $id)
    {
        if ($id === 'null') {
            $this->unassign_agent($request);
            return;
        }
        $sales = Sale::whereIn('id', $request->all())->get();
        $to = User::select('id', 'name')->find($id);

        foreach ($sales as $key => $sale) {
            $sale = Sale::find($sale->id);
            $agent_id = $sale->agent_id;
            $from = User::select('id', 'name')->find($agent_id);

            $from_user = ($from) ? $from->name : '--';


            $sale->history_comment = 'Order agent updated from <b style="color:red"> ' . $from_user . '</b> to <b style="color:#1564c0;"> ' . $to->name;
            $sale->agent_id = $id;

            $sale->save();
        }
        // Sale::whereIn('id', $request->all())->update(['agent_id' => $id]);
    }


    public function unassign_agent(Request $request)
    {
        $sales = Sale::whereIn('id', $request->all())->get();

        foreach ($sales as $key => $sale) {
            $sale = Sale::find($sale->id);
            $agent_id = $sale->agent_id;
            $from = User::select('id', 'name')->find($agent_id);

            $from_user = ($from) ? $from->name : '--';


            $sale->history_comment = 'Order agent unassined from <b style="color:red"> ' . $from_user . '</b> to <b style="color:#1564c0;"> ';
            $sale->agent_id = null;

            $sale->save();
        }
        // Sale::whereIn('id', $request->all())->update(['agent_id' => $id]);
    }


    public function update_order_status(Request $request)
    {
        if ($request->status === 'Cancelled') {
            return $this->handleCancelledStatus($request);
        }

        if ($request->status === 'Received') {
            return $this->handleReceivedStatus($request);
        }

        if (Auth::user()->hasPermissionTo('Order update delivered or returned')) {
            return $this->handleOtherStatuses($request);
        }

        abort(422, 'You are not authorized to perform this action');
    }

    protected function handleCancelledStatus(Request $request)
    {
        $user = Auth::user();
        $cancelled_on = $request->update_date;
        $ids = collect($request->orders)->pluck('id');

        $orders = Sale::whereIn('id', $ids)
            ->whereIn('delivery_status', ['Pending', 'Undispatched', 'Out Of Stock'])
            ->get();

        if ($orders->isEmpty()) {
            throw new Exception('No valid orders found for dispatch.');
        }

        Sale::whereIn('id', $orders->pluck('id'))
            ->update(['delivery_status' => 'Cancelled', 'history_comment' => 'Order cancelled by ' . $user->name, 'status' => 'Closed', 'cancelled_on' => $cancelled_on]);

        $this->logOrderHistory($orders, 'Order cancelled by ' . $user->name, $user->name);

        return;
    }

    protected function handleReceivedStatus(Request $request)
    {
        $user = Auth::user();
        $ids = collect($request->orders)->pluck('id');
        $requestOrders = $request->orders;

        $orders = Sale::whereIn('id', $ids)
            ->where('delivery_status', 'Awaiting Return')
            ->get();

        if ($orders->isEmpty()) {
            // throw new Exception('No valid orders found for receive.');
            abort(422, "No valid orders found for receive.");
        }

        foreach ($orders as $sale) {
            $return_notes = null;
            foreach ($requestOrders as $key => $item) {
                if ($sale->id === $item['id']) {
                    $return_notes = $item['return_notes'];
                }
            }
            // abort(422, $return_notes);
            $sale->return_notes = $return_notes;
            $sale->delivery_status = 'Return Received';
            $sale->history_comment = 'Order updated to Return Received by ' . $user->name;
            $sale->save();
        }

        $this->logOrderHistory($orders, 'Order updated to Return Received by ' . $user->name, $user->name);

        // get zone_ids from the orders
        $zone_ids = $orders->pluck('zone_id')->unique()->toArray();

        $zones = Zone::whereIn('id', $zone_ids)->pluck('name')->toArray();

        $company = Company::first();

        ReturnPrintJob::dispatch($orders, $zones, $company);

        return;
    }

    protected function handleOtherStatuses(Request $request)
    {
        $status = new Status();
        foreach ($request->orders as $order) {
            $order['delivery_status'] = $request->status;
            $order['update_date'] = $request->update_date;

            if ($request->status === 'Delivered') {
                $order['delivered_on'] = $request->update_date;
            } elseif ($request->status === 'Returned') {
                $order['returned_on'] = $request->update_date;
            } elseif ($request->status === 'Cancelled') {
                $order['cancelled_on'] = $request->update_date;
            }

            $status->status_update(new Request($order), $order['id']);
        }
    }

    protected function logOrderHistory($orders, $comment, $userName)
    {
        $orderHistory = [];
        foreach ($orders as $his) {
            $orderHistory[] = [
                'action' => 'Order updated',
                'comment' => '',
                'update_comment' => $comment,
                'user_name' => $userName,
                'tracking_comment' => $comment,
                'user_id' => Auth::id(),
                'sale_id' => $his->id,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        OrderHistory::insert($orderHistory);
    }

    public function warehouse($data)
    {
        $sale = $data['sale'];
        $sale_update = $data['sale_update'];
        $warehouse_id = $data['warehouse_id'];
        $status = $data['status'];
        $old_status = $data['old_status'];
        $partial_delivery = (array_key_exists('partial_delivery', $data)) ? true : false;
        // dd($sale, $warehouse_id, $status, $old_status);
        foreach ($sale_update['products'] as $product) {
            $product_sale = ProductSale::find($product['pivot']['id']);
            $warehouse = new Product_warehouse();
            $warehouse_id = $warehouse_id;
            $seller_id = $product['pivot']['seller_id'];
            $product_id = $product['pivot']['product_id'];

            if ($partial_delivery) {
                $quantity = $product['pivot']['quantity_delivered'];
            } else {
                $quantity = ($product['pivot']['quantity_tobe_delivered'] > 0) ? $product['pivot']['quantity_tobe_delivered'] : $product['pivot']['quantity'];
            }

            $daily_start = new DailyStats();


            if ($status == 'Delivered' && !$product['pivot']['delivered']) {
                // dd($product_sale->delivered);
                if (!$product_sale->delivered) {
                    $warehouse->delivered_items($warehouse_id, $seller_id, $product_id, $quantity, $status, $old_status);
                }
                $product_sale->sent = true;
                $product_sale->delivered = true;
                $product_sale->quantity_delivered = $quantity;
                $product_sale->save();

                // $daily_start->delivered_count($quantity, $product_id);
            } elseif (!$product['pivot']['sent']) {

                // dd($warehouse_id, $seller_id, $product_id, $quantity);
                // dd($product['pivot']['delivered']);
                // dd($product_sale->delivered);
                // else {
                // } elseif ($status == 'Scheduled' || $status == 'Dispatched') {

                if ($status == 'Dispatched') {
                    $product_sale->quantity_sent = $quantity;
                    // $daily_start->dispatched_count($quantity, $product_id);
                }
                if ($status == 'Scheduled') {
                    // $daily_start->scheduled_count($quantity, $product_id);
                }
                if (!$product_sale->sent) {

                    // dd($product_sale, $product['pivot']['id']);
                    $warehouse->reduce_items($warehouse_id, $seller_id, $product_id, $quantity, $status, $old_status);

                    $product_sale->sent = true;
                    // $product_sale->quantity_sent = $product['pivot']['quantity'];
                    $product_sale->save();
                }
                // }

                // var_dump($product_sale->quantity_sent);
                // return $product_sale;
            }
        }
    }

    public function print_change(Request $request, $id)
    {
        // return $request->all();
        if (!$request->comment) {
            abort(422, 'A comment is required');
        }
        $user = $this->logged_user();
        $sale = Sale::find($id);

        $comment_ = ($request->printed) ? 'not printed' : 'printed';
        $sale->history_comment = 'Order print updated to <b style="color:red">' . $comment_ . ' </b><b style="color: #1565c0">(Comment: ' . $request->comment . ')</b>';

        $sale->history_comment;
        $sale->printed = !$request->printed;
        $sale->user_id = Auth::id();
        $sale->save();


        if ($request->printed) {
            $message = '*' . $sale->order_no . '* has been reprinted by *' . Auth::user()->name . '*. Reason: ' . $request->comment;
            $chat = TelegraphChat::first();
            $chat->markdown($message)->send();
        }

        // $comment = new Comment();
        // return $comment->comment($request->all(), $id);
        return $sale;
    }

    public function print_mult_change(Request $request)
    {
        // return $request->all();
        foreach ($request->orders as $value) {
            $data = [
                'printed' => $request->printed,
                'comment' => $request->comment
            ];
            $this->print_change(new Request($data), $value['id']);
        }
    }

    public function sales_update(Request $request)
    {
        $form = $request->form;
        $customer_notes = (array_key_exists('customer_notes', $form)) ?  $form['customer_notes'] : null;
        $delivery_date = (array_key_exists('delivery_date', $form)) ?  $form['delivery_date'] : null;
        $delivery_status = (array_key_exists('delivery_status', $form)) ?  $form['delivery_status'] : null;
        $zone_from = (array_key_exists('zone_from', $form)) ?  $form['zone_from'] : null;
        $zone_to = (array_key_exists('zone_to', $form)) ?  $form['zone_to'] : null;
        // return $request->all();

        $orders = $request->orders;
        $status = new Status();

        // $items = new Request($form);
        foreach ($orders as $order) {
            $order['zone_from'] = $zone_from;
            $order['zone_to'] = $zone_to;
            $order['delivery_status'] = $delivery_status;
            $order['delivery_date'] = $delivery_date;
            if ($customer_notes) {
                $order['customer_notes'] = $customer_notes;
            }
            $status->status_update(new Request($order), $order['id']);
        }
    }

    public function status_update(Request $request, $id)
    {
        $callCenterStatus = ['Scheduled', 'Cancelled', 'Reschedule', 'Pending', 'Out Of Stock'];
        $operationsStatus = ['Dispatched', 'Undispatched', 'In Transit'];
        $financeStatus = ['Delivered', 'Returned', 'Refund'];
        $warehouseStatus = ['Out Of Stock'];
        $followingUpStatus = ['Reschedule'];

        // if($request->delivery_status == 'Delivered'){
        //     $sale = Sale::select('id', 'delivery_status')->setEagerlyLoaded([])->find($id);
        //     if(strtolower($sale->delivery_status) !== 'in transit'){
        //         abort(422, 'Order is not in transit');
        //     }
        // }

        // if (Auth::user()->hasRole('Call center')) {
        //     if (!in_array($request->status, $callCenterStatus)) {
        //         abort(422, 'You are not authorized to perform this action');
        //     }
        // }

        // if (Auth::user()->hasRole('Operations')) {
        //     if (!in_array($request->status, $operationsStatus)) {
        //         abort(422, 'You are not authorized to perform this action');
        //     }
        // }

        // if (Auth::user()->hasRole('Finance')) {
        //     if (!in_array($request->status, $financeStatus)) {
        //         abort(422, 'You are not authorized to perform this action');
        //     }
        // }

        // if (Auth::user()->hasRole('Warehouse')) {
        //     if (!in_array($request->status, $warehouseStatus)) {
        //         abort(422, 'You are not authorized to perform this action');
        //     }
        // }

        // if (Auth::user()->hasRole('Follow UP')) {
        //     if (!in_array($request->status, $followingUpStatus)) {
        //         abort(422, 'You are not authorized to perform this action');
        //     }
        // }
        
        



        $status = new Status();

        // dd(Carbon::parse($request->created_at));



        $status->status_update($request, $id);
    }

    public function approve_delivery(Request $request, $id)
    {
        if (Auth::user()->hasRole('Super admin') || Auth::user()->hasRole('Finance')) {
            $data = $request->all();
            $data['delivery_status'] = 'Delivered';
            $this->status_update(new Request($data), $id);
        } else {
            abort(422, 'You are not authorized to perform this action');
        }
    }

    public function undispatch_multiple_status(Request $request)
    {
        foreach ($request->orders as $order) {
            // Check if $order is an array or an integer
            if (is_array($order)) {
                $this->undispatch_status(new Request($order), $order['id']);
            } else {
                // If $order is just the ID (integer)
                $this->undispatch_status($request, $order);
            }
        }
    }

    public function undispatch_status(Request $request, $id)
    {

        DB::beginTransaction();

        try {
            $sale = Sale::find($id);

            $rider_sale = new RiderSale();
            $sale_zones = new SaleZone();
            $status = $request->status;
            $sale->user_id = Auth::id();

            if ($status == 'Redispatch') {
                $rider_sale->create($id, $request->rider_id);

                if ($request->zone_to) {
                    $sale_zones->create($id, $request->zone_to, 1);
                }
                $sale->history_comment = 'Order redispatched';
                $sale->delivery_status = 'In Transit';
                $sale->save();
            } elseif ($status == 'Reschedule') {
                $tomorrow = Carbon::tomorrow();
                $today = Carbon::today();
                $deliveryDate = Carbon::parse($request->delivery_date);
                if ($deliveryDate->eq($today)) {
                    abort(422, "You can't reschedule for today!");
                }

                // if ($deliveryDate->eq($tomorrow) || $deliveryDate->eq($today)) {
                $sale->delivery_status = 'Reschedule';
                // } else {
                //     $sale->delivery_status = 'Scheduled';
                //     $sale->printed = false;
                // }
                $sale->delivery_date = $deliveryDate;
                $sale->history_comment = 'Order rescheduled';
                $sale->save();
                $rider_sale->undispatch($id);
            } else {
                $sale->status = 'Undispatched';
                $sale->delivery_status = 'Undispatched';
                $sale->history_comment = 'Order Undispatched';
                $sale->customer_notes = $status;
                $sale->save();
                // $rider_sale->undispatch($id);
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }














    public function dispatch_orders(Request $request)
    {
        $request->validate([
            'zone_from' => 'required',
            'zone_to' => 'required',
            'orders' => 'required|array'
        ]);

        // Start a transaction
        DB::beginTransaction();


        $rider_id = $request->rider_id;

        $orders = $request->orders;
        $zone_from = $request->zone_from;
        $zone_to = $request->zone_to;
        $courier = $request->courier;
        $ids =  collect($orders)->pluck('id');

        $user = Auth::user();



        $warehouseId = Warehouse::where('ou_id', $user->ou_id)->first()->id;

        BulkUpdateJob::dispatch($zone_from, $zone_to, $ids, $warehouseId, $user, 'Dispatched', $rider_id, $courier);
    }
}
