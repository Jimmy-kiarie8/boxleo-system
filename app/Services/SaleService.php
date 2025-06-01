<?php

namespace App\Services;

use App\Models\AppCustom;
use App\Models\AutoGenerate;
use App\Models\CustomView;
use App\Models\Mpesa;
use App\Models\Sale;
use App\Models\SaleZone;
use App\Models\Zone;
use App\Scopes\OrderScope;
use App\Scopes\ProductScope;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class SaleService
{

    public function sale_filter(Request $request)
    {


        $is_agent = false;
        $sales_ids = [];
        // if (Auth::check()) {
        //     if (Auth::user()->hasRole('Agent')) {
        //         $zones = Zone::where('manager', Auth::id())->pluck('id');
        //         $sales_ids = SaleZone::whereIn('zone_to', $zones)->pluck('sale_id');
        //         $is_agent = true;
        //     }
        // }



        // return $request->all();
        $ou_id = $request->ou_id;
        $client = $request->client;
        $delivery_status = $request->delivery_status;
        $created_at = $request->created_at;
        $delivery_date = $request->delivery_date;
        $delivered_on = $request->delivered_on;
        $returned_on = $request->returned_on;
        $recall_date = $request->recall_date;
        $dispatched_on = $request->dispatched_on;
        $status = (Str::lower($request->status) != 'all') ? $request->status : null;
        $agent_id = $request->agent_id;



        $sales = Sale::with(['client' => function ($q) {
            // $q->select('client.id', 'name', 'email', 'phone', 'alt_phone', 'address');
        }, 'agent' => function ($q) {
            $q->setEagerLoads([]);
        }, 'products'])->when(!empty($client), function ($q) use ($client) {
            return $q->whereIn('seller_id', $client);
        })->when(!empty($delivery_status), function ($q) use ($delivery_status) {
            return $q->whereIn('delivery_status', $delivery_status);
        })->when(!empty($ou_id), function ($q) use ($ou_id) {
            return $q->where('ou_id', $ou_id);
        })->when(!empty($created_at), function ($q)  use ($created_at) {
            if ($created_at[0] == $created_at[1]) {
                return $q->whereDate('created_at', $created_at);
            } else {
                return $q->whereBetween('created_at', $created_at);
            }
        })->when(($agent_id), function ($q)  use ($agent_id) {
            return $q->where('agent_id', $agent_id);
        })->when(($delivery_date), function ($q)  use ($delivery_date) {
            if ($delivery_date[0] == $delivery_date[1]) {
                return $q->whereDate('delivery_date', $delivery_date);
            } else {
                return $q->whereBetween('delivery_date', $delivery_date);
            }
        })->when(($delivered_on), function ($q)  use ($delivered_on) {
            if ($delivered_on[0] == $delivered_on[1]) {
                return $q->whereDate('delivered_on', $delivered_on);
            } else {
                return $q->whereBetween('delivered_on', $delivered_on);
            }
        })->when(($returned_on), function ($q)  use ($returned_on) {
            if ($returned_on[0] == $returned_on[1]) {
                return $q->whereDate('returned_on', $returned_on);
            } else {
                return $q->whereBetween('returned_on', $returned_on);
            }
        })->when(($dispatched_on), function ($q)  use ($dispatched_on) {
            if ($dispatched_on[0] == $dispatched_on[1]) {
                return $q->whereDate('dispatched_on', $dispatched_on);
            } else {
                return $q->whereBetween('dispatched_on', $dispatched_on);
            }
        })->when(($recall_date), function ($q)  use ($recall_date) {
            if ($recall_date[0] == $recall_date[1]) {
                return $q->whereDate('recall_date', $recall_date);
            } else {
                return $q->whereBetween('recall_date', $recall_date);
            }
        })->when($is_agent, function ($q)  use ($sales_ids) {
            return $q->whereIn('id', $sales_ids);
        })->orderBy('id', 'DESC');


        if ($status == 'Open') {
            $statuses = ['Delivered', 'Dispatched', 'Scheduled'];
            $sales = $sales->whereNotIn('delivery_status', $statuses)->whereDate('recall_date', Carbon::today());
        } elseif ($status == 'Pending') {
            $statuses = ['Delivered', 'Dispatched', 'Scheduled', 'Returned', 'Cancelled', 'In Transit', 'New', 'Awaiting Dispatch', 'Undispatched'];
            $sales = $sales->whereNotIn('delivery_status', $statuses);
        } elseif ($status == 'Out Of Stock') {
            $statuses = ['Out Of Stock', 'out_of_stock', 'out-of-stock'];
            $sales = $sales->whereIn('delivery_status', $statuses);
        } elseif ($status == 'Returned') {
            $sales = $sales->where('delivery_status', $status);
        } elseif ($status == 'Awaiting Return') {
            $sales = $sales->where('delivery_status', $status);
        } else {
            $sales = $sales->when($status, function ($q) use ($status) {
                return $q->where('status', $status);
            });
        }

        return $sales;
    }

    public function restrictions($sale, $status, $user)
    {
        // Assuming $user contains the authenticated user object
        $userRole = $user->role; // Retrieve the user's role

        // Assuming $order contains the order object that you want to update
        $currentStatus = $sale->status; // Retrieve the current status of the order

        if ($userRole == 'Admin') {
            // Admin can update any order status
            // Proceed with updating the order status
        } elseif ($userRole == 'Call center') {
            // Call center can only update order status to Scheduled, Cancelled, Pending
            if (in_array($currentStatus, ['Delivered', 'In Transit', 'Dispatched', 'Awaiting Dispatch'])) {
                // Restrict updating order status
                // Return error message or redirect back with error message
            } else {
                // Proceed with updating the order status
            }
        } elseif ($userRole == 'Operations' || $userRole == 'Warehouse') {
            // Operations and Warehouse can only update order status to Dispatched, In Transit
            if (in_array($currentStatus, ['Delivered', 'New'])) {
                // Restrict updating order status
                // Return error message or redirect back with error message
            } else {
                // Proceed with updating the order status
            }
        } elseif ($userRole == 'Agent') {
            // Define role-specific restrictions for Agent, if any
            // Example: Agent can only update specific order statuses
        } else {
            // Handle unrecognized roles, if any
        }
    }

    public function prepareSearchData($search)
    {
        return array_map('trim', preg_split("/[\r\n,]+/", $search, -1, PREG_SPLIT_NO_EMPTY));
    }

    public function buildSearchQuery($data_arr)
    {
        $query = Sale::with([
            'products' => function ($query) {
                $query->withoutGlobalScope(ProductScope::class)->setEagerLoads([]);
            },
            'client:id,name,phone,alt_phone,address,email,city',
            'pod',
            'agent:id,name'
        ]);

        if (count($data_arr) > 1) {
            $query->where(function ($query) use ($data_arr) {
                $query->whereIn('order_no', $data_arr)
                    ->orWhereHas('client', function ($q) use ($data_arr) {
                        $q->whereIn('name', $data_arr)
                            ->orWhereIn('phone', $data_arr)
                            ->orWhereIn('address', $data_arr)
                            ->orWhereIn('email', $data_arr);
                    });
            });
        } else {
            $search = $data_arr[0];
            // $query->where('order_no', 'LIKE', "%{$search}%");
            $query->where(function ($query) use ($search) {
                $query->where('order_no', 'LIKE', "%{$search}%")
                    ->orWhereHas('client', function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('phone', 'LIKE', "%{$search}%");
                        // ->orWhere('alt_phone', 'LIKE', "%{$search}%")
                        // ->orWhere('address', 'LIKE', "%{$search}%")
                        // ->orWhere('email', 'LIKE', "%{$search}%");
                    });
            });
        }

        if (Auth::check()) {
            $query->withoutGlobalScope(OrderScope::class)->where('ou_id', Auth::user()->ou_id);
        }

        return $query;
    }



    public function trans_sales($sales, $trans_type, $count)
    {
        $this->transform_sales($sales);

        $custom = CustomView::where('user_id', Auth::id())->where('app_view', false)->first();
        $app_view = CustomView::where('user_id', Auth::id())->where('app_view', true)->first();

        if ($custom && $trans_type == 'custom') {
            $merged = collect($custom->labels)->zip($custom->columns)->transform(function ($values) {
                return ['text' => $values[0], 'value' => $values[1]];
            });
        } elseif ($app_view && $trans_type == 'custom') {
            return (new AppCustom())->show($app_view->id);
        } else {
            // Default fields and labels...
            $labels = [
                "Created on",
                "Order No.",
                "Client Name",
                "Client Phone",
                "Altenative Phone",
                "Client Address",
                "Client city",
                "Pickup Phone",
                "Pickup Address",
                "Pickup city",
                "Product Name",
                // "Created By",
                "Delivery Status",
                "Status",
                "Delivery Date",
                "Recall On",
                // "Sub total",
                "Cod amount",
                "Charges",
                'Notes',
                'Return Reasons',
                'Printed',
                'POD',
                'Agent',
                'Upsell',
                'Weight',

            ];

            $fields = [
                "created_at",
                "order_no",
                "client_name",
                "client_phone",
                "alt_phone",
                "client_address",
                "client_city",
                "pickup_phone",
                "pickup_address",
                "pickup_city",
                "product_name",
                // "created_by",
                "delivery_status",
                "status",
                "delivery_date",
                "recall_date",
                // "sub_total",
                // "discount",
                "total_price",
                "shipcharges_sum_amount",
                'customer_notes',
                'return_notes',
                'printed',
                'pod',
                'agent_name',
                'upsell',
                'weight',
            ];


            if (Auth::user()) {
                $labels[] = 'Print Count';
                $fields[] = 'print_count';
                $labels[] = 'Shipping charges';
                $fields[] = 'shipping_charges';

                if (Auth::user()->hasRole(['Finance', 'Admin', 'Super admin'])) {
                    $additionalFields = ['Delivered On', 'Returned On', 'Invoice Value'];
                    $labels = array_merge($labels, $additionalFields);
                    $fields = array_merge($fields, ['delivered_on', 'returned_on', 'invoice_value']);
                }
            }

            $merged = collect($labels)->zip($fields)->transform(function ($values) {
                return ['text' => $values[0], 'value' => $values[1]];
            });
        }

        // Return or process $merged as needed

        $mpesa_connected =  Cache::remember('appview_' . Auth::id(), now()->addHours(10), function () {
            return Mpesa::exists();
        });
        // $mpesa_connected = Mpesa::exists();

        if ($mpesa_connected) {
            $myObject = new AutoGenerate;
            $myObject->checked = true;
            $myObject->value = "mpesa_code";
            $myObject->text = "M-pesa";
            $merged->push($myObject);
        }


        $myObject = new AutoGenerate;

        $myObject->checked = true;
        $myObject->value = "actions";
        $myObject->text = "Action";
        // $myObject->value = "printed";
        // $myObject->text = "Printed";

        $merged->push($myObject);



        // $merged[] = $myObject;
        // $more = ['actions'];
        // $labels = array_merge($labels, $more);
        // $fields = array_merge($fields, $more);


        return response()->json([
            'sales' => $sales,
            'columns' => $merged,
            'count' => $count
        ], 200);
    }

    public function transform_sales($sales)
    {
        return $sales->transform(function ($sale) {
            $total = 0;
            $product_name = '';
            $products_count = 0;

            foreach ($sale->products as $product) {
                $total += $product['pivot']['price'];
                $products_count += $product->pivot->quantity_tobe_delivered;
                $product_name .= "|{$product->product_name}: Qty {$products_count}";
            }

            $sale->agent_name = optional($sale->agent)->name;
            $sale->product_name = ltrim($product_name, '|');
            $sale->products_count = $products_count;
            $sale->sub_total = $sale->sub_total;
            $sale->total = $total;
            $sale->client_name = optional($sale->client)->name ?? 'anonymous';
            $sale->client_phone = optional($sale->client)->phone ?? '';
            $sale->alt_phone = optional($sale->client)->alt_phone;
            $sale->client_email = optional($sale->client)->email ?? '';
            $sale->client_address = optional($sale->client)->address ?? '';
            $sale->client_city = optional($sale->client)->city ?? '';

            return $sale;
        });
        return $sales;
    }




    public function prepareSearchData1($search)
    {
        $data_arr = preg_split("/[\r\n,]+/", $search, -1, PREG_SPLIT_NO_EMPTY);
        return array_map(function ($a) {
            return str_replace(' ', '', $a);
        }, $data_arr);
    }

    public function buildSearchQuery1($data_arr)
    {
        $query = Sale::with([
            'products' => function ($q) {
                $q->select('products.id', 'products.product_name', 'products.sku_no')->setEagerLoads([]);
            },
            'client',
            'pod',
            'agent' => function ($q) {
                $q->setEagerLoads([]);
            }
        ]);

        if (count($data_arr) > 1) {
            $query->where(function ($query) use ($data_arr) {
                $query->whereIn('order_no', $data_arr);
                $query->orWhereHas('client', function ($q) use ($data_arr) {
                    $q->where(function ($q) use ($data_arr) {
                        $q->whereIn('name', $data_arr)
                            ->orWhereIn('phone', $data_arr)
                            ->orWhereIn('address', $data_arr)
                            ->orWhereIn('email', $data_arr);
                    });
                });
            });
        } else {
            $search = $data_arr[0];
            $query->where(function ($query) use ($search) {
                $query->where('order_no', 'LIKE', "%{$search}%");
                $query->orWhereHas('client', function ($q) use ($search) {
                    $q->where(function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('phone', 'LIKE', "%{$search}%")
                            ->orWhere('alt_phone', 'LIKE', "%{$search}%")
                            ->orWhere('address', 'LIKE', "%{$search}%")
                            ->orWhere('email', 'LIKE', "%{$search}%");
                    });
                });
            });
        }

        if (Auth::check()) {
            $query->withoutGlobalScope(OrderScope::class);
        }

        return $query;
    }

    public function clearOrderSearchCache($orderNo)
    {
        $cacheKey = 'order_search:' . md5($orderNo);
        Cache::forget($cacheKey);
    }

    public function clearOrderCache($orderId)
    {
        $cacheKey = 'order_show:' . $orderId;
        Cache::forget($cacheKey);
    }


    public function clearOrderScanCache($orderNo)
    {
        Cache::forget('order_scan:' . $orderNo);
    }
}
