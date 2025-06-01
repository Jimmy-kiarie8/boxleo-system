<?php

namespace App\Models;

use App\Rider;
use App\Scopes\ProductScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Report extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'user_id', 'columns', 'order', 'labels', 'user_id', 'conditions'];

    public function setColumnsAttribute($value)
    {
        $this->attributes['columns'] = serialize($value);
    }

    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('D d M Y H:i');
    }
    public function setLabelsAttribute($value)
    {
        $this->attributes['labels'] = serialize($value);
    }

    public function setOrderAttribute($value)
    {
        $this->attributes['order'] = serialize($value);
    }

    public function setConditionsAttribute($value)
    {
        $this->attributes['conditions'] = serialize($value);
    }

    public function getConditionsAttribute($value)
    {
        return unserialize($value);
    }
    public function getOrderAttribute($value)
    {
        return unserialize($value);
    }

    public function getColumnsAttribute($value)
    {
        return unserialize($value);
    }

    public function getLabelsAttribute($value)
    {
        return unserialize($value);
    }


    public function custom_report($id)
    {
        $report = Report::find($id);

        // DB::enableQueryLog();
        $sale_item = Sale::with(['products']);
        $conditions = $report->conditions;
        // $columns = $report->columns;


        $fields = $report->columns;
        $labels = $report->labels;
        $items = new AutoGenerate;
        foreach ($fields as $key1 => $value1) {
            foreach ($labels as $key2 => $value2) {
                if ($key1 == $key2) {
                    $items->$value2 = $value1;
                }
            }
        }
        // dd($items);

        // dd(array_keys($items));
        $merged = collect($items);

        $table_columns = collect($labels)->zip($fields)->transform(function ($values) {
            // var_dump($values[1]);
            return [
                'text' => $values[0],
                'value' => $values[1]
            ];
        });

        // $merged = collect($labels)->zip($fields)->transform(function ($values) {
        //     return [
        //         $values[0] => $values[1]
        //     ];
        // });

        // return (array_values($merged));


        // $myObject = new AutoGenerate;

        // $myObject->Order no = '';


        // return $merged;

        $sale_item->where(function ($query) use ($conditions) {
            // $sale_item->select($columns)->where(function ($query) use ($conditions) {
            // $query = Sale::setEagerLoads([]);
            foreach ($conditions as $filter) {
                if ($filter['row']['Type'] == 'tinyint(1)') {
                    if ($filter['value'] == 'true') {

                        $filter['value'] = true;
                    } elseif ($filter['value'] == 'false') {
                        $filter['value'] = false;
                    }
                    // booleanValue
                    // dd(($filter['row']['Type']));
                }
                $column = Str::snake($filter['row']['label']);
                $operator = $filter['operator'];
                $row_value = $filter['value'];
                $op = '';
                // return $filter['condition'] == 'is';

                if ($filter['condition'] == 'is') {
                    // dd('dwdw');
                    $op = '=';
                } elseif ($filter['condition'] == 'is not') {
                    $op = '!=';
                } elseif ($filter['condition'] == 'is greater than') {
                    $op = '>';
                } elseif ($filter['condition'] == 'is greater than or equal to') {
                    $op = '>=';
                } elseif ($filter['condition'] == 'is less than') {
                    $op = '<';
                } elseif ($filter['condition'] == 'is less than or equal to') {
                    $op = '<=';
                } elseif ($filter['condition'] == 'contains') {
                    $op = 'LIKE';
                } elseif ($filter['condition'] == "doesn't contain") {
                    $op = 'NOT LIKE';
                } elseif ($filter['condition'] == 'is in') {
                    $op = 'whereIn';
                } elseif ($filter['condition'] == 'is not in') {
                    $op = 'whereNotIn';
                }

                if ($operator == 'OR') {
                    if ($op == 'LIKE' || $op == 'NOT LIKE') {
                        $query->orWhere($column, $op, "%{$row_value}%");
                    } elseif ($op == 'whereIn') {
                        $query->orWhereIn($column, [$row_value]);
                    } elseif ($op == 'whereNotIn') {
                        $query->orWhereNotIn($column, [$row_value]);
                    } else {
                        $query->orWhere($column, $op, $row_value);
                    }
                } else {
                    if ($op == 'LIKE' || $op == 'NOT LIKE') {
                        $query->where($column, $op, "%{$row_value}%");
                    } elseif ($op == 'whereIn') {
                        $query->whereIn($column, [$row_value]);
                    } elseif ($op == 'whereNotIn') {
                        $query->whereNotIn($column, [$row_value]);
                    } else {
                        $query->where($column, $op, $row_value);
                    }
                    // $query->where($column, $op, $row_value);
                }
            }
        });

        $sales = $sale_item->get();

        $sale_transform = new Sale;
        $sale_transform->transform_sales($sales, '');

        // dd(DB::getQueryLog()); // Show results of log
        return response()->json([
            'sales' => $sales,
            'columns' => $merged,
            'table_columns' => $table_columns
        ], 200);
    }
    public function report_transform($data)
    {
        $riders = Rider::where('ou_id', 1)->get();
        $zones = Zone::where('ou_id', 1)->get();
        $data->transform(function ($sale) use ($riders, $zones) {
            // $customer_notes = $sale->customer_notes;

            // if ($sale->delivery_status == 'Returned' &&!empty($sale->return_notes)) {
            //     $customer_notes = $sale->return_notes;
            // }

            $cod_charge = 0;
            $outbound_delivery = 0;
            $outbound_return = 0;
            $inbound_return = 0;
            $inbound_delivery = 0;
            $call_charge = 0;
            $packaging = 0;
            $label_printing = 0;


            $product_name = '';
            $total = 0;
            $quantity = 0;
            foreach ($sale->products as $product) {
                $total += $product->price;

                $product_name = $product_name . '|' . $product->product_name . ' - QTY:' . $product['pivot']['quantity'];

                $quantity += $product['pivot']['quantity'];
            }

            $shipping_charges = 0;
            foreach ($sale->services as $service) {
                $shipping_charges += $service['pivot']['amount'];

                if ($service['name'] == "Call center") {
                    $call_charge = $service['pivot']['amount'];
                } elseif ($service['name'] == "Inbound delivery") {
                    $inbound_delivery = $service['pivot']['amount'];
                } elseif ($service['name'] == "Cod") {
                    $cod_charge = $service['pivot']['amount'];
                } elseif ($service['name'] == "Outbound Returns") {
                    $outbound_return = $service['pivot']['amount'];
                } elseif ($service['name'] == "Inbound returns") {
                    $inbound_return = $service['pivot']['amount'];
                } elseif ($service['name'] == "Outbound Delivery") {
                    $outbound_delivery = $service['pivot']['amount'];
                } elseif ($service['name'] == "Label Print") {
                    $label_printing = $service['pivot']['amount'];
                } elseif ($service['name'] == "Packaging") {
                    $packaging = $service['pivot']['amount'];
                }
            }

            $sale->delivery_fee = $outbound_delivery + $outbound_return + $inbound_return + $inbound_delivery;
            $sale->fulfillment = $call_charge + $packaging + $label_printing;

            $sale->cod_charge = $cod_charge;
            $sale->outbound_delivery = $outbound_delivery;
            $sale->outbound_return = $outbound_return;
            $sale->inbound_return = $inbound_return;
            $sale->inbound_delivery = $inbound_delivery;
            $sale->call_charge = $call_charge;
            $sale->packaging = $packaging;
            $sale->label_printing = $label_printing;

            $sale->shipping_charges += $shipping_charges;
            // $sale->order_no = ($sale->seller) ? $sale->seller->order_prefix . $sale->order_no : $sale->order_no;

            $sale->quantity = $quantity;
            $sale->product_name = ltrim($product_name, '|');
            $sale->sub_total = $sale->sub_total;
            $sale->total = $sale->total_price;
            // $sale->customer_notes = $customer_notes;
            // $sale->total = ($sale->discount) ? $sale->sub_total - $sale->discount : $sale->sub_total;
            $sale->client_name = ($sale->client) ? $sale->client->name : 'anonymus';
            $sale->client_phone = ($sale->client) ? $sale->client->phone : '';
            $sale->client_email = ($sale->client) ? $sale->client->email : '';
            $sale->client_address = ($sale->client) ? $sale->client->address : '';
            $sale->agent_name = ($sale->agent) ? $sale->agent->name : '';

            if(!$sale->client) 
                    {
                        $sale->client = ['name' => '-', 'phone' => '', 'email' => '', 'address' => '', 'gender' =>'', 'country_name' => '', 'city' => '', 'latitude' => '', 'longitude' => ''];

           

            }

            if ($sale->ou_id == 1) {
                if ($sale->rider_id) {
                    $sale->rider_name = $riders->where('id', $sale->rider_id)->first()->name;
                }
                if ($sale->zone_id) {
                    $sale->zone_name = $zones->where('id', $sale->zone_id)->first()->name;
                }
            }

            // $sale->user_name = $sale->user->name;
            return $sale;
        });
        return $data;
    }

    public function table_cols()
    {

        $labels = [
            "Created on",
            "Order No.",
            "Client Name",
            "Client Phone",
            "Client Address",
            "Product Name",
            // "Created By",
            "Delivery Status",
            "Status",
            "Delivery Date",
            "Sub total",
            "Cod amount",
            "Charges",
            "M-pesa Code",
            'Printed'
        ];

        $fields = [
            "created_at",
            "order_no",
            "client_name",
            "client_phone",
            "client_address",
            "product_name",
            // "created_by",
            "delivery_status",
            "status",
            "delivery_date",
            "sub_total",
            // "discount",
            "total_price",
            "shipping_charges",
            "mpesa_code",
            'printed'
        ];
        return $merged = collect($labels)->zip($fields)->transform(function ($values) {
            // var_dump($values[1]);
            return [
                'text' => $values[0],
                'value' => $values[1]
            ];
        });
    }

    public function remit($start_date, $end_date, $vendor)
    {
        // return $request->all();
        $charges = Shipcharge::where('remmited', false)->whereBetween('created_at', [$start_date, $end_date])->get();
        $sale_id = [];
        foreach ($charges as $key => $charge) {
            $sale_id[] = $charge->sale_id;
        }

        $sales = Sale::setEagerLoads([])->with(['services', 'seller'])->whereIn('id', $sale_id);
        if ($vendor) {
            $sales = $sales->where('seller_id', $vendor);
        }

        // $sales = $sales->where('delivery_status', 'Delivered')->orWhere('delivery_status', 'Returned');
        $sales = $sales->get();
        $sales = $this->report_transform($sales);
        return $sales;
    }


    public function reports(Request $request)
    {
        $custom_report = new Report();
        $start_date = Carbon::parse($request->start_date);
        $end_date = Carbon::parse($request->end_date);

        if ($request->report == 'Custom') {
            $data = $request->validate([
                'custom' => 'required'
            ]);

            return $custom_report->custom_report($request->custom, ['start_date' => $start_date, 'end_date' => $end_date], $request->client);
        }

        $report = $request->report;
        $sales = Sale::setEagerLoads([])->with(['products' => function ($query) {
            $query->setEagerLoads([]);
        }, 'client' => function ($query) {
            $query->setEagerLoads([]);
        }, 'seller' => function ($query) {
            $query->setEagerLoads([]);
        }, 'services' => function ($query) {
            $query->setEagerLoads([]);
        }]);
        if ($report == 'Status') {
            if (!empty($request->delivery_status)) {
                $sales = $sales->whereIn('delivery_status', $request->delivery_status);
            }
        } elseif ($report == 'Delivered') {
            $sales = $sales->where('delivery_status', 'Delivered');
            if ($request->delivery_start_date && $request->delivery_end_date) {
                $delivery_start_date = Carbon::parse($request->delivery_start_date);
                $delivery_end_date = Carbon::parse($request->delivery_end_date);
                $sales = $sales->whereBetween('delivered_on', [$delivery_start_date, $delivery_end_date]);
            }
        } elseif ($report == 'Returned') {
            $return_start_date = Carbon::parse($request->return_start_date);
            $return_end_date = Carbon::parse($request->return_end_date);
            if ($return_start_date && $return_end_date) {
                $sales = $sales->where('delivery_status', 'Returned')->whereBetween('returned_on', [$return_start_date, $return_end_date]);
            }
        } elseif ($report == 'Refused') {
            $return_start_date = Carbon::parse($request->return_start_date);
            $return_end_date = Carbon::parse($request->return_end_date);
            if ($return_start_date && $return_end_date) {
                $sales = $sales->where('delivery_status', 'Refused')->whereBetween('returned_on', [$return_start_date, $return_end_date]);
            }
        } elseif ($report == 'Dispatched') {
            $return_start_date = Carbon::parse($request->return_start_date);
            $return_end_date = Carbon::parse($request->return_end_date);
            if ($return_start_date && $return_end_date) {
                $sales = $sales->where('delivery_status', 'Dispatched')->whereBetween('dispatched_on', [$start_date, $end_date]);
            }
        } elseif ($report == 'Zone') {
            $date_arr = [Carbon::parse($request->start_date), Carbon::parse($request->end_date)];
            if ($request->rider_id) {
                $rider_sale = RiderSale::whereBetween('created_at', $date_arr)->where('rider_id', $request->rider_id)->get('sale_id');

                $rider_ids = [];
                foreach ($rider_sale as $key => $value) {
                    $rider_ids[] = $value->sale_id;
                }

                $sales = $sales->whereIn('id', $rider_ids);
            } elseif (!empty($request->zone_to)) {
                $zone_sale = SaleZone::whereBetween('created_at', $date_arr)->whereIn('zone_to', $request->zone_to)->get('sale_id');

                $zone_ids = [];
                foreach ($zone_sale as $key => $value) {
                    $zone_ids[] = $value->sale_id;
                }

                $sales = $sales->whereIn('id', $zone_ids);
            }
        } elseif ($report == 'Agents') {
            $sales = $sales->where('assigned_to', $request->user_name);
        }


        if ($request->finance) {
            $sales = $sales->where('remmited', 0);
        }
        if (!empty($request->client)) {
            $sales = $sales->whereIn('seller_id', $request->client);
        }
        if ($start_date && $end_date && $report != 'Zones') {
            $sales = $sales->whereBetween('created_at', [$start_date, $end_date]);
        }

        if ($report == 'Remittance') {
            $sales = $sales->where('delivery_status', 'Delivered')->orWhere('delivery_status', 'Returned');
            $sales_sum = $sales->sum('total_price');
        }

        $sales = $sales->get();
        $sales = $custom_report->report_transform($sales);


        return response()->json([
            'sales' => $sales,
        ], 200);
    }

    public function repor_filter(Request $request)
    {
        $request->validate([
            'report' => 'required'
        ]);
        $ou_id = $request->ou_id;
        $client = $request->client;
        $delivery_status = $request->delivery_status;

        $created_at = $request->created_at ? [Carbon::parse($request->created_at[0])->startOfDay(), Carbon::parse($request->created_at[1])->endOfDay()] : null;
        $delivery_date = $request->delivery_date ? [Carbon::parse($request->delivery_date[0])->startOfDay(), Carbon::parse($request->delivery_date[1])->endOfDay()] : null;
        $delivered_on = $request->delivered_on ? [Carbon::parse($request->delivered_on[0])->startOfDay(), Carbon::parse($request->delivered_on[1])->endOfDay()] : null;
        $return_date = $request->return_date ? [Carbon::parse($request->return_date[0])->startOfDay(), Carbon::parse($request->return_date[1])->endOfDay()] : null;
        $returned_on = $request->returned_on ? [Carbon::parse($request->returned_on[0])->startOfDay(), Carbon::parse($request->returned_on[1])->endOfDay()] : null;
        $status_date = $request->status_date ? [Carbon::parse($request->status_date[0])->startOfDay(), Carbon::parse($request->status_date[1])->endOfDay()] : null;
        // $returned_on = $request->returned_on ? Carbon::parse($request->returned_on)->startOfDay() : null;

        $dispatched_on = $request->dispatched_on;
        $product_id = $request->product;
        $status = (Str::lower($request->status) != 'all') ? $request->status : null;

        $custom_report = new Report();

        if ($request->report == 'Custom') {
            $data = $request->validate([
                'custom' => 'required'
            ]);

            return $custom_report->custom_report($request->custom, ['start_date' => $created_at[0], 'end_date' => $created_at[1]], $request->client);
        }

        $report = $request->report;

        if ($report == 'Product') {

            $sale_ids = ProductSale::whereIn('product_id', $product_id)->when(!empty($created_at), function ($q)  use ($created_at) {
                if ($created_at[0] == $created_at[1]) {
                    return $q->whereDate('created_at', $created_at);
                } else {
                    return $q->whereBetween('created_at', $created_at);
                }
            })->pluck('sale_id');




            $sales = Sale::setEagerLoads([])->whereIn('id', $sale_ids)->when(!empty($client), function ($q) use ($client) {
                return $q->whereIn('seller_id', $client);
            })->when(!empty($created_at), function ($q)  use ($created_at) {
                if ($created_at[0] == $created_at[1]) {
                    return $q->whereDate('created_at', $created_at);
                } else {
                    return $q->whereBetween('created_at', $created_at);
                }
            });
        } elseif ($report == 'Remittance') {
            $statuses = ['Delivered', 'Returned', 'Cancelled'];
            $salesQuery = null;

            foreach ($statuses as $key => $status_) {
                $query = Sale::setEagerLoads([])->select(
                    "id",
                    "client_id",
                    "agent_id",
                    "total_price",
                    "scale",
                    "amount_paid",
                    "sub_total",
                    "order_no",
                    "customer_notes",
                    "return_notes",
                    "shipping_charges",
                    "delivery_date",
                    "status",
                    "delivery_status",
                    "warehouse_id",
                    "seller_id",
                    "mpesa_code",
                    "cancel_notes",
                    "dispatched_on",
                    "return_date",
                    "delivered_on",
                    "returned_on",
                    "cancelled_on",
                    "ou_id",
                    "user_id",
                    "weight",
                    "schedule_date",
                    "created_at",
                    "order_date",
                    "updated_at"
                )->with(['products' => function ($q) {
                    $q->setEagerLoads([])->withoutGlobalScope(ProductScope::class)->select('products.id', 'products.product_name');
                }, 'client' => function ($q) {
                    $q->setEagerLoads([])->select('id', 'name', 'phone', 'address', 'city');
                }, 'seller' => function ($q) {
                    $q->setEagerLoads([])->select('id', 'name');
                }, 'services' => function ($q) {
                    $q->setEagerLoads([])->select('services.id', 'services.name');
                }, 'agent' => function ($q) {
                    $q->setEagerLoads([])->select('id', 'name');
                }])
                    ->when(!empty($client), function ($q) use ($client) {
                        return $q->whereIn('seller_id', $client);
                    })
                    ->when($ou_id, function ($q) use ($ou_id) {
                        return $q->where('ou_id', $ou_id);
                    })->withSum('shipcharges', 'amount');

                // if (!empty($created_at)) {
                //     if ($status_ == 'Delivered') {
                //         $query->whereBetween('delivered_on', $created_at);
                //     } elseif ($status_ == 'Returned') {
                //         $query->whereBetween('returned_on', $created_at);
                //     } elseif ($status_ == 'Cancelled') {
                //         $query->whereBetween('cancelled_on', $created_at);
                //     }
                // }
                if (!empty($created_at)) {
                    if ($status_ == 'Delivered') {
                        $query->where(function ($q) use ($created_at) {
                            $q->whereDate('delivered_on', '>=', $created_at[0])
                                ->whereDate('delivered_on', '<=', $created_at[1]);
                        });
                    } elseif ($status_ == 'Returned') {
                        $query->where(function ($q) use ($created_at) {
                            $q->whereDate('returned_on', '>=', $created_at[0])
                                ->whereDate('returned_on', '<=', $created_at[1]);
                        });
                    } elseif ($status_ == 'Cancelled') {
                        $query->where(function ($q) use ($created_at) {
                            $q->whereDate('cancelled_on', '>=', $created_at[0])
                                ->whereDate('cancelled_on', '<=', $created_at[1]);
                        });
                    }

                    $query->where('delivery_status', $status_);
                } else {
                    $query->where('delivery_status', $status_);
                }


                if ($key === 0) {
                    $salesQuery = $query;
                } else {
                    $salesQuery = $salesQuery->union($query);
                }
            }

            if ($salesQuery !== null) {
                $sales = $salesQuery;
            }
        } else {
            $sale_ids = [];
            if ($product_id) {
                $sale_ids = ProductSale::whereIn('product_id', $product_id)->when(!empty($created_at), function ($q)  use ($created_at) {
                    if ($created_at[0] == $created_at[1]) {
                        return $q->whereDate('created_at', $created_at);
                    } else {
                        return $q->whereBetween('created_at', $created_at);
                    }
                })->pluck('sale_id');
            }

            $sales = Sale::setEagerLoads([])->with(['products' => function ($query) {
                $query->withoutGlobalScope(ProductScope::class)->setEagerLoads([]);
            }, 'client' => function ($query) {
                $query->setEagerLoads([]);
            }, 'seller' => function ($query) {
                $query->setEagerLoads([]);
            }, 'services' => function ($query) {
                $query->setEagerLoads([]);
            }, 'agent' => function ($query) {
                $query->setEagerLoads([]);
            }])->when(!empty($client), function ($q) use ($client) {
                return $q->whereIn('seller_id', $client);
            })->when(!empty($delivery_status), function ($q) use ($delivery_status) {
                return $q->whereIn('delivery_status', $delivery_status);
            })->when($ou_id, function ($q) use ($ou_id) {
                return $q->where('ou_id', $ou_id);
            })
                ->when((!empty($created_at) && $report != 'awaiting_returned'), function ($q)  use ($created_at) {
                    if ($created_at[0] == $created_at[1]) {
                        return $q->whereDate('created_at', $created_at);
                    } else {
                        return $q->whereBetween('created_at', $created_at);
                    }
                })
                ->when(($report == 'Delivered' && !empty($delivered_on)), function ($q)  use ($delivered_on) {
                    if ($delivered_on[0] == $delivered_on[1]) {
                        return $q->where('delivery_status', 'Delivered')->whereDate('delivered_on', $delivered_on[0]);
                    } else {
                        return $q->where('delivery_status', 'Delivered')->whereBetween('delivered_on', $delivered_on);
                    }
                })->when(($report == 'Returned' && !empty($returned_on)), function ($q)  use ($returned_on) {
                    if ($returned_on[0] == $returned_on[1]) {
                        return $q->where('delivery_status', 'Returned')->whereDate('returned_on', $returned_on[0]);
                    } else {
                        return $q->where('delivery_status', 'Returned')->whereBetween('returned_on', $returned_on);
                    }
                })->when(($report == 'awaiting_returned' && !empty($return_date)), function ($q)  use ($return_date) {
                    if ($return_date[0] == $return_date[1]) {
                        return $q->where('delivery_status', 'Awaiting Return')->whereDate('return_date', $return_date[0]);
                    } else {
                        return $q->where('delivery_status', 'Awaiting Return')->whereBetween('return_date', $return_date);
                    }
                })->when(($report == 'Dispatched' && !empty($dispatched_on)), function ($q)  use ($dispatched_on) {
                    $statuses = ['Dispatched', 'In Transit'];
                    if ($dispatched_on[0] == $dispatched_on[1]) {
                        return $q->whereDate('dispatched_on', $dispatched_on[0]);
                    } else {
                        return $q->whereBetween('dispatched_on', $dispatched_on);
                    }
                })->when((!empty($status_date)), function ($q)  use ($status_date) {
                    if ($status_date[0] == $status_date[1]) {
                        return $q->whereDate('updated_at', $status_date[0]);
                    } else {
                        return $q->whereBetween('updated_at', $status_date);
                    }
                })->when(($report == 'Remittance'), function ($q)  use ($created_at) {
                    $statuses = ['Delivered', 'Returned', 'Cancelled'];
                    if ($created_at[0] == $created_at[1]) {
                        return $q->WhereIn('delivery_status', $statuses)->whereDate('updated_at', $created_at[0])->orderBy('delivery_status');
                    } else {
                        return $q->whereIn('delivery_status', $statuses)->whereBetween('updated_at', $created_at)->orderBy('delivery_status');
                    }
                })->when((!empty($product_id)), function ($q)  use ($sale_ids) {
                    return $q->whereIn('id', $sale_ids);
                });
        }

        if ($report == 'Dispatched') {
            $ids = [];
            if ($request->rider_id) {
                $ids = RiderSale::whereBetween('created_at', $dispatched_on)->where('rider_id', $request->rider_id)->pluck('sale_id');
            }
            $sales = $sales->whereIn('id', $ids);
        }
        $ids = [];
        if ($report == 'Zone' || $report == 'awaiting_returned') {
            if ($request->rider_id) {
                $ids = RiderSale::whereBetween('created_at', $created_at)->where('rider_id', $request->rider_id)->pluck('sale_id');
            } elseif (!empty($request->zone_to)) {
                // $created_at = ($created_at) ? $created_at : [];
                $ids = SaleZone::whereBetween('created_at', $created_at)->whereIn('zone_to', $request->zone_to)->pluck('sale_id');
            }
            $sales = $sales->whereIn('id', $ids);
        } elseif ($report == 'Agents') {
            $sales = $sales->where('agent_id', $request->agent_id);
        }


        if ($request->finance) {
            $sales = $sales->where('remmited', 0);
        }
        // if ($report == 'Remittance') {
        //     $sales = $sales->where('delivery_status', 'Delivered')->orWhere('delivery_status', 'Returned');
        // }


        return $sales;
    }



    public function data_count(Request $request)
    {

        $ou_id = $request->ou_id;
        $client = $request->client;
        $delivery_status = $request->delivery_status;
        $created_at = $request->created_at;
        $delivery_date = $request->delivery_date;
        $delivered_on = $request->delivered_on;
        $returned_on = $request->returned_on;
        $dispatched_on = $request->dispatched_on;
        $product_id = $request->product;
        $status = (Str::lower($request->status) != 'all') ? $request->status : null;
        $report = $request->report;

        if ($report == 'Product') {

            $sale_ids = ProductSale::whereIn('product_id', $product_id)->when(!empty($created_at), function ($q)  use ($created_at) {
                if ($created_at[0] == $created_at[1]) {
                    return $q->whereDate('created_at', $created_at);
                } else {
                    return $q->whereBetween('created_at', $created_at);
                }
            })->pluck('sale_id');




            return $sales = Sale::setEagerLoads([])->whereIn('id', $sale_ids)->when(!empty($client), function ($q) use ($client) {
                return $q->whereIn('seller_id', $client);
            })->when(!empty($created_at), function ($q)  use ($created_at) {
                if ($created_at[0] == $created_at[1]) {
                    return $q->whereDate('created_at', $created_at);
                } else {
                    return $q->whereBetween('created_at', $created_at);
                }
            });
        } else {
            $sales =  $countsQuery = Sale::query()
                ->when(!empty($client), function ($q) use ($client) {
                    return $q->whereIn('seller_id', $client);
                })
                ->when(!empty($delivery_status), function ($q) use ($delivery_status) {
                    return $q->whereIn('delivery_status', $delivery_status);
                })
                ->when(!empty($ou_id), function ($q) use ($ou_id) {
                    return $q->where('ou_id', $ou_id);
                })
                ->when(!empty($created_at), function ($q) use ($created_at) {
                    if ($created_at[0] == $created_at[1]) {
                        return $q->whereDate('created_at', $created_at);
                    } else {
                        return $q->whereBetween('created_at', $created_at);
                    }
                })
                ->when(($report == 'Delivered' && !empty($delivered_on)), function ($q) use ($delivered_on) {
                    if ($delivered_on[0] == $delivered_on[1]) {
                        return $q->where('delivery_status', 'Delivered')->whereDate('delivered_on', $delivered_on);
                    } else {
                        return $q->where('delivery_status', 'Delivered')->whereBetween('delivered_on', $delivered_on);
                    }
                })
                ->when(($report == 'Returned' && !empty($returned_on)), function ($q) use ($returned_on) {
                    if ($returned_on[0] == $returned_on[1]) {
                        return $q->where('delivery_status', 'Returned')->whereDate('returned_on', $returned_on);
                    } else {
                        return $q->where('delivery_status', 'Returned')->whereBetween('returned_on', $returned_on);
                    }
                })
                ->when(($report == 'Dispatched' && !empty($dispatched_on)), function ($q) use ($dispatched_on) {
                    $statuses = ['Dispatched', 'In Transit'];
                    if ($dispatched_on[0] == $dispatched_on[1]) {
                        return $q->whereIn('delivery_status', $statuses)->whereDate('dispatched_on', $dispatched_on);
                    } else {
                        return $q->whereIn('delivery_status', $statuses)->whereBetween('dispatched_on', $dispatched_on);
                    }
                });


            if ($report == 'Zone') {
                if ($request->rider_id) {
                    $ids = RiderSale::whereBetween('created_at', $created_at)->where('rider_id', $request->rider_id)->pluck('sale_id');
                } elseif (!empty($request->zone_to)) {
                    $ids = SaleZone::whereBetween('created_at', $created_at)->whereIn('zone_to', $request->zone_to)->pluck('sale_id');
                }
                $sales = $sales->whereIn('id', $ids);
            }
            return $sales;
        }
    }




    public function transform_sales1($sales)
    {
        $sales->transform(function ($sale) {
            $rider_name = '';
            $zone_name = '';

            // Get latest rider assignment
            $rider_sale = RiderSale::where('sale_id', $sale->id)->latest()->first();
            if ($rider_sale) {
                $rider = Rider::find($rider_sale->rider_id);
                if ($rider) {
                    $rider_name = $rider->name;
                }
            }

            // Get zone information
            $sale_zone = SaleZone::where('sale_id', $sale->id)->latest()->first();
            if ($sale_zone) {
                $zone = Zone::find($sale_zone->zone_to);
                if ($zone) {
                    $zone_name = $zone->name;
                }
            }

            $total = 0;
            $product_name = '';
            $products_count = 0;
            foreach ($sale->products as $product) {
                $total += $product['pivot']['price'];
                $products_count += $product->pivot->quantity_tobe_delivered;
                $product_name = $product_name . '|' . $product->product_name . ': Qty ' . $products_count;
            }

            $sale->agent_name = ($sale->agent) ? $sale->agent->name : '';
            $sale->rider_name = $rider_name;
            $sale->zone_name = $zone_name;

            $sale->product_name = ltrim($product_name, '|');
            $sale->products_count = $products_count;
            $sale->sub_total = $sale->sub_total;
            $sale->total = $total;
            $sale->client_name = ($sale->client) ? $sale->client->name : 'anonymus';
            $sale->client_phone = ($sale->client) ? $sale->client->phone : '';
            $sale->alt_phone = ($sale->client) ? $sale->client->alt_phone : null;
            $sale->client_email = ($sale->client) ? $sale->client->email : '';
            $sale->client_address = ($sale->client) ? $sale->client->address : '';
            $sale->client_city = ($sale->client) ? $sale->client->city : '';
            return $sale;
        });
        return $sales;
    }
}
