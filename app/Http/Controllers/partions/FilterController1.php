<?php

namespace App\Http\Controllers;

use App\Mobile\Status;
use App\Models\Call;
use App\Models\Client;
use App\Models\RiderSale;
use App\Models\Sale;
use App\Models\SaleZone;
use App\Models\User;
use App\Models\Zone;
use App\Scopes\OrderScope;
use App\Scopes\ProductScope;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use SplFileInfo;

class FilterController1 extends Controller
{
    public function sale_filter(Request $request)
    {
        $is_agent = false;
        $sales_ids = [];
        if (Auth::check()) {
            if (Auth::user()->hasRole('Agent')) {
                $zones = Zone::where('manager', Auth::id())->pluck('id');
                $sales_ids = SaleZone::whereIn('zone_to', $zones)->pluck('sale_id');
                $is_agent = true;
            }
        }

        $currentDate = Carbon::today()->endOfDay();

        // Check if we're in the first half (January to June)
        if ($currentDate->month <= 6) {
            $startOfHalfYear = Carbon::create($currentDate->year, 1, 1); // January 1st
        } else {
            $startOfHalfYear = Carbon::create($currentDate->year, 7, 1); // July 1st
        }

        // Always start with created_at filter to leverage partitioning
        $created_at = $request->created_at ?? [$startOfHalfYear, $currentDate];
        $app_custom_id = $request->app_custom_id;
        $ou_id = $request->ou_id;
        $client = $request->client;
        $delivery_status = $request->delivery_status;
        $delivery_date = $request->delivery_date;
        $delivered_on = $request->delivered_on;
        $returned_on = $request->returned_on;
        $recall_date = $request->recall_date;
        $dispatched_on = $request->dispatched_on;
        $status = (Str::lower($request->status) != 'all') ? $request->status : null;
        $agent_id = $request->agent_id;

        // Start with created_at filter to leverage partitioning
        $sales = Sale::with([
            'client',
            'agent' => function ($q) {
                $q->setEagerLoads([]);
            },
            'products' => function ($query) {
                $query->withoutGlobalScope(ProductScope::class)->setEagerLoads([]);
            }
        ]);

        // Apply created_at filter first for partition pruning
        if (!empty($created_at)) {
            if ($created_at[0] == $created_at[1]) {
                $sales = $sales->whereDate('created_at', $created_at[0]);
            } else {
                $sales = $sales->whereBetween('created_at', $created_at);
            }
        }

        // Apply remaining filters
        $sales = $sales->when(!empty($client), function ($q) use ($client) {
            return $q->whereIn('seller_id', $client);
        })->when(!empty($delivery_status), function ($q) use ($delivery_status) {
            return $q->whereIn('delivery_status', $delivery_status);
        })->when(!empty($ou_id), function ($q) use ($ou_id) {
            return $q->where('ou_id', $ou_id);
        })->when(($agent_id), function ($q) use ($agent_id) {
            return $q->where('agent_id', $agent_id);
        })->when(($delivery_date), function ($q) use ($delivery_date) {
            if ($delivery_date[0] == $delivery_date[1]) {
                return $q->whereDate('delivery_date', $delivery_date[0]);
            } else {
                return $q->whereBetween('delivery_date', $delivery_date);
            }
        })->when(($delivered_on), function ($q) use ($delivered_on) {
            if ($delivered_on[0] == $delivered_on[1]) {
                return $q->whereDate('delivered_on', $delivered_on[0]);
            } else {
                return $q->whereBetween('delivered_on', $delivered_on);
            }
        })->when(($returned_on), function ($q) use ($returned_on) {
            if ($returned_on[0] == $returned_on[1]) {
                return $q->whereDate('returned_on', $returned_on[0]);
            } else {
                return $q->whereBetween('returned_on', $returned_on);
            }
        })->when(($dispatched_on), function ($q) use ($dispatched_on) {
            if ($dispatched_on[0] == $dispatched_on[1]) {
                return $q->whereDate('dispatched_on', $dispatched_on[0]);
            } else {
                return $q->whereBetween('dispatched_on', $dispatched_on);
            }
        })->when(($recall_date), function ($q) use ($recall_date) {
            if ($recall_date[0] == $recall_date[1]) {
                return $q->whereDate('recall_date', $recall_date[0]);
            } else {
                return $q->whereBetween('recall_date', $recall_date);
            }
        })->when($is_agent, function ($q) use ($sales_ids) {
            return $q->whereIn('id', $sales_ids);
        });

        // Add index hint for partition scanning
        $sales = $sales->from(DB::raw('`sales` FORCE INDEX (PRIMARY)'));

        // Apply status filters and pagination
        if ($status == 'Open') {
            $statuses = ['Delivered', 'Dispatched', 'Scheduled'];
            $sales = $sales->whereNotIn('delivery_status', $statuses)
                          ->whereDate('recall_date', Carbon::today())
                          ->orderBy('id', 'DESC')
                          ->paginate(200);
        } elseif ($status == 'Pending') {
            $statuses = ['Delivered', 'Dispatched', 'Scheduled', 'Returned', 'Cancelled', 'In Transit', 'New', 'Awaiting Dispatch', 'Undispatched'];
            $sales = $sales->whereNotIn('delivery_status', $statuses)
                          ->orderBy('id', 'DESC')
                          ->paginate(200);
        } elseif ($status == 'Out Of Stock') {
            $statuses = ['Out Of Stock', 'out_of_stock', 'out-of-stock'];
            $sales = $sales->whereIn('delivery_status', $statuses)
                          ->orderBy('id', 'DESC')
                          ->paginate(200);
        } elseif ($status == 'Returned') {
            $sales = $sales->where('delivery_status', $status)
                          ->orderBy('id', 'DESC')
                          ->paginate(200);
        } elseif ($status == 'Awaiting Return') {
            $sales = $sales->where('delivery_status', $status)
                          ->orderBy('id', 'DESC')
                          ->paginate(200);
        } else {
            $sales = $sales->when($status, function ($q) use ($status) {
                return $q->where('status', $status);
            })
            ->orderBy('id', 'DESC')
            ->paginate(100);
        }

        $sale_trans = new Sale;
        $response = $sale_trans->trans_sales($sales, 'custom', '');
        return $response;
    }

    public function status_counts($sales)
    {
        $statuses = ['New', 'Inprogress', 'Confirmed', 'Shipped', 'Closed'];
        $data = [];

        return $sales->withCount('status', function ($query) use ($statuses) {
            $query->whereIn('status', $statuses);
        })->get();
    }


    public function mobile_filter(Request $request)
    {
        // dd($request->all());
        $request->validate([
            // 'zone_from' => 'required',
            // 'zone_to' => 'required',
            // 'rider_id' => 'required',
            // 'start_date' => 'required',
            // 'end_date' => 'required'
        ]);

        $zone_ids = [];
        $rider_ids = [];
        if ($request->rider_id) {
            $date_arr = [Carbon::parse($request->start_date), Carbon::parse($request->end_date)];
            $rider_sale = RiderSale::whereBetween('created_at', $date_arr)->where('rider_id', $request->rider_id)->get('sale_id');

            foreach ($rider_sale as $key => $value) {
                $rider_ids[] = $value->sale_id;
            }
        } elseif ($request->zone_to) {
            $zone_sale = SaleZone::where('zone_to', $request->zone_to)->get('sale_id');

            foreach ($zone_sale as $key => $value) {
                $zone_ids[] = $value->sale_id;
            }
        }
        // dd($rider_ids);
        $statuses = ['pending approval', 'Return pending'];
        $sale = Sale::setEagerLoads([])->with(['products' => function ($query) use ($statuses) {
            $query->select('product_name')->setEagerLoads([]);
        }, 'client', 'pod'])->whereIn('delivery_status', $statuses)->orderBy('updated_at', 'DESC')->when($request->status, function ($q) use ($request) {
            return $q->where('delivery_status', $request->status);
        })->when($request->rider_id, function ($q) use ($rider_ids) {
            return $q->whereIn('id', $rider_ids);
        })->when($request->zone_to, function ($q) use ($zone_ids) {
            return $q->whereIn('id', $zone_ids);
        })->when($request->start_date && $request->end_date, function ($q) use ($request) {
            $date_arr = [Carbon::parse($request->start_date), Carbon::parse($request->end_date)];
            return $q->whereBetween('dispatched_on', $date_arr);
        });


        if ($request->paginate) {
            $sales = $sale->paginate(100);
        } else {
            $sales = $sale->get();
        }
        $company = Auth::user()->company;


        $sales = $sales->transform(function ($order) use ($company) {
            $product_name = '';
            $qty = 0;
            // dd($delivered);

            foreach ($order->products as $key => $value) {
                $qty += $value->pivot->quantity;
                if ($key == 0) {
                    $product_name = $value->product_name;
                } else {
                    $product_name = $product_name . ' | ' .  $value->product_name;
                }
            }

            foreach ($order->products as $key => $product) {
                if ($key == 0) {
                    $product_name = $product->product_name;
                } else {
                    $product_name = $product_name . '|' . $product->product_name;
                }
            }

            $order->product_name = $product_name;
            $order->qty  = $qty;
            $order->client_name = $order->client->name;
            $order->product_name = $product_name;
            $order->company_name = $company->name;
            $order->company_email = $company->email;
            $order->company_phone = $company->phone;
            $order->company_address = $company->address;
            $order->company_city = $company->region;
            return $order;
        });


        return  ['sales' => $sales];
    }


    public function files()
    {

        $files = Storage::disk('tenant')->files(tenant('id') . '/sales');
        $timezone = optional(auth()->user())->timezone ?? config('app.timezone');
        $fileA = [];
        $file_data = [];
        foreach ($files as $file) {
            $dateTime = substr($file, strrpos($file, '/') + 1, -4);
            $fileA['name'] = (string) $dateTime;
            $fileA['path'] = (string) $file;
            $fileA['date'] = Carbon::parse($dateTime)->timezone($timezone)->format('D d M Y H:i');
            $file_data[] = $fileA;
        }


        usort($file_data, function ($a, $b) {
            return strtotime($a['date']) < strtotime($b['date']);
        });
        return $file_data;
    }
    public function search_order($id)
    {
        return $sale = Sale::setEagerLoads([])->with(['client', 'products' => function ($query) {
            $query->setEagerLoads([]);
        }])->where('id', $id)->latest()->first();

        if ($sale) {

            if ($sale->client) {
                // return $sale->client->id;
                $clients = Client::find($sale->client->id);

                $clients->sales = collect([$sale]);


                if ($clients) {
                    $clients->sales->transform(function ($order) {
                        $product_name = '';
                        // $qty = 0;
                        foreach ($order->products as $key => $product) {
                            $qty = $product->pivot->quantity;
                            if ($key == 0) {
                                $product_name = $product->product_name . '(Qty: ' . $qty .  ') ';
                            } else {
                                $product_name = $product_name . '(Qty: ' . $qty . ') ' . '|' . $product->product_name;
                            }
                        }

                        $order->product_name = $product_name;
                        // $order->qty  = $qty;
                        return $order;
                    });
                }
                return $clients;
            }

            // Remove any non-digit characters from the phone number
            // $cleanPhoneNumber = preg_replace('/\D/', '', $phone);

            // // Format the phone number with the international code if missing
            // if (strlen($cleanPhoneNumber) === 9) {
            //     $cleanPhoneNumber = '254' . $cleanPhoneNumber;
            // }
            // Call::where()->get();

            $clients = Client::with(['sales' => function ($q) {
                $q->withoutGlobalScopes([])->setEagerLoads([])->with(['products' => function ($query) {
                    $query->setEagerLoads([]);
                }]);
            }])->where('id', $sale->client->id)->first();

            if ($clients) {
                $clients->sales->transform(function ($order) {
                    $product_name = '';
                    // $qty = 0;
                    foreach ($order->products as $key => $product) {
                        $qty = $product->pivot->quantity;
                        if ($key == 0) {
                            $product_name = $product->product_name . '(Qty: ' . $qty .  ') ';
                        } else {
                            $product_name = $product_name . '(Qty: ' . $qty . ') ' . '|' . $product->product_name;
                        }
                    }

                    $order->product_name = $product_name;
                    // $order->qty  = $qty;
                    return $order;
                });
            }

            return $clients;
        }
    }
    public function searchLeads($phone)
    { 
        $client_ = new Client();

        $cleanPhoneNumber = $client_->cleanPhone($phone);

        $clients = Client::with(['sales' => function ($q) {
            $q->select('id', 'client_id','order_no', 'total_price', 'delivery_status', 'delivery_date', 'created_at', 'customer_notes')->withoutGlobalScope(OrderScope::class)->setEagerLoads([])->with(['products' => function ($query) {
                $query->select('products.id', 'products.product_name')->setEagerLoads([]);
            }]);
        }])->where('phone', 'LIKE', "%{$cleanPhoneNumber}%")->first();

        if ($clients) {
            if (!empty($clients->sales)) {
                $clients->sales->transform(function ($order) {
                    $product_name = '';
                    // $qty = 0;
                    foreach ($order->products as $key => $product) {
                        $qty = $product->pivot->quantity;
                        if ($key == 0) {
                            $product_name = $product->product_name . '(Qty: ' . $qty .  ') ';
                        } else {
                            $product_name = $product_name . '(Qty: ' . $qty . ') ' . '|' . $product->product_name;
                        }
                    }

                    $order->product_name = $product_name;
                    // $order->qty  = $qty;
                    return $order;
                });
            }
        }

        return $clients;
    }
}
