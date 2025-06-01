<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Client;
use App\Models\Ou;
use App\Models\Product;
use App\Models\ProductSale;
use App\Models\Sale;
use App\Models\Sku;
use App\Models\User;
use App\Models\Warehouse\ProductBin;
use App\Models\Warehouse\Warehouse;
use App\Models\Zone;
use App\Scopes\OrderScope;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class DashboardController1 extends Controller
{
    protected $dateRange;
    protected $ou;
    protected $year;
    protected $cacheTimeout = 3600; // 1 hour cache

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->dateRange = json_decode($request->daterange);
            $this->ou = json_decode($request->ou);
            $this->year = $request->year !== 'undefined' ? $request->year : date('Y');
            return $next($request);
        });
    }

    protected function getCacheKey($method, $params = [])
    {
        return sprintf(
            'dashboard_%s_%s_%s_%s',
            $method,
            md5(json_encode($this->dateRange)),
            $this->ou ?? 'all',
            implode('_', $params)
        );
    }

    protected function getDateRange()
    {
        if (!empty($this->dateRange)) {
            return [$this->dateRange[0], $this->dateRange[1]];
        }
        return [Carbon::now()->subMonth(), Carbon::today()];
    }

    public function dashboard_count(Request $request)
    {
        $cacheKey = $this->getCacheKey('dashboard_count');
        
        return Cache::remember($cacheKey, $this->cacheTimeout, function () use ($request) {
            // Fetch basic counts in a single query
            $basicCounts = $this->getBasicCounts();
            
            // Fetch sales statistics in a single query
            $salesStats = $this->getSalesStatistics();
            
            // Fetch inventory statistics in a single query
            $inventoryStats = $this->getInventoryStatistics();
            
            // Fetch charts data
            $chartsData = $this->getChartsData($request);


            return $this->getAllWeeklyStats($this->getDateRange(), Auth::user()->ou_id);

            
            return array_merge(
                $basicCounts,
                $salesStats,
                $inventoryStats,
                $chartsData
            );
        });
    }

    protected function getBasicCounts()
    {
        return Cache::remember($this->getCacheKey('basic_counts'), $this->cacheTimeout, function () {
            return [
                'user_count' => User::count(),
                'clients_count' => Client::count(),
                'sellers_count' => DB::table('sellers')->count(),
                'ou_count' => Ou::count(),
                'zone_count' => Zone::where('ou_id', Auth::user()->ou_id)->count(),
                'warehouse_count' => Warehouse::count(),
                'product_count' => Product::count(),
            ];
        });
    }

    protected function getSalesStatistics()
    {
        $dateRange = $this->getDateRange();
        $cacheKey = $this->getCacheKey('sales_stats');

        return Cache::remember($cacheKey, $this->cacheTimeout, function () use ($dateRange) {
            $query = Sale::query()
                ->select([
                    DB::raw('COUNT(*) as total_count'),
                    DB::raw('SUM(CASE WHEN delivery_status = "Delivered" THEN 1 ELSE 0 END) as delivered_count'),
                    DB::raw('SUM(CASE WHEN delivery_status = "Scheduled" OR delivery_status = "Awaiting Dispatch" THEN 1 ELSE 0 END) as scheduled_count'),
                    DB::raw('SUM(CASE WHEN delivery_status = "Dispatched" OR delivery_status = "In Transit" THEN 1 ELSE 0 END) as dispatched_count'),
                    DB::raw('SUM(CASE WHEN delivery_status = "Returned" THEN 1 ELSE 0 END) as returns_count')
                ])
                ->whereBetween('created_at', $dateRange);

            if ($this->ou) {
                $query->where('ou_id', $this->ou);
            }

            $stats = $query->first();

            // Calculate week-over-week changes
            $lastWeekStats = $this->getLastWeekComparison();

            return [
                'total_sales_count' => $stats->total_count,
                'delivered_count' => $stats->delivered_count,
                'scheduled_count' => $stats->scheduled_count,
                'dispatched_count' => $stats->dispatched_count,
                'returns_count' => $stats->returns_count,
                'perc_delivery' => $lastWeekStats['delivery_change'],
                'perc_return' => $lastWeekStats['return_change']
            ];
        });
    }

    protected function getInventoryStatistics()
    {
        return Cache::remember($this->getCacheKey('inventory_stats'), $this->cacheTimeout, function () {
            $productBinStats = ProductBin::select(
                DB::raw('SUM(commited) as total_committed'),
                DB::raw('SUM(onhand) as total_onhand')
            )->first();

            $lowStockCount = Sku::whereRaw('quantity < reorder_point')->count();

            $activeProducts = Product::select(
                DB::raw('SUM(CASE WHEN active = 1 THEN 1 ELSE 0 END) as active_count'),
                DB::raw('SUM(CASE WHEN active = 0 THEN 1 ELSE 0 END) as inactive_count')
            )->first();

            return [
                'commited' => $productBinStats->total_committed,
                'onhand' => $productBinStats->total_onhand,
                'lowStock' => $lowStockCount,
                'active_chart' => [
                    'active' => $activeProducts->active_count,
                    'inactive' => $activeProducts->inactive_count
                ]
            ];
        });
    }

    protected function getChartsData(Request $request)
    {
        // return Cache::remember($this->getCacheKey('charts_data'), $this->cacheTimeout, function () use ($request) {
            return [
                'sales_chart' => $this->getSalesChartData(),
                'delivery_chart' => $this->getDeliveryChartData(),
                'return_chart' => $this->getReturnChartData(),
                'clients_chart' => $this->getClientsChartData($request),
            ];
        // });
    }

    public function getClientsChartData(Request $request)
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
    protected function getSalesChartData()
    {
        $query = Sale::select([
            DB::raw('COUNT(*) as count'),
            DB::raw('DATE_FORMAT(created_at, "%M") as date')
        ])
            ->whereYear('created_at', $this->year)
            ->groupBy('date')
            ->orderBy('created_at');

        if (Auth::guard('seller')->check()) {
            $query->whereIn('id', $this->getSellerSaleIds());
        }

        if ($this->ou) {
            $query->where('ou_id', $this->ou);
        }

        $sales = $query->get();

        return $this->formatChartData($sales);
    }

    protected function getDeliveryChartData()
    {
        return $this->getStatusChartData('Delivered', 'delivered_on');
    }

    protected function getReturnChartData()
    {
        return $this->getStatusChartData('Returned', 'returned_on');
    }

    protected function getStatusChartData($status, $dateField)
    {
        $sales = Sale::select([
            DB::raw('COUNT(*) as count'),
            DB::raw("DATE_FORMAT($dateField, '%M') as date")
        ])
            ->where('delivery_status', $status)
            ->whereNotNull($dateField)
            ->whereYear('created_at', $this->year)
            ->groupBy('date')
            ->orderBy($dateField)
            ->get();

        return $this->formatChartData($sales);
    }

    protected function formatChartData($data)
    {
        $months = [
            "January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ];

        $formatted = [
            'labels' => [],
            'rows' => []
        ];

        foreach ($months as $month) {
            $record = $data->firstWhere('date', $month);
            if ($record && $record->count > 0) {
                $formatted['labels'][] = $record->date;
                $formatted['rows'][] = $record->count;
            }
        }

        return ['data' => $formatted];
    }

    protected function getLastWeekComparison()
    {
        $currentWeek = [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()];
        $lastWeek = [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()];

        $stats = Sale::select([
            DB::raw('SUM(CASE WHEN created_at BETWEEN ? AND ? THEN 1 ELSE 0 END) as current_week_total'),
            DB::raw('SUM(CASE WHEN created_at BETWEEN ? AND ? THEN 1 ELSE 0 END) as last_week_total'),
            DB::raw('SUM(CASE WHEN delivery_status = "Delivered" AND delivered_on BETWEEN ? AND ? THEN 1 ELSE 0 END) as current_week_delivered'),
            DB::raw('SUM(CASE WHEN delivery_status = "Delivered" AND delivered_on BETWEEN ? AND ? THEN 1 ELSE 0 END) as last_week_delivered'),
            DB::raw('SUM(CASE WHEN delivery_status = "Returned" AND returned_on BETWEEN ? AND ? THEN 1 ELSE 0 END) as current_week_returned'),
            DB::raw('SUM(CASE WHEN delivery_status = "Returned" AND returned_on BETWEEN ? AND ? THEN 1 ELSE 0 END) as last_week_returned')
        ])
            ->setBindings([
                $currentWeek[0], $currentWeek[1],
                $lastWeek[0], $lastWeek[1],
                $currentWeek[0], $currentWeek[1],
                $lastWeek[0], $lastWeek[1],
                $currentWeek[0], $currentWeek[1],
                $lastWeek[0], $lastWeek[1]
            ])
            ->first();

        return [
            'delivery_change' => $this->calculatePercentageChange(
                $stats->current_week_delivered,
                $stats->current_week_total,
                $stats->last_week_delivered,
                $stats->last_week_total
            ),
            'return_change' => $this->calculatePercentageChange(
                $stats->current_week_returned,
                $stats->current_week_total,
                $stats->last_week_returned,
                $stats->last_week_total
            )
        ];
    }

    protected function calculatePercentageChange($currentCount, $currentTotal, $lastCount, $lastTotal)
    {
        $currentPercentage = $currentTotal > 0 ? ($currentCount / $currentTotal) * 100 : 0;
        $lastPercentage = $lastTotal > 0 ? ($lastCount / $lastTotal) * 100 : 0;
        return number_format($currentPercentage - $lastPercentage, 2);
    }

    protected function getSellerSaleIds()
    {
        return ProductSale::whereBetween('created_at', $this->getDateRange())
            ->pluck('sale_id')
            ->unique()
            ->values()
            ->all();
    }











    private const STATUS_DATE_MAPPINGS = [
        'delivered' => [
            'field' => 'delivered_on',
            'statuses' => ['Delivered']
        ],
        'returned' => [
            'field' => 'returned_on',
            'statuses' => ['Returned']
        ],
        'dispatched' => [
            'field' => 'dispatched_on',
            'statuses' => ['Dispatched', 'In Transit']
        ],
        'scheduled' => [
            'field' => 'schedule_date',
            'statuses' => ['Scheduled', 'Awaiting Dispatch']
        ]
    ];

    public function getWeeklyStats($date_range = null, $ou = null)
    {
        $date_range = $this->getDateRange();
        
        // Get all stats in a single query
        $stats = $this->getAllWeeklyStats($date_range, $ou);
        
        return [
            'week_delivered' => $this->formatStats($stats, 'delivered', $date_range),
            'week_dispatched' => $this->formatStats($stats, 'dispatched', $date_range),
            'week_scheduled' => $this->formatStats($stats, 'scheduled', $date_range),
            'week_returned' => $this->formatStats($stats, 'returned', $date_range)
        ];
    }

    private function getAllWeeklyStats($date_range, $ou = null)
    {
        $query = Sale::setEagerLoads([])
            ->withoutGlobalScope(OrderScope::class)
            ->select([
                'delivery_status',
                'delivered_on',
                'returned_on',
                'dispatched_on',
                'schedule_date'
            ]);

        if ($ou) {
            $query->where('ou_id', $ou);
        }

        if (Auth::guard('seller')->check()) {
            $query->where('ou_id', Auth::guard('seller')->user()->ou_id);
        }

        // Build union query for all status types
        $unions = [];
        foreach (self::STATUS_DATE_MAPPINGS as $key => $mapping) {
            $unionQuery = clone $query;
            $unionQuery->whereIn('delivery_status', $mapping['statuses'])
                ->whereBetween($mapping['field'], $date_range)
                ->selectRaw("
                    DATE({$mapping['field']}) as date,
                    COUNT(*) as count,
                    '{$key}' as status_type
                ")
                ->groupBy('date');
            $unions[] = $unionQuery;
        }

        // Combine all queries with union
        $result = array_reduce($unions, function ($carry, $query) {
            return $carry ? $carry->union($query) : $query;
        });

        return $result->get()->groupBy('status_type');
    }

    private function formatStats($stats, $status, $date_range)
    {
        // Initialize the week array with zeros
        $week_data = $this->initializeWeekData($date_range);
        
        // Get the stats for this status
        $status_stats = $stats->get($status, collect());
        
        // Fill in the actual values
        foreach ($status_stats as $stat) {
            $week_data[$stat->date] = (int)$stat->count;
        }

        return [
            'data' => array_values($week_data),
            'count' => array_sum($week_data)
        ];
    }

    private function initializeWeekData($date_range)
    {
        $start = Carbon::parse($date_range[0]);
        $week_data = [];

        for ($i = 0; $i < 7; $i++) {
            $date = $start->copy()->addDays($i)->format('Y-m-d');
            $week_data[$date] = 0;
        }

        return $week_data;
    }

}