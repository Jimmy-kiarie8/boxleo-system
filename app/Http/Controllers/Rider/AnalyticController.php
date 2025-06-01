<?php

namespace App\Http\Controllers\Rider;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\SaleZone;
use App\Models\Zone;
use App\Rider;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AnalyticController extends Controller
{
    public function zone_sales()
    {
        $sales_ids = [];
        if (Auth::check()) {
            if (Auth::user()->hasRole('Agent')) {
                $zones = Zone::where('manager', Auth::id())->pluck('id');
                $sales_ids = SaleZone::whereIn('zone_to', $zones)->pluck('sale_id');
            } else {
                $zones = Zone::where('default_zone', false)->pluck('id');
                $sales_ids = SaleZone::whereYear('created_at', Carbon::now()->year)->whereMonth('created_at',Carbon::now()->month)->whereIn('zone_to', $zones)->pluck('sale_id');
            }
        }
        return $sales_ids;
    }
    public function agents()
    {
        // $sales_ids = $this->zone_sales();
        $sales = Sale::setEagerLoads([])
            ->select(DB::raw('count(id) as count, delivery_status'))
            ->whereIn('delivery_status', ['In Transit', 'Returned', 'Delivered'])
            ->groupBy('delivery_status')
            // ->when((!$sales_ids), function ($q)  use ($sales_ids) {
            //     return $q->whereIn('id', $sales_ids);
            // })
            ->get();

        $lables = [];
        $rows = [];

        foreach ($sales as $sale) {
            $lables[] = $sale->delivery_status;
            $rows[] = $sale->count;
        }

        $data = [
            'lables' => $lables,
            'rows' => $rows
        ];

        return response()->json(['data' => $data]);
    }

    public function statusCount($status)
    {
        // $sales_ids = $this->zone_sales();
        $sales = Sale::setEagerLoads([])
            ->select('id', 'ou_id')
            // ->when((!$sales_ids), function ($q)  use ($sales_ids) {
            //     return $q->whereIn('id', $sales_ids);
            // })
            ->when(($status == 'Delivered'), function ($q) {
                return $q->whereYear('delivered_on', Carbon::now()->year)
                    ->whereMonth('delivered_on', Carbon::now()->month);
            })->when(($status == 'Returned'), function ($q) {
                return $q->whereYear('returned_on', Carbon::now()->year)
                    ->whereMonth('returned_on', Carbon::now()->month);
            })->when(($status == 'In Transit'), function ($q) {
                return $q->whereYear('dispatched_on', Carbon::now()->year)
                    ->whereMonth('dispatched_on', Carbon::now()->month);
            })
            ->where('delivery_status', 'Delivered');

        return $sales->count();
    }

    public function agents_performance()
    {
       $sales_ids = $this->zone_sales();

        $data = DB::table('zones')
            ->select(
                'zones.name as zone',
                DB::raw('COUNT(sales.id) as sales_count'),
                DB::raw('SUM(CASE WHEN sales.delivery_status = "Delivered" THEN 1 ELSE 0 END) as delivered_count'),
                DB::raw('SUM(CASE WHEN sales.delivery_status = "Returned" THEN 1 ELSE 0 END) as returned_count'),
                DB::raw('SUM(CASE WHEN sales.delivery_status = "In Transit" THEN 1 ELSE 0 END) as in_transit')
            )
            ->join('sale_zones', 'zones.id', '=', 'sale_zones.zone_to')
            ->join('sales', 'sales.id', '=', 'sale_zones.sale_id')
            ->groupBy('zones.id')
            ->orderBy('zones.name')
            ->whereIn('sales.id', $sales_ids)
            ->paginate();

        return response()->json($data);
    }

    public function order_count()
    {
        // $sales_ids = $this->zone_sales();
        return Sale::whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)->count();
            // ->when((!$sales_ids), function ($q)  use ($sales_ids) {
            //     return $q->whereIn('id', $sales_ids);
            // })->count();
    }

    public function rate_calc($status)
    {
        $orders = $this->order_count();
        $rates = $this->statusCount($status);
        if ($orders) {
            return round(($rates / $orders) * 100, 2);
        }
        return 0;
    }


    public function agents_dashboard(Request $request)
    {
        return [
            'orders' => $this->order_count(),
            'return_rate' => $this->rate_calc('Returned'),
            'delivery_rate' => $this->rate_calc('Delivered'),
            'returned' => $this->statusCount('Returned'),
            'delivered' => $this->statusCount('Delivered'),
            'in_transit' => $this->statusCount('In Transit'),
            // 'agents_performance' => $this->agents_performance()
        ];
    }



    // public function agent_chart($status)
    // {
        // $sales_ids = $this->zone_sales();
    //     $counts = DB::table('sales')
    //         ->select('users.name as agent_name', DB::raw('COUNT(*) as count'))
    //         ->join('users', 'sales.agent_id', '=', 'users.id')
    //         ->where('sales.delivery_status', $status)

    //         ->when((!$sales_ids), function ($q)  use ($sales_ids) {
    //             return $q->whereIn('id', $sales_ids);
    //         })
    //         ->groupBy('sales.agent_id', 'users.name')
    //         ->get();

    //     $lables = $counts->pluck('agent_name')->toArray();
    //     $rows = $counts->pluck('count')->toArray();

    //     $data = [
    //         'lables' => $lables,
    //         'rows' => $rows,
    //     ];

    //     return response()->json(['data' => $data]);
    // }
    public function agent_chart($status)
    {
        $sales_ids = $this->zone_sales();

        // Query to retrieve zone names and their corresponding sales count
        $data = DB::table('zones')
            ->select('zones.name as zone', DB::raw('COUNT(sales.id) as sales_count'))
            ->join('sale_zones', 'zones.id', '=', 'sale_zones.zone_to')
            ->join('sales', 'sales.id', '=', 'sale_zones.sale_id')
            ->groupBy('zones.id')
            ->orderBy('zones.name')
            ->whereIn('sales.id', $sales_ids)
            ->where('sales.delivery_status', $status)
            ->when(($status == 'Delivered'), function ($q) {
                return $q->whereYear('sales.delivered_on', Carbon::now()->year)
                    ->whereMonth('sales.delivered_on', Carbon::now()->month);
            })->when(($status == 'Returned'), function ($q) {
                return $q->whereYear('sales.returned_on', Carbon::now()->year)
                    ->whereMonth('sales.returned_on', Carbon::now()->month);
            })
            ->get();

        // Prepare the data for the graph
        $labels = $data->pluck('zone'); // Get the zone names as labels
        $rows = $data->pluck('sales_count'); // Get the sales count for each zone

        // Prepare the output structure
        $output = [
            'data' => [
                'lables' => $labels,
                'rows' => $rows,
            ],
        ];

        return response()->json($output);
    }


    public function agents_charts()
    {
        // $sales_ids = $this->zone_sales();

        $sales = Sale::setEagerLoads([])
            ->select(DB::raw('count(id) as count, delivery_status'))
            ->whereIn('delivery_status', ['Delivered', 'Returned', 'In Transit'])
            ->groupBy('delivery_status')
            // ->whereIn('id', $sales_ids)
            ->get();

        $lables = [];
        $rows = [];

        foreach ($sales as $sale) {
            $lables[] = $sale->delivery_status;
            $rows[] = $sale->count;
        }

        $data = [
            'lables' => $lables,
            'rows' => $rows
        ];

        return response()->json(['data' => $data]);
    }
}
