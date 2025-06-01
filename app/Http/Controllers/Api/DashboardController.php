<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Client;
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
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function logged_user()
    {
        $user = auth('api')->user();
        // return User::first();
        // $user = new User;
        // return  $user->logged_user();
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

    public function total_sales_count($range)
    {
        return Sale::when(!empty($range), function ($q) use ($range) {
            $q->whereBetween('created_at', $range);
        })->count();
    }

    public function product_count()
    {
        return Product::setEagerLoads([])->count();
    }

    public function week_total_sales_income()
    {
        return ProductSale::setEagerLoads([])->sum('total_price');
    }
    public function week_sold_items($date_range)
    {
        return ProductSale::setEagerLoads([])->whereBetween('created_at', $this->get_week($date_range))->count();
    }
    public function week_orders($date_range)
    {
        if (Auth::guard('seller')->check()) {
            $sale_id = $this->product_sale($date_range);
            return Sale::select('id')->setEagerLoads([])->whereBetween('created_at', $this->get_week($date_range))->whereIn('id', $sale_id)->count();
        }
        return Sale::select('id')->setEagerLoads([])->whereBetween('created_at', $this->get_week($date_range))->count();
    }

    public function week_sales_count($date_range)
    {


        $sales = Sale::select('id')->setEagerLoads([]);
        if (Auth::guard('seller')->check()) {
            $sale_id = $this->product_sale($date_range);
            $sales = $sales->whereIn('id', $sale_id);
        }
        $sales = $sales->whereBetween('created_at', $this->get_week($date_range))->where('status', 'Complete');
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
        $sales = Sale::setEagerLoads([])->select(DB::raw('count(id) as count, date_format(created_at, "%M") as date'))
            ->where('delivery_status', 'Delivered')
            ->where('delivered_on', '!=', null)
            ->orderBy('id', 'asc')
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
        $sales = Sale::setEagerLoads([])->select(DB::raw('count(id) as count, date_format(created_at, "%M") as date'))
            ->where('delivery_status', 'Returned')
            ->where('returned_on', '!=', null)
            ->orderBy('id', 'asc')
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

        if (!empty($date_range)) {
            $start_date = $date_range[0];
            $end_date = $date_range[1];
            $sales = $sales->whereBetween($date_comp, [$start_date, $end_date]);
        }
        $count = $sales->count();
        return $count;
    }

    public function vendor_performance()
    {
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
            'Scheduled',
            'Awaiting Dispatch'
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

    public function week_stats($status, $date_range)
    {
        $status_new = Str::lower($status);

        if (Auth::guard('seller')->check()) {
            $sales = new Sale;

            if ($status_new == 'delivered') {
                $date_comp = 'delivered_on';
            } elseif ($status_new == 'returned') {
                $date_comp = 'returned_on';
            } elseif ($status_new == 'dispatched') {
                $date_comp = 'dispatched_on';
            } elseif ($status_new == 'cancelled') {
                $date_comp = 'cancelled_on';
            } else {
                $date_comp = 'created_at';
            }

            $count = $sales->where('delivery_status', $status)->whereBetween($date_comp, $this->get_week($date_range))->count();

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


        $count = Sale::setEagerLoads([])
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


        return [
            'data' => $data,
            'count' => (int)$count
        ];
    }

    public function product_analytics($status)
    {
        $products = Product::where('active', true)->pluck('id');
        $ids = Sale::where('delivery_status', $status)->with('products')->pluck('id');
        $product_counts =  ProductSale::whereIn('sale_id', $ids)->whereIn('product_id', $products)->get();

        // Count the number of distinct products
        $distinct_products_count = $product_counts->pluck('product_id')->unique()->count();

        // Total number of sales
        $total_sales = $ids->count();

        // Total products sold
        $total_products_sold = $product_counts->sum('quantity');

        // Calculate average products sold per sale
        $average_products_per_sale = ($total_products_sold > 0) ? $total_products_sold / $total_sales : 0;

        // Additional statistics can be calculated here if needed.

        // Return the statistics
        return [
            'distinct_products_count' => $distinct_products_count,
            'total_sales' => $total_sales,
            'total_products_sold' => $total_products_sold,
            'average_products_per_sale' => number_format($average_products_per_sale, 2),
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

        $date_range = [];

        if ($request->start_date && $request->end_date) {
            $date_range = [$request->start_date, $request->end_date];
        }




        return [
            // 'get_week' => $this->get_week($date_range),
            'total_sales' => $this->total_sales_count($date_range),
            'product' => $this->product_count(),
            'sales_chart' => $this->sales_chart($request),
            // 'delivery_chart' => $this->delivery_chart($request, $date_range),
            // 'return_chart' => $this->return_chart($request, $date_range),
            // 'clients_chart' => $this->clients_chart($request),
            'lowStock' => $this->lowStock(),
            'top_sales' => $this->top_sales(),
            // 'pending' => $this->pending($request),
            'pending' => $this->status_count($request, 'Pending', $date_range),
            'cancelled' => $this->status_count($request, 'Cancelled', $date_range),
            'delivered' => $this->status_count($request, 'Delivered', $date_range),
            'scheduled' => $this->status_count($request, 'Returned', $date_range),
            'dispatched' => $this->status_count($request, 'Dispatched', $date_range),
            'returns' => $this->status_count($request, 'Scheduled', $date_range),
        ];
    }
}
