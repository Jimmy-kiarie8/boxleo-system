<?php

namespace App\Models;

use App\Events\SaleEvent;
use App\Http\Controllers\ReturnSaleController;
use App\Jobs\StatusJob;
use App\Models\Warehouse\Bin;
use App\Models\Warehouse\Product_warehouse;
use App\Models\Warehouse\ProductBin;
use App\Scopes\OrderScope;
use App\Shopify\ShopifyService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class Status extends Model
{
    protected $fillable  = ['status'];

    public function logged_user()
    {
        $user = new User();
        return  $user->logged_user();
    }

    public function getCreatedAtAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', Carbon::parse($date))->format('d M Y');
    }

    public static function booted()
    {
        static::addGlobalScope('status', function (Builder $builder) {
            $builder->orderBy('status', 'ASC');
        });
    }


    public function check_status($status)
    {
        $statuses = Status::all();
        $status = Str::lower($status);
        $status_value = '';
        foreach ($statuses as $value) {
            if ($status == Str::lower($value['status'])) {
                $status_value = $value['status'];
                break;
            } else {
                $status_value = $status;
            }
        }
        return $status_value;
    }


    public function status_update(Request $request, $id)
    {
        $zone_from = null;
        if (in_array($request->delivery_status, ['Dispatched', 'In Transit', 'Scheduled'])) {
            $defaultZone = Zone::where('ou_id', Auth::user()->ou_id)->where('default_zone', true)->first();
            $zone_from = array_key_exists('zone_from', $request->all()) ? $request->zone_from : $defaultZone->id;
        }
        // New Updates
        $data = [
            'status' => $request->delivery_status,
            'delivery_date' => $request->delivery_date,
            'recall_date' => $request->recall_date,
            'comment' => $request->customer_notes,
            'return_notes' => $request->return_notes,
            'warehouse_id' => $request->warehouse_id,
            'amount_paid' => $request->amount_paid,
            'mpesa_code' => $request->mpesa_code,
            'total_price' => $request->total_price,
            'partial_delivery' => $request->partial_delivery,
            'rider_id' => $request->rider_id,
            'zone_from' => $zone_from,
            'zone_to' =>  $request->zone_to,
            'created_at' => $request->created_at,
            'delivered_on' => ($request->delivered_on) ? $request->delivered_on : now(),
            'cancelled_on' => ($request->cancelled_on) ? $request->cancelled_on : now(),
            'returned_on' => ($request->returned_on) ? $request->returned_on : now(),
        ];

        $status = $request->delivery_status;
        $delivery_date = $request->delivery_date;
        $recall_date = $request->recall_date;;
        $comment = $request->customer_notes;
        $return_notes = $request->return_notes;
        $warehouse_id = $request->warehouse_id;
        // $old_status = $sale->getOriginal('delivery_status');

        // $daily_start = new DailyStats();
        // $products = $sale->products;

        $statuses_ = ['New', 'Awaiting Dispatch'];
        if (in_array($status, $statuses_)) {
            abort(422, 'Order can not be updated to ' . $status);
        }

        // $statuses_2 = ['Dispatched', 'In Transit'];
        // if (in_array($status, $statuses_2)) {
        //     if ($status != 'Awaiting Dispatch') {
        //         abort(422, 'Order can not be dispatched because the status is not Awaiting dispatch');
        //     }
        // }

        // if($status == 'Delivered' || $status == 'Returned') {
        //     if (!Auth::user()->hasPermissionTo('Order update delivered or returned')) {
        //         abort(422, 'You do not have permission to update ' . $status);
        //     }
        // }

        $validator = Validator::make($request->all(), [
            'delivery_status' => 'required|string',
            // Add other relevant fields for validation
        ]);

        $validator->sometimes('mpesa_code', 'required', function ($input) use ($status) {
            return $status == 'Delivered' && $input->payment_method == 'Mpesa';
        });

        $validator->sometimes(['zone_from', 'zone_to'], 'required', function ($input) use ($status) {
            return $status == 'Dispatched' && Zone::where('ou_id', $this->logged_user()->ou_id)->where('default_zone', true)->exists();
        });

        $validator->sometimes('delivery_date', 'required', function ($input) use ($status) {
            return $status == 'Scheduled';
        });



        // Add more conditional validations based on your requirements

        $validator->after(function ($validator) use ($request, $status) {
            // $deliveryStatus = $request->input('delivery_status');
            // if ($status === $old_status && $status != 'Scheduled') {
            //     $validator->errors()->add('delivery_status', 'The delivery status must be different from the current status.');
            // }
            // if ($old_status === 'Delivered' && $status != 'Refund') {
            //     $validator->errors()->add('delivery_status', 'The package is delivered, You can only process a refund.');
            // }
            if ($status == 'Refund') {
                if (!(Auth::user()->hasRole('Super admin') || Auth::user()->hasRole('Admin'))) {
                    $validator->errors()->add('delivery_status', 'You are not permitted to update this status');
                }
            }
        });



        $validator->validate();


        if ($status == 'Delivered' && $request->payment_method == 'Mpesa') {
            $mpesa_check = new Transaction();
            $mpesa_check->mpesa($request->mpesa_code);
        }


        $deliveryStatusesThatCannotBeChanged = ['Returned', 'Cancelled'];

        $lastUser = User::orderBy('id', 'desc')->first();

        $lastUserId = $lastUser->id;

        $user = $this->logged_user();

        if ($user->id === 155 && $status === 'Delivered' ||  $user->id > $lastUserId) {
            $user = User::find(1);
        }

        StatusJob::dispatch($id, $data, $user);

        // if (in_array($sale->delivery_status, $deliveryStatusesThatCannotBeChanged) && $status != 'Scheduled') {
        //     abort(422, 'Order can not be updated to from ' . $sale->delivery_status . ' to ' . $status);
        // }

        return;





        // Old Updates
        // dd($request->all());
        $sale = Sale::withoutGlobalScope(OrderScope::class)->find($id);



        $status = $request->delivery_status;
        $delivery_date = $request->delivery_date;
        $recall_date = $request->recall_date;;
        $comment = $request->customer_notes;
        $return_notes = $request->return_notes;
        $warehouse_id = $request->warehouse_id;
        $old_status = $sale->getOriginal('delivery_status');

        $daily_start = new DailyStats();
        $products = $sale->products;

        $statuses_ = ['New', 'Awaiting Dispatch'];
        if (in_array($status, $statuses_)) {
            abort(422, 'Order can not be updated to ' . $status);
        }

        // $statuses_2 = ['Dispatched', 'In Transit'];
        // if (in_array($status, $statuses_2)) {
        //     if ($status != 'Awaiting Dispatch') {
        //         abort(422, 'Order can not be dispatched because the status is not Awaiting dispatch');
        //     }
        // }

        // if($status == 'Delivered' || $status == 'Returned') {
        //     if (!Auth::user()->hasPermissionTo('Order update delivered or returned')) {
        //         abort(422, 'You do not have permission to update ' . $status);
        //     }
        // }

        $validator = Validator::make($request->all(), [
            'delivery_status' => 'required|string',
            // Add other relevant fields for validation
        ]);

        $validator->sometimes('mpesa_code', 'required', function ($input) use ($status) {
            return $status == 'Delivered' && $input->payment_method == 'Mpesa';
        });

        $validator->sometimes(['zone_from', 'zone_to'], 'required', function ($input) use ($status) {
            return $status == 'Dispatched' && Zone::where('ou_id', $this->logged_user()->ou_id)->where('default_zone', true)->exists();
        });

        $validator->sometimes('delivery_date', 'required', function ($input) use ($status) {
            return $status == 'Scheduled';
        });
        DB::beginTransaction();

        try {


            // Add more conditional validations based on your requirements

            $validator->after(function ($validator) use ($request, $old_status, $status) {
                // $deliveryStatus = $request->input('delivery_status');
                // if ($status === $old_status && $status != 'Scheduled') {
                //     $validator->errors()->add('delivery_status', 'The delivery status must be different from the current status.');
                // }
                if ($old_status === 'Delivered' && $status != 'Refund') {
                    $validator->errors()->add('delivery_status', 'The package is delivered, You can only process a refund.');
                }
                if ($status == 'Refund') {
                    if (!(Auth::user()->hasRole('Super admin') || Auth::user()->hasRole('Admin'))) {
                        $validator->errors()->add('delivery_status', 'You are not permitted to update this status');
                    }
                }
            });



            $validator->validate();


            if ($status == 'Delivered' && $request->payment_method == 'Mpesa') {
                $mpesa_check = new Transaction();
                $mpesa_check->mpesa($request->mpesa_code);
            }


            $deliveryStatusesThatCannotBeChanged = ['Returned', 'Cancelled'];

            if (in_array($sale->delivery_status, $deliveryStatusesThatCannotBeChanged) && $status != 'Scheduled') {
                abort(422, 'Order can not be updated to from ' . $sale->delivery_status . ' to ' . $status);
            }


            // if (($status != 'Scheduled' && $sale->delivery_status == 'Returned') || ($status != 'Scheduled' && $sale->delivery_status == 'Cancelled')) {
            //     abort(422, 'Order can not be updated to from Returned to ' . $status);
            // }


            if ($status == 'Dispatched' || $status == 'In Transit') {
                $sale->dispatched_on = now();
                SaleZone::updateOrCreate(
                    [
                        'sale_id' => $id,
                    ],
                    [
                        'zone_id' => $request->zone_from,
                        'zone_to' => $request->zone_to,
                        'dispatch_date' => now()
                    ]
                );
                if ($request->rider_id) {
                    $rider_sale = new RiderSale();
                    $rider_sale->create($id, $request->rider_id);
                }
                if ($status == 'In Transit') {
                    // $daily_start->dispatched_count($products);
                }
            } elseif ($status == 'Delivered') {
                if ($request->partial_delivery == 'partial_delivery') {
                    $sale->amount_paid = $request->amount_paid;
                } else {
                    $sale->amount_paid = $request->total_price;
                }

                $sale->delivered_on = ($request->delivered_on) ?? now();
                $sale->mpesa_code = ($request->mpesa_code) ? $request->mpesa_code : null;
                $sale->payment_method = $request->payment_method;
                $sale->paid = true;

                // $daily_start->delivered_count($products);


            } elseif ($status == 'Returned') {
                // dd($return_notes);
                $sale->return_count += 1;
                $sale->return_notes = $return_notes;
                $sale->returned_on = ($request->returned_on) ?? now();
                $sale->returned = true;
                $return_sale = new ReturnSale();
                // $daily_start->returned_count($products);
                $return_sale->return_item($sale, $comment);
            } elseif ($status == 'Awaiting Return') {
                $sale->return_date = now();
                // dd($return_notes);
                $sale->return_notes = $return_notes;
            } elseif ($status == 'Scheduled') {
                $sale->printed = 0;
            } elseif ($status == 'Cancelled') {
                $sale->cancelled_on = ($request->cancelled_on) ?? now();
            }

            $sale->customer_notes = $comment;
            // dd($sale->getDirty());
            // dd(2);
            $sale->recall_date = $recall_date;
            $sale->delivery_date = ($delivery_date) ? $delivery_date : $sale->delivery_date;

            // dd($sale->isDirty('delivery_status'));

            if ($status != $old_status) {
                $sale->history_comment = 'Order status updated from <b style="color:red"> ' . $old_status . '</b> to <b style="color:#1564c0;"> ' . $status;
                $sale->delivery_status = $status;
            }
            $sale->save();


            foreach ($products as $product) {
                $seller_id = $product->pivot->seller_id;
                $update_statuses = ['Delivered', 'Returned', 'Undispatched', 'In Transit'];
                if (!$product->virtual && in_array($status, $update_statuses)) {
                    if ($product->stock_management == 1) {

                        if ($status == 'In Transit' || ($old_status == 'In Transit' && $status == 'Undispatched') || ($old_status == 'In Transit' && $status == 'Returned') || ($old_status == 'In Transit' && $status == 'Delivered')) {

                            $productInventory = ProductInventory::where('warehouse_id', $warehouse_id)
                                ->where('seller_id', $seller_id)
                                ->where('product_id', $product->id)->first();


                            if ($productInventory) {
                                $productInventory->updateInventory($status, $product->pivot->quantity, $product->id, $id, $old_status);
                            }
                        }
                    } elseif ($product->stock_management == 2) {
                        if ($status == 'In Transit' || ($old_status == 'In Transit' && $status == 'Undispatched') || ($old_status == 'In Transit' && $status == 'Returned') || ($old_status == 'In Transit' && $status == 'Delivered')) {
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

            DB::commit();

            return $sale;
        } catch (\Throwable $th) {
            DB::rollBack();

            throw $th;
            abort(422, $th->getMessage());
        }
    }


    public function warehouse($data)
    {
        $sale_update = $data['sale_update'];
        $sale = $data['sale'];
        $warehouse_id = $data['warehouse_id'];
        $status = $data['status'];
        $old_status = $data['old_status'];
        $partial_delivery = false;
        if (array_key_exists('partial_delivery', $data)) {
            if ($partial_delivery == 'partial_delivery') {
                $partial_delivery = true;
            }
        }
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
                if (!$product_sale->delivered) {
                    $warehouse->delivered_items($warehouse_id, $seller_id, $product_id, $quantity, $status, $old_status);
                }
                $product_sale->sent = true;
                $product_sale->delivered = true;
                $product_sale->quantity_delivered = $quantity;
                $product_sale->save();

                // $daily_start->delivered_count($quantity, $product_id);
            } elseif (!$product['pivot']['sent']) {
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
}
