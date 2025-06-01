<?php

namespace App\Http\Controllers;

use App\Branch;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Client;
use App\Models\orderStats;
use App\Models\Ou;
use App\Models\Product;
use App\Models\ProductSale;
use App\Models\Sale;
use App\Models\Sku;
use App\Seller;
use App\Models\User;
use App\Models\Warehouse\ProductBin;
use App\Models\Warehouse\Warehouse;
use App\Models\Zone;
use App\Scopes\OrderScope;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function logged_user()
    {
        $user = new User;
        return  $user->logged_user();
    }
    public function get_week($date_range)
    {
        if (!empty($date_range)) {
            $today = $date_range[1];
            $last_week = $date_range[0];
        } else {
            $today  = Carbon::today();
            $last_week = Carbon::now()->subDays(7);
        }

        return array($last_week, $today);
    }
    public function product_sale($date_range)
    {
        $product_sale = ProductSale::setEagerLoads([])->whereBetween('created_at', $this->get_week($date_range))->get('sale_id');
        $sale_id = [];
        foreach ($product_sale as $sale) {
            $sale_id[] = $sale->sale_id;
        }
        return $sale_id;
    }

    public function user_count()
    {
        return User::setEagerLoads([])->count();
    }
    public function clients_count()
    {

        return Client::setEagerLoads([])->count();
    }
    public function sellers_count()
    {

        return Seller::setEagerLoads([])->count();
    }
    public function ou_count()
    {


        return Ou::setEagerLoads([])->count();
    }
    public function zone_count()
    {
        return Zone::setEagerLoads([])->where('ou_id', $this->logged_user()->ou_id)->count();
    }
    public function total_sales_count($date_range, $ou)
    {
        return Sale::select('id')->setEagerLoads([])->count();
    }

    public function product_count()
    {
        return Product::setEagerLoads([])->count();
    }

    public function category_count()
    {


        return Category::setEagerLoads([])->count();
    }

    public function brand_count()
    {


        return Brand::setEagerLoads([])->count();
    }

    public function week_total_sales_income($date_range, $ou)
    {


        return ProductSale::setEagerLoads([])->sum('total_price');
    }
    public function week_sold_items($date_range, $ou)
    {
        return ProductSale::setEagerLoads([])->whereBetween('created_at', $this->get_week($date_range, $ou))->count();
    }
    public function week_orders($date_range, $ou)
    {
        if (Auth::guard('seller')->check()) {
            $sale_id = $this->product_sale($date_range, $ou);
            return Sale::select('id')->setEagerLoads([])->whereBetween('created_at', $this->get_week($date_range, $ou))->whereIn('id', $sale_id)->count();
        }
        return Sale::select('id')->setEagerLoads([])->whereBetween('created_at', $this->get_week($date_range, $ou))->count();
    }

    public function week_sales_count($date_range, $ou)
    {


        $sales = Sale::select('id')->setEagerLoads([]);
        if (Auth::guard('seller')->check()) {
            $sale_id = $this->product_sale($date_range, $ou);
            $sales = $sales->whereIn('id', $sale_id);
        }
        $sales = $sales->whereBetween('created_at', $this->get_week($date_range, $ou))->where('status', 'Complete');
        return $sales->count();
    }

    public function commited()
    {
        return ProductBin::setEagerLoads([])->sum('commited');
    }

    public function onhand()
    {

        // return ProductBin::all();
        return ProductBin::setEagerLoads([])->sum('onhand');
    }
    // Charts

    public function sales_chart_1(Request $request, $date_range)
    {


        // return Sale::all();
        $sales = Sale::setEagerLoads([])->select(DB::raw('count(id) as count, date_format(created_at, "%M") as date'))
            ->orderBy('id', 'asc')
            ->groupBy('date');

        if (Auth::guard('seller')->check()) {
            $sale_id = $this->product_sale($date_range);
            $sales = $sales->whereIn('id', $sale_id);
        }
        // return $request->all();

        if ($request->ou != 'undefined') {
            $sales = $sales->where('ou_id', $request->ou);
        }
        if ($request->year != 'undefined') {
            $sales = $sales->whereYear('created_at', '=', (int) $request->year);
        } else {
            $sales = $sales->whereYear('created_at', '=', date("Y"));
        }
        $sales = $sales->get();
        $lables = [];
        $rows = [];
        foreach ($sales as $key => $sale) {
            $lables[] = $sale->date;
            $rows[] = $sale->count;
        }
        $data = array(
            'lables' => $lables,
            'rows' => $rows
        );

        $monthLabels = [
            "January",
            "February",
            "March",
            "April",
            "May",
            "June",
            "July",
            "August",
            "September",
            "October",
            "November",
            "December"
        ];

        // $rearrangedData = array_combine($monthLabels, $data['rows']);

        $existingLabels = $data['lables'];
        $existingRows = $data['rows'];

        $filteredData = array_filter($existingLabels, function ($label) use ($monthLabels) {
            return in_array($label, $monthLabels);
        }, ARRAY_FILTER_USE_BOTH);

        $rearrangedData = array_map(function ($month) use ($existingLabels, $existingRows) {
            $index = array_search($month, $existingLabels);
            return $existingRows[$index] ?? 0;
        }, $monthLabels);

        $chartData['lables'] = array_values($filteredData);
        $chartData['rows'] = $rearrangedData;

        return response()->json(['data' => $chartData]);
    }
    public function sales_chart(Request $request)
    {
        $date_range = null;
        $sales = Sale::setEagerLoads([])
            ->select(DB::raw('count(id) as count, date_format(created_at, "%M") as date'))
            ->orderBy('id', 'asc')
            ->groupBy('date')
            ->when(Auth::guard('seller')->check(), function ($query) use ($date_range) {
                $sale_id = $this->product_sale($date_range);
                return $query->whereIn('id', $sale_id);
            })
            ->when($request->ou != 'undefined', function ($query) use ($request) {
                return $query->where('ou_id', $request->ou);
            })
            ->when($request->year != 'undefined', function ($query) use ($request) {
                return $query->whereYear('created_at', '=', (int) $request->year);
            }, function ($query) {
                return $query->whereYear('created_at', '=', date("Y"));
            })
            ->get();

        $monthLabels = [
            "January",
            "February",
            "March",
            "April",
            "May",
            "June",
            "July",
            "August",
            "September",
            "October",
            "November",
            "December"
        ];

        $data = [
            'lables' => [],
            'rows' => []
        ];

        foreach ($monthLabels as $month) {
            $sale = $sales->firstWhere('date', $month);
            if ($sale && $sale->count > 0) {
                $data['lables'][] = $sale->date;
                $data['rows'][] = $sale->count;
            }
        }

        $chartData = [
            'lables' => $data['lables'],
            'rows' => $data['rows']
        ];

        return response()->json(['data' => $chartData]);
    }



    public function delivery_chart(Request $request)
    {
        $date_range = null;
        $sales = Sale::setEagerLoads([])->select(DB::raw('count(id) as count, date_format(delivered_on, "%M") as date'))
            ->where('delivery_status', 'Delivered')
            ->where('delivered_on', '!=', null)
            // ->orderBy('id', 'asc')
            ->orderBy('delivered_on', 'asc')
            ->groupBy('date');

        if (Auth::guard('seller')->check()) {
            $sale_id = $this->product_sale($date_range);
            $sales = $sales->whereIn('id', $sale_id);
        }
        // return $request->all();
        if ($request->year) {
            $sales = $sales->whereYear('created_at', '=', (int) $request->year);
        } else {
            $sales = $sales->whereYear('created_at', '=', date("Y"));
        }
        $sales = $sales->get();
        // return ($sales);
        $lables = [];
        $rows = [];
        foreach ($sales as $key => $sale) {
            $lables[] = $sale->date;
            $rows[] = $sale->count;
        }
        $data = array(
            'lables' => $lables,
            'rows' => $rows
        );
        return response()->json(['data' => $data]);
    }


    public function return_chart(Request $request)
    {
        $date_range = null;
        // return Sale::all();
        $sales = Sale::setEagerLoads([])->select(DB::raw('count(id) as count, date_format(returned_on, "%M") as date'))
            ->where('delivery_status', 'Returned')
            ->where('returned_on', '!=', null)
            ->orderBy('returned_on', 'asc')
            ->groupBy('date');

        if (Auth::guard('seller')->check()) {
            $sale_id = $this->product_sale($date_range);
            $sales = $sales->whereIn('id', $sale_id);
        }
        // return $request->all();
        if ($request->year) {
            $sales = $sales->whereYear('created_at', '=', (int) $request->year);
        } else {
            $sales = $sales->whereYear('created_at', '=', date("Y"));
        }
        $sales = $sales->get();
        $lables = [];
        $rows = [];
        foreach ($sales as $key => $sale) {
            $lables[] = $sale->date;
            $rows[] = $sale->count;
        }
        $data = array(
            'lables' => $lables,
            'rows' => $rows
        );
        return response()->json(['data' => $data]);
    }

    public function sellers_chart(Request $request)
    {


        // return $request->all();
        $sellers = Seller::setEagerLoads([])->select(DB::raw('count(id) as count, date_format(created_at, "%M") as date'))
            ->orderBy('id', 'asc')
            ->groupBy('date');
        if ($request->year) {
            $sellers = $sellers->whereYear('created_at', '=', (int) $request->year);
        }
        $sellers = $sellers->get();
        $lables = [];
        $rows = [];
        foreach ($sellers as $key => $seller) {
            $lables[] = $seller->date;
            $rows[] = $seller->count;
        }
        $data = array(
            'lables' => $lables,
            'rows' => $rows
        );
        return response()->json(['data' => $data]);
    }
    public function clients_chart(Request $request)
    {


        // return $request->all();
        $schools = Client::setEagerLoads([])->select(DB::raw('count(id) as count, date_format(created_at, "%M") as date'))
            ->orderBy('id', 'asc')
            ->groupBy('date');
        if ($request->year) {
            $schools = $schools->whereYear('created_at', '=', (int) $request->year);
        }
        $schools = $schools->get();
        $lables = [];
        $rows = [];
        foreach ($schools as $key => $school) {
            $lables[] = $school->date;
            $rows[] = $school->count;
        }
        $data = array(
            'lables' => $lables,
            'rows' => $rows
        );
        return response()->json(['data' => $data]);
    }

    public function lowStock()
    {
        $products = Product::setEagerLoads([])->get('id');
        $ids = [];
        foreach ($products as $key => $value) {
            $ids[] = $value->id;
        }
        return Sku::setEagerLoads([])->where('product_id', $ids)->whereRaw('quantity < reorder_point')->count();
    }


    public function weekly_sale()
    {


        $dates = collect();
        foreach (range(-3, 1) as $i) {
            $date = Carbon::now()->addDays($i)->format('Y-m-d');
            $dates->put($date, 0);
        }
        // Get the post counts
        $orders = Sale::select('id')->setEagerLoads([])->where('created_at', '>=', $dates->keys()->first())
            ->groupBy('date')
            ->orderBy('date')
            ->get([
                DB::raw('DATE( created_at ) as date'),
                DB::raw('sum(sub_total) as "count"')
            ])
            ->pluck('count', 'date');

        // Merge the two collections; any results in `$orders` will overwrite the zero-value in `$dates`
        $dates = $dates->merge($orders);
        return $dates;
    }

    public function top_sales()
    {

        return [];
        $product_sale = ProductSale::setEagerLoads([])->groupBy('product_id')->orderBy('product_id', 'desc')->take(2)->get();
        $product_sale->transform(function ($sale_) {
            $product = Product::find($sale_->product_id);
            // dd($product->product_name);
            $sale_->product_name = ($product)  ? $product->product_name : null;
            return $sale_;
        });
        return $product_sale;
        // return ProductSale::setEagerLoads([])->select(
        //     'product_sale.*',
        //     DB::raw('SUM(quantity)')
        // )
        // ->groupBy('product_id')
        // ->take(2);
    }

    public function status_count(Request $request, $status, $date_range)
    {
        if ($status == 'Dispatched') {
            $sales = Sale::select('id', 'ou_id')->setEagerLoads([])->whereIn('delivery_status', ['Dispatched', 'In Transit']);
        } else {
            $sales = Sale::select('id', 'ou_id')->setEagerLoads([])->where('delivery_status', $status);
        }


        if ($status == 'Delivered') {
            $date_comp = 'delivered_on';
        } elseif ($status == 'Returned') {
            $date_comp = 'returned_on';
        } elseif ($status == 'Dispatched') {
            $date_comp = 'dispatched_on';
        } elseif ($status == 'Cancelled') {
            $date_comp = 'cancelled_on';
        } else {
            $date_comp = 'created_at';
        }

        if ($date_range) {
            $start_date = $date_range[0];
            $end_date = $date_range[1];
            $sales = $sales->whereBetween($date_comp, [$start_date, $end_date]);
        } elseif ($request->filter == 'year') {
            if ($request->year != 'undefined') {
                $format = 'Y-m-d';
                $date = $request->year;
                $sales = $sales->whereYear($date_comp, $request->year);
            }
        } else {
            $sales = $sales->whereYear($date_comp, date("Y"));
        }

        if ($request->ou && $request->ou != 'undefined') {
            $sales = $sales->where('ou_id', $request->ou);
        } else {
            $sales = $sales->where('ou_id', $this->logged_user()->ou_id);
        }
        // $sales->count();
        // dd(DB::getQueryLog()); // Show results of log
        return $sales->count();
    }

    public function vendor_performance()
    {
        return [];
        return  Seller::setEagerLoads([])->withCount(['sales as delivered_count' => function ($query) {
            $query->where('delivery_status', 'Delivered');
        }, 'sales as returned_count' => function ($query) {
            $query->where('delivery_status', 'Returned');
        }, 'sales as sales_count' => function ($query) {
            // $query->where('Returned');
        }])->inRandomOrder()->take(4)->get();
    }


    public function vendorPerformance(Request $request)
    {
        return  Seller::setEagerLoads([])->withCount(['sales as delivered_count' => function ($query) {
            $query->where('delivery_status', 'Delivered');
        }, 'sales as returned_count' => function ($query) {
            $query->where('delivery_status', 'Returned');
        }, 'sales as sales_count' => function ($query) {
            // $query->where('Returned');
        }])->whereBetween('created_at', $request->all())->get();
    }

    public function pending(Request $request)
    {

        $statuses = [
            'Delivered',
            'Cancelled',
            'Returned',
            'Dispatched',
            'Scheduled'
        ];

        $sales = Sale::select('id', 'ou_id', 'created_at')->setEagerLoads([])->whereNotIn('delivery_status', $statuses);

        if ($request->ou != 'undefined') {
            $sales = $sales->where('ou_id', $request->ou);
        } else {
            $sales = $sales->where('ou_id', $this->logged_user()->ou_id);
        }
        if ($request->year != 'undefined') {
            $format = 'Y-m-d';
            $year_formart = 'Y';
            $date = $request->year;
            $d = DateTime::createFromFormat($format, $date);

            $is_date = ($d && $d->format($format) === $date);


            if ($is_date) {
                $sales = $sales->whereDate('created_at', $request->year);
            } else {
                $sales = $sales->whereYear('created_at', $request->year);
            }
        }
        return $sales->count();
    }

    public function warehouse_count()
    {
        return Warehouse::count();
    }

    public function active_chart()
    {
        $active = Product::where('active', 1)->count();
        $inactive = Product::where('active', 0)->count();

        return collect([
            "active" => $active,
            "inactive" => $inactive
        ]);
    }

    public function week_stats($status, $date_range, $ou)
    {
        $status_new = Str::lower($status);

        if (Auth::guard('seller')->check()) {
            $sales = new Sale;

            if ($status_new == 'Delivered') {
                $date_comp = 'delivered_on';
            } elseif ($status_new == 'Returned') {
                $date_comp = 'returned_on';
            } elseif ($status_new == 'In Transit') {
                $date_comp = 'dispatched_on';
            } elseif ($status_new == 'Cancelled') {
                $date_comp = 'cancelled_on';
            } else {
                $date_comp = 'created_at';
            }

            $count = $sales->where('delivery_status', $status)
                ->when($ou, function ($query) use ($ou) {
                    return $query->where('ou_id', $ou);
                })->whereBetween($date_comp, $this->get_week($date_range, $ou))->count();

            // $sales = $sales->where('ou_id', $this->logged_user()->ou_id);
            return [
                'data' => null,
                'count' => $count
            ];
        }

        if (!empty($date_range)) {
            $date = $date_range;
        } else {
            $date = [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()];
        }


        $count = Sale::setEagerLoads([])->withoutGlobalScope(OrderScope::class)
            ->when($status == 'Delivered', function ($query) use ($date) {
                return $query->where('delivery_status', 'Delivered')
                    ->whereBetween('delivered_on', $date);
            })
            ->when($status == 'Returned', function ($query) use ($date) {
                return $query->where('delivery_status', 'Returned')
                    ->whereBetween('returned_on', $date);
            })
            ->when($status == 'Scheduled', function ($query) use ($date) {
                return $query->whereIn('delivery_status', ['Scheduled', 'Awaiting Dispatch'])
                    ->whereBetween('schedule_date', $date);
            })
            ->when($ou, function ($query) use ($ou) {
                return $query->where('ou_id', $ou);
            })
            ->when($status == 'Dispatched', function ($query) use ($date) {
                return $query->whereIn('delivery_status', ['Dispatched', 'In Transit'])
                    ->whereBetween('dispatched_on', $date);
            })
            ->count();


        // Get the correct date field based on the status
        $dateField = ($status == 'Delivered' ? 'delivered_on' : ($status == 'Returned' ? 'returned_on' : ($status == 'Dispatched' ? 'dispatched_on' : 'schedule_date')));

        // Query for the data
        $data = Sale::selectRaw("DATE($dateField) as date, COUNT(*) as count")
            ->when($status == 'Delivered', function ($query) use ($date) {
                return $query->where('delivery_status', 'Delivered')
                    ->whereBetween('delivered_on', $date);
            })
            ->when($status == 'Returned', function ($query) use ($date) {
                return $query->where('delivery_status', 'Returned')
                    ->whereBetween('returned_on', $date);
            })
            ->when($status == 'Scheduled', function ($query) use ($date) {
                return $query->whereIn('delivery_status', ['Scheduled', 'Awaiting Dispatch'])
                    ->whereBetween('schedule_date', $date);
            })
            ->when($status == 'Dispatched', function ($query) use ($date) {
                return $query->whereIn('delivery_status', ['Dispatched', 'In Transit'])
                    ->whereBetween('dispatched_on', $date);
            })
            ->groupBy('date')
            ->pluck('count', 'date') // Pluck count as values and date as keys
            ->map(function ($count) {
                return (int) $count; // Convert counts to integers
            })
            ->all();

        // Fill in the data for all days of the week (initialized to 0)
        $week_delivered = [
            Carbon::now()->startOfWeek()->format('Y-m-d') => 0,
            Carbon::now()->startOfWeek()->addDay()->format('Y-m-d') => 0,
            Carbon::now()->startOfWeek()->addDays(2)->format('Y-m-d') => 0,
            Carbon::now()->startOfWeek()->addDays(3)->format('Y-m-d') => 0,
            Carbon::now()->startOfWeek()->addDays(4)->format('Y-m-d') => 0,
            Carbon::now()->startOfWeek()->addDays(5)->format('Y-m-d') => 0,
            Carbon::now()->startOfWeek()->addDays(6)->format('Y-m-d') => 0,
        ];

        // Merge the retrieved data with the initialized data
        $week_delivered = array_merge($week_delivered, $data);

        // Sort the array based on keys (dates)
        ksort($week_delivered);

        // Prepare the final response
        return [
            'data' => array_values($week_delivered),
            'count' => array_sum($week_delivered),
        ];


        /*
        if ($status == 'delivered') {
            $count = Sale::setEagerLoads([])->where('delivery_status', 'Delivered')->whereBetween('delivered_on', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();

            $data = Sale::selectRaw('DATE(delivered_on) as date, COUNT(*) as count')
                ->where('delivery_status', 'Delivered')
                ->whereBetween('delivered_on', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                ->groupBy('date')
                ->get();
        } elseif ($status == 'returned') {
            $count = Sale::setEagerLoads([])->where('delivery_status', 'Returned')->whereBetween('returned_on', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();

            $data = Sale::selectRaw('DATE(returned_on) as date, COUNT(*) as count')
                ->where('delivery_status', 'Returned')
                ->whereBetween('returned_on', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                ->groupBy('date')
                ->get();
        } elseif ($status == 'schedule') {
            $count = Sale::setEagerLoads([])->whereIn('delivery_status', ['Scheduled', 'Awaiting Dispatch'])->whereBetween('schedule_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();

            $data = Sale::selectRaw('DATE(schedule_date) as date, COUNT(*) as count')
                ->whereIn('delivery_status', ['Scheduled', 'Awaiting Dispatch'])
                ->whereBetween('schedule_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                ->groupBy('date')
                ->get();
        }  elseif ($status == 'dispatched') {
            $count = Sale::setEagerLoads([])->whereIn('delivery_status', ['Dispatched', 'In Transit'])->whereBetween('dispatched_on', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();

            $data = Sale::selectRaw('DATE(dispatched_on) as date, COUNT(*) as count')
                ->whereIn('delivery_status', ['Dispatched', 'In Transit'])
                ->whereBetween('dispatched_on', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                ->groupBy('date')
                ->get();
        } else {
            $stats =  orderStats::where('created_at', '>', Carbon::now()->startOfWeek())->where('created_at', '<', Carbon::now()->endOfWeek());
            $data = $stats->get($status)->flatten()->pluck($status);
            $count = $stats->sum($status);
        }
        */
        return [
            'data' => $data,
            'count' => (int)$count
        ];
    }

    public function week_inc($status, $date_range)
    {
        // $last_week =  orderStats::where('created_at', '>', Carbon::now()->subWeek()->startOfWeek())->where('created_at', '<', Carbon::now()->subWeek()->endOfWeek())->sum($status);


        $lastWeek = [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()];

        $last_orders = Sale::whereBetween('created_at', $lastWeek)->count();

        $last_week = Sale::where('delivery_status', $status)->whereBetween('delivered_on', $this->get_week($date_range))->count();

        if ($last_orders > 0) {
            $perc = $last_week / $last_orders * 100;
        } else {
            $perc = 0;
        }
        $date_range = $this->get_week($date_range);


        $this_week = Sale::setEagerLoads([])
            ->when($status == 'Delivered', function ($query) use ($date_range) {
                return $query->where('delivery_status', 'Delivered')
                    ->whereBetween('delivered_on', $date_range);
            })
            ->when($status == 'Returned', function ($query) use ($date_range) {
                return $query->where('delivery_status', 'Returned')
                    ->whereBetween('returned_on', $date_range);
            })
            ->when($status == 'Scheduled', function ($query) use ($date_range) {
                return $query->whereIn('delivery_status', ['Scheduled', 'Awaiting Dispatch'])
                    ->whereBetween('schedule_date', $date_range);
            })
            ->when($status == 'Dispatched', function ($query) use ($date_range) {
                return $query->whereIn('delivery_status', ['Dispatched', 'In Transit'])
                    ->whereBetween('dispatched_on', $date_range);
            })->count();


        $thisWeek = [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()];

        $this_orders = Sale::whereBetween('created_at', $thisWeek)->count();


        if ($this_orders > 0) {
            $this_perc = ($this_week / $this_orders) * 100;
        } else {
            $this_perc = 0;
        }



        return number_format((float)($this_perc - $perc), 2, '.', '');
    }

    public function dashboard_count(Request $request)
    {
        // return $request->all();

        $date_range = json_decode($request->daterange);
        $ou = null;
        // $ou = $request->ou;

        // $ou_id = AUth::user()->ou_id;

        // $cacheKey = "dashboard_stats_{$request->daterange}_{$ou_id}";

        // return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($request, $date_range, $ou) {
            return [
                'get_week' => $this->get_week($ou),
                // 'product_sale' => $this->product_sale(),
                'user_count' => $this->user_count(),
                'clients_count' => $this->clients_count(),
                'zone_count' => $this->zone_count(),
                'sellers_count' => $this->sellers_count(),
                'ou_count' => $this->ou_count(),
                'total_sales_count' => $this->total_sales_count($date_range, $ou),
                'product_count' => $this->product_count(),
                'week_total_sales_income' => $this->week_total_sales_income($date_range, $ou),
                'week_sold_items' => $this->week_sold_items($date_range, $ou),
                'week_orders' => $this->week_orders($date_range, $ou),
                'week_sales_count' => $this->week_sales_count($date_range, $ou),
                'commited' => $this->commited(),
                'onhand' => $this->onhand(),
                'sales_chart' => $this->sales_chart($request),
                'delivery_chart' => $this->delivery_chart($request, $date_range, $ou),
                'return_chart' => $this->return_chart($request, $date_range, $ou),
                // 'sellers_chart' => $this->sellers_chart($request),
                'clients_chart' => $this->clients_chart($request),
                'lowStock' => $this->lowStock(),
                'warehouse_count' => $this->warehouse_count(),
                // 'weekly_sale' => $this->weekly_sale(),
                'top_sales' => $this->top_sales(),
                // 'vendor_performance' => $this->vendor_performance(),
                'pending' => $this->pending($request),
                'delivered_count' => $this->status_count($request, 'Delivered', $date_range, $ou),
                'scheduled_count' => $this->status_count($request, 'Scheduled', $date_range, $ou),
                'dispatched_count' => $this->status_count($request, 'Dispatched', $date_range, $ou),
                'returns_count' => $this->status_count($request, 'Returned', $date_range, $ou),
                'active_chart' => $this->active_chart(),

                'week_delivered' => $this->week_stats('Delivered', $date_range, $ou),
                'week_dispatched' => $this->week_stats('Dispatched', $date_range, $ou),
                'week_scheduled' => $this->week_stats('Scheduled', $date_range, $ou),
                'week_returned' => $this->week_stats('Returned', $date_range, $ou),

                'perc_delivery' => $this->week_inc('Delivered', $date_range, $ou),
                'perc_return' => $this->week_inc('Returned', $date_range)

            ];
        // });
    }
}
