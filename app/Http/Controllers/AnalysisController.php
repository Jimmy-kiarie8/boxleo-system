<?php

namespace App\Http\Controllers;

use App\Models\Call;
use App\Models\Client;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SellerService;
use App\Models\Service;
use App\Models\User;
use App\Models\Zone;
use App\Rider;
use App\Scopes\OrderScope;
use App\Seller;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log as FacadesLog;
use Log;

class AnalysisController extends Controller
{


    public function index()
    {
        $users = User::all();
        // return Inertia::render('Reports/index', [
        //     'users' => $users
        // ]);
    }


    public function analysis(Request $request, $report)
    {
        return $this->{$report}($request);
    }


    public function getHeader($data)
    {
        $headers = [];

        if (!empty($data)) {
            $firstRow = $data->first(); // Assuming it's a collection

            if ($firstRow) {
                foreach ($firstRow->toArray() as $key => $value) {
                    // Check if the key is not an internal Laravel attribute (starts with an underscore)
                    if (!Str::startsWith($key, '_')) {
                        $headers[] = [
                            'title' => ucfirst(str_replace('_', ' ', $key)),
                            'key' => $key,
                        ];
                    }
                }
            }
        }

        return  $headers;
    }

    public function generateMerchantPerformanceReport(Request $request)
    {

        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfday();
        $sellerIds = $request->seller_id;

        $deliveryStatuses = [
            'Pending', 'Processing for Dispatch', 'Dispatched', 'Cancelled', 'Scheduled',
            'Delivered', 'Returned', 'Out of stock', 'Partially Delivered', 'Refund',
            'Inprogress', 'Repeated order'
        ];

        // Fetch sellers
        $sellers = Seller::where('active', true)
            ->when(!empty($sellerIds), function ($q) use ($sellerIds) {
                return $q->whereIn('id', $sellerIds);
            })
            ->select(['id', 'name'])
            ->get();

        // Fetch all relevant sales data in a single query
        $salesData = Sale::select('id', 'seller_id', 'delivery_status')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('seller_id', $sellers->pluck('id'))
            ->get()
            ->groupBy('seller_id');

        $reportData = [];
        $labels = [];
        $del_rows = [];
        $ret_rows = [];

        foreach ($sellers as $seller) {
            $labels[] = $seller->name;
            $sales = $salesData[$seller->id] ?? collect();
            $total = $sales->count();

            $salesCountByStatus = $this->calculateSalesCountByStatus($sales, $deliveryStatuses);

            $del_rows[] = $salesCountByStatus['Delivered'];
            $ret_rows[] = $salesCountByStatus['Returned'];

            $reportData[] = [
                'seller_name' => $seller->name,
                'total' => $total,
                'sales_count_by_status' => $salesCountByStatus,
            ];
        }

        $headers = $this->getHeaders();

        $delivery_chart = [
            'labels' => $labels,
            'rows' => $del_rows,
        ];

        $return_chart = [
            'labels' => $labels,
            'rows' => $ret_rows,
        ];

        return response()->json([
            'data' => $reportData,
            'headers' => $headers,
            'delivery_chart' => $delivery_chart,
            'return_chart' => $return_chart
        ]);
    }

    private function calculateSalesCountByStatus($sales, $deliveryStatuses)
    {
        $salesCountByStatus = array_fill_keys($deliveryStatuses, 0);

        foreach ($sales as $sale) {
            $status = $sale->delivery_status;

            if ($status === 'Will call back' || $status === 'Unreachable') {
                $salesCountByStatus['Pending']++;
            } else {
                $salesCountByStatus[$status] = ($salesCountByStatus[$status] ?? 0) + 1;
            }
        }

        return $salesCountByStatus;
    }

    private function getHeaders()
    {
        return [
            ['value' => 'seller_name', 'text' => 'Merchant Name'],
            ['value' => 'total', 'text' => 'Total Orders'],
            ['value' => 'sales_count_by_status.Scheduled', 'text' => 'Scheduled'],
            ['value' => 'sales_count_by_status.Processing for Dispatch', 'text' => 'Processing for Dispatch'],
            ['value' => 'sales_count_by_status.Pending', 'text' => 'Pending'],
            ['value' => 'sales_count_by_status.Partially Delivered', 'text' => 'Partially Delivered'],
            ['value' => 'sales_count_by_status.Cancelled', 'text' => 'Cancelled'],
            ['value' => 'sales_count_by_status.Dispatched', 'text' => 'Dispatched'],
            ['value' => 'sales_count_by_status.Delivered', 'text' => 'Delivered'],
            ['value' => 'sales_count_by_status.Returned', 'text' => 'Returned'],
            ['value' => 'sales_count_by_status.Out of stock', 'text' => 'Out of stock'],
            ['value' => 'sales_count_by_status.Repeated order', 'text' => 'Repeated order'],
            ['value' => 'sales_count_by_status.Inprogress', 'text' => 'Inprogress'],
        ];
    }

    public function generateMerchantPerformanceReport1(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $sellers = $request->seller_id;
        //
        // Define delivery statuses
        $deliveryStatuses = ['Pending', 'Processing for Dispatch', 'Dispatched', 'Cancelled', 'Scheduled', 'Delivered', 'Returned', 'Out of stock', 'Partially Delivered', 'Refund', 'Inprogress', 'Repeated order'];

        // Fetch sellers
        $sellers = Seller::when(!empty($sellers), function ($q) use ($sellers) {
            return $q->whereIn('id', $sellers);
        })->where('active', true)->select(['id', 'name'])->get();

        // Initialize report data array
        $reportData = [];
        $deliveredCount = 0;
        $returnCount = 0;

        // Iterate through sellers
        foreach ($sellers as $seller) {
            // Initialize array to store sales count for each delivery status
            $salesCountByStatus = [];
            $labels[] = $seller->name;

            // Fetch sales for the seller
            // $sales = Sale::whereBetween('created_at', [$startDate, $endDate])->where('seller_id', $seller->id)->get();

            $sales = Sale::setEagerLoads([])->select('id', 'seller_id', 'created_at', 'delivery_status')->whereBetween('created_at', [$startDate, $endDate])->where('seller_id', $seller->id)->get();
            $total = $sales->count();
            // Count sales for each delivery status
            foreach ($deliveryStatuses as $status) {

                $count = $sales->where('delivery_status', $status)->count();

                if ($status == 'Delivered') {
                    $deliveredCount = $count;
                } elseif ($status == 'Returned') {
                    $returnCount = $count;
                }

                if ($status === 'Pending') {
                    $salesCountByStatus[$status] = $sales->whereIn('delivery_status', ['Will call back', 'Unreachable'])->count();
                    $count = $sales->where('delivery_status', $status)->count();
                } else {
                    $salesCountByStatus[$status] = $sales->where('delivery_status', $status)->count();
                }
                // $total += $count;
            }


            // Sum sales count for all delivery statuses
            // $totalSalesCount = array_sum($salesCountByStatus['Delivered']);

            // Add total sales count to rows array
            $del_rows[] = $deliveredCount;
            $ret_rows[] = $returnCount;

            // Prepare data for each seller
            $reportData[] = [
                'seller_name' => $seller->name,
                'total' => $total,
                'sales_count_by_status' => $salesCountByStatus,
            ];
        }

        // $deliveryStatuses = ['Will call back', 'Processing for Dispatch','Dispatched', 'Cancelled', 'Unreachable', 'Scheduled', 'Delivered', 'Returned', 'Refund', 'Out of stock', 'Partially Delivered', 'To be returned'];

        $headers = [
            [
                'value' => 'seller_name',
                'text' => 'Merchant Name'
            ],
            [
                'value' => 'total',
                'text' => 'Total Orders'
            ],
            [
                'value' => 'sales_count_by_status.Scheduled',
                'text' => 'Scheduled'
            ],
            [
                'value' => 'sales_count_by_status.Processing for Dispatch',
                'text' => 'Processing for Dispatch'
            ],
            [
                'value' => 'sales_count_by_status.Pending',
                'text' => 'Pending'
            ],
            [
                'value' => 'sales_count_by_status.Partially Delivered',
                'text' => 'Partially Delivered'
            ],
            [
                'value' => 'sales_count_by_status.Cancelled',
                'text' => 'Cancelled'
            ],
            [
                'value' => 'sales_count_by_status.Dispatched',
                'text' => 'Dispatched'
            ],
            [
                'value' => 'sales_count_by_status.Delivered',
                'text' => 'Delivered'
            ],
            [
                'value' => 'sales_count_by_status.Returned',
                'text' => 'Returned'
            ],
            [
                'value' => 'sales_count_by_status.Out of stock',
                'text' => 'Out of stock'
            ],
            [
                'value' => 'sales_count_by_status.Partially Delivered',
                'text' => 'Partially Delivered'
            ],
            [
                'value' => 'sales_count_by_status.Repeated order',
                'text' => 'Repeated order'
            ],
            [
                'value' => 'sales_count_by_status.Inprogress',
                'text' => 'Inprogress'
            ]
        ];


        // Construct data array
        $delivery_chart = [
            'labels' => $labels,
            'rows' => $del_rows,
        ];


        $return_chart = [
            'labels' => $labels,
            'rows' => $ret_rows,
        ];
        // Pass report data to view
        // return view('agent-performance-report', compact('reportData', 'deliveryStatuses'));

        // return $reportData;

        // $headers = $this->getHeader($reportData);

        return response()->json(['data' => $reportData, 'headers' => $headers, 'delivery_chart' => $delivery_chart, 'return_chart' => $return_chart]);
    }


    public function generateAgentPerformanceReport(Request $request)
    {
        // $startDate = $request->start_date;
        // $endDate = $request->end_date;
        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfday();
        $agents = $request->agent_id;
        //
        // Define delivery statuses
        $deliveryStatuses = ['Pending', 'Awaiting Dispatch', 'In Transit', 'Cancelled', 'Scheduled', 'Delivered', 'Returned', 'Out Of Stock', 'Reschedule', 'Undispatched'];

        // Fetch agents
        $agents = User::role(['Call center'])->when(!empty($agents), function ($q) use ($agents) {
            return $q->whereIn('id', $agents);
        })->where('active', true)->select(['id', 'name', 'ou_id', 'on_break', 'status', 'call_active'])->where('ou_id', Auth::user()->ou_id)->get();

        // Initialize report data array
        $reportData = [];
        $deliveredCount = 0;
        $returnCount = 0;

        // Iterate through agents
        foreach ($agents as $agent) {
            // Initialize array to store sales count for each delivery status
            $salesCountByStatus = [];
            $labels[] = $agent->name;

            // Fetch sales for the agent
            // $sales = Sale::whereBetween('created_at', [$startDate, $endDate])->where('agent_id', $agent->id)->get();

            $sales = Sale::withoutGlobalScope(OrderScope::class)->setEagerLoads([])
                ->select('id', 'agent_id', 'created_at', 'delivery_status')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->where('agent_id', $agent->id)->get();

            $total = 0;
            // Count sales for each delivery status
            foreach ($deliveryStatuses as $status) {
                if ($status == 'Returned') {
                    $statuses = ['Returned', 'Awaiting Return', 'Return Received'];
                    $count = $sales->whereIn('delivery_status', $statuses)->count();

                    $salesCountByStatus[$status] = $count;
                } elseif ($status == 'In Transit') {
                    $statuses = ['In Transit', 'Dispatched'];
                    $count = $sales->whereIn('delivery_status', $statuses)->count();
                    $salesCountByStatus[$status] = $count;
                } elseif ($status == 'Scheduled') {
                    $statuses = ['Pending', 'Awaiting Dispatch', 'In Transit', 'Cancelled', 'Scheduled', 'Delivered', 'Returned', 'Reschedule', 'Undispatched'];
                    $count = $sales->whereIn('delivery_status', $statuses)->count();
                    $salesCountByStatus[$status] = $count;
                } else {
                    $count = $sales->where('delivery_status', $status)->count();
                    $salesCountByStatus[$status] = $sales->where('delivery_status', $status)->count();
                }
                if ($status == 'Delivered') {
                    $deliveredCount = $count;
                } elseif ($status == 'Returned') {
                    $returnCount = $count;
                }
                $total += $count;
            }


            // Sum sales count for all delivery statuses
            // $totalSalesCount = array_sum($salesCountByStatus['Delivered']);

            // Add total sales count to rows array
            $del_rows[] = $deliveredCount;
            $ret_rows[] = $returnCount;

            // Prepare data for each agent
            $reportData[] = [
                'agent_name' => $agent->name,
                'total' => $total,
                'sales_count_by_status' => $salesCountByStatus,
            ];
        }

        $headers = [
            [
                'value' => 'agent_name',
                'text' => 'Agent Name'
            ],
            [
                'value' => 'total',
                'text' => 'Total Orders'
            ],
            [
                'value' => 'sales_count_by_status.Scheduled',
                'text' => 'Scheduled'
            ],
            [
                'value' => 'sales_count_by_status.Awaiting Dispatch',
                'text' => 'Awaiting Dispatch'
            ],
            [
                'value' => 'sales_count_by_status.Cancelled',
                'text' => 'Cancelled'
            ],
            [
                'value' => 'sales_count_by_status.Pending',
                'text' => 'Pending'
            ],
            [
                'value' => 'sales_count_by_status.In Transit',
                'text' => 'In Transit'
            ],
            [
                'value' => 'sales_count_by_status.Delivered',
                'text' => 'Delivered'
            ],
            [
                'value' => 'sales_count_by_status.Returned',
                'text' => 'Returned'
            ],
            [
                'value' => 'sales_count_by_status.Undispatched',
                'text' => 'Undispatched'
            ],
            [
                'value' => 'sales_count_by_status.Reschedule',
                'text' => 'Reschedule'
            ],
            [
                'value' => 'sales_count_by_status.Out Of Stock',
                'text' => 'Out Of Stock'
            ]
        ];


        // Construct data array
        $delivery_chart = [
            'labels' => $labels,
            'rows' => $del_rows,
        ];


        $return_chart = [
            'labels' => $labels,
            'rows' => $ret_rows,
        ];
        // Pass report data to view
        // return view('agent-performance-report', compact('reportData', 'deliveryStatuses'));

        // return $reportData;

        // $headers = $this->getHeader($reportData);

        return response()->json(['data' => $reportData, 'headers' => $headers, 'delivery_chart' => $delivery_chart, 'return_chart' => $return_chart]);
    }

    public function generateRiderPerformanceReport(Request $request)
    {
        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfday();
        $vendorId = $request->seller_id;


        // Define delivery statuses
        $deliveryStatuses = ['Delivered', 'Returned', 'In Transit'];
        $date = [$startDate, $endDate];
        $rider_id = $request->rider_id;
        // Fetch riders
        $riders = Rider::with(['sales' => function ($q) use ($date) {
            $q->whereBetween('dispatched_on', $date);
        }])->when(!empty($rider_id), function ($q) use ($rider_id) {
            return $q->whereIn('id', $rider_id);
        })->get();


        // Initialize report data array
        $reportData = [];

        // Initialize arrays for charts
        $deliveredRows = [];
        $returnRows = [];

        // Iterate through riders
        foreach ($riders as $rider) {
            // Initialize array to store sales count for each delivery status
            $salesCountByStatus = [];

            // Fetch sales for the rider
            // $sales = Sale::setEagerLoads([])->with(['riders'])->whereBetween('dispatched_on', [$startDate, $endDate])
            //     ->whereHas('riders', function ($query) use ($rider) {
            //         $query->where('rider_id', $rider->id);
            //     })
            //     ->get();

            $sales = $rider->sales;
            $total = 0;
            // Count sales for each delivery status
            foreach ($deliveryStatuses as $status) {
                $count = $sales->where('delivery_status', $status)->whereIn('seller_id', $vendorId)->count();
                $salesCountByStatus[$status] = $count;
                $total += $count;
            }

            // Add data for the rider to the report data array
            $reportData[] = [
                'rider_name' => $rider->name,
                'total' => $total,
                'sales_count_by_status' => $salesCountByStatus,
            ];

            // Add data for charts
            $deliveredRows[] = $salesCountByStatus['Delivered'] ?? 0;
            $returnRows[] = $salesCountByStatus['Returned'] ?? 0;
        }

        // Define headers for the report
        $headers = [
            [
                'value' => 'rider_name',
                'text' => 'Rider Name'
            ],
            [
                'value' => 'total',
                'text' => 'Total'
            ],
            [
                'value' => 'sales_count_by_status.Returned',
                'text' => 'Returned'
            ],
            [
                'value' => 'sales_count_by_status.Delivered',
                'text' => 'Delivered'
            ],
            [
                'value' => 'sales_count_by_status.In Transit',
                'text' => 'In Transit'
            ],
        ];

        // Construct data arrays for charts
        $delivery_chart = [
            'labels' => $this->getRiderNames($riders),
            'rows' => $deliveredRows,
        ];

        $return_chart = [
            'labels' => $this->getRiderNames($riders),
            'rows' => $returnRows,
        ];

        // Return the report data and charts data as JSON
        return response()->json([
            'data' => $reportData,
            'headers' => $headers,
            'delivery_chart' => $delivery_chart,
            'return_chart' => $return_chart,
        ]);
    }

    // Helper function to get rider names
    private function getRiderNames($riders)
    {
        return $riders->pluck('name')->toArray();
    }



    public function generateLeadStatusReport(Request $request)
    {

        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfday();
        $date = [
            $startDate,
            $endDate
        ];
        $data = Sale::select(
            'status',
            DB::raw('COUNT(*) as count')
        )
            ->groupBy('status')
            ->paginate(100);


        $headers = $this->getHeader($data);
        return response()->json(['data' => $data, 'headers' => $headers]);
    }

    public function generateSystemCallsTrendReport(Request $request)
    {

        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfday();
        $date = [
            $startDate,
            $endDate
        ];
        $data = Call::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as date'),
            DB::raw('COUNT(*) as count')
        )
            ->groupBy('date')
            ->orderBy('date')
            ->paginate(100);


        $headers = $this->getHeader($data);
        return response()->json(['data' => $data, 'headers' => $headers]);
    }

    public function generateLeadsConversionComparisonReport(Request $request)
    {

        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfday();
        $date = [
            $startDate,
            $endDate
        ];
        $data = User::select(
            'users.name',
            DB::raw('COUNT(CASE WHEN sales.status = "converted" THEN 1 ELSE NULL END) as converted_count'),
            DB::raw('COUNT(sales.id) as total_count')
        )
            ->leftJoin('leads', 'users.id', '=', 'sales.agent_id')
            ->groupBy('users.id', 'users.name')
            ->paginate(100);


        $headers = $this->getHeader($data);
        return response()->json(['data' => $data, 'headers' => $headers]);
    }

    public function generateFirstCallResolutionRateReport(Request $request)
    {

        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfday();
        $date = [
            $startDate,
            $endDate
        ];
        $data = User::select(
            'users.name',
            DB::raw('COUNT(CASE WHEN calls.status = "completed" AND sales.status = "converted" THEN 1 ELSE NULL END) as resolved_count'),
            DB::raw('COUNT(CASE WHEN calls.status = "completed" THEN 1 ELSE NULL END) as completed_count')
        )
            ->leftJoin('calls', 'users.id', '=', 'calls.agent_id')
            ->leftJoin('leads', 'users.id', '=', 'sales.agent_id')
            ->groupBy('users.id', 'name')
            ->paginate(100);


        $headers = $this->getHeader($data);
        return response()->json(['data' => $data, 'headers' => $headers]);
    }

    public function generateAverageCallTimeReport(Request $request)
    {

        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfday();
        $date = [
            $startDate,
            $endDate
        ];
        $data = User::select(
            'name',
            DB::raw('AVG(calls.durationInSeconds) as average_call_time')
        )
            ->leftJoin('calls', 'users.id', '=', 'calls.agent_id')
            ->groupBy('users.id', 'name')
            ->paginate(100);


        $headers = $this->getHeader($data);
        return response()->json(['data' => $data, 'headers' => $headers]);
    }

    public function generateCallAbandonmentRateReport(Request $request)
    {

        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfday();
        $date = [
            $startDate,
            $endDate
        ];
        $data = User::select(
            'name',
            DB::raw('COUNT(CASE WHEN calls.status = "missed" THEN 1 ELSE NULL END) as missed_count'),
            DB::raw('COUNT(CASE WHEN calls.status = "initiated" THEN 1 ELSE NULL END) as initiated_count')
        )
            ->leftJoin('calls', 'users.id', '=', 'calls.agent_id')
            ->groupBy('users.id', 'name')
            ->paginate(100);


        $headers = $this->getHeader($data);
        return response()->json(['data' => $data, 'headers' => $headers]);
    }

    public function generateAgentActivityOverTimeReport(Request $request)
    {

        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfday();
        $date = [
            $startDate,
            $endDate
        ];
        $data = Call::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as date'),
            DB::raw('COUNT(*) as count')
        )
            ->where('agent_id', Auth::id())
            ->groupBy('date')
            ->orderBy('date')
            ->paginate(100);


        $headers = $this->getHeader($data);
        return response()->json(['data' => $data, 'headers' => $headers]);
    }

    public function generateTotalAmountSpentReport(Request $request)
    {

        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfday();
        $date = [
            $startDate,
            $endDate
        ];
        $data = User::select(
            'name',
            'amount_spent'
        )
            ->paginate(100);


        $headers = $this->getHeader($data);
        return response()->json(['data' => $data, 'headers' => $headers]);
    }

    public function generateOverallSystemSummaryReport(Request $request)
    {

        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfday();
        $date = [
            $startDate,
            $endDate
        ];
        $totalCompletedCalls = Call::where('status', 'completed')->whereBetween('created_at', $date)->count();
        $totalMissedCalls = Call::where('status', 'missed')->whereBetween('created_at', $date)->count();
        $totalLeadsContacted = Sale::where('status', 'contacted')->whereBetween('created_at', $date)->count();
        $totalLeadsConverted = Sale::where('status', 'converted')->whereBetween('created_at', $date)->count();

        $data = [
            'Total Completed Calls' => $totalCompletedCalls,
            'Total Missed Calls' => $totalMissedCalls,
            'Total Leads Contacted' => $totalLeadsContacted,
            'Total Leads Converted' => $totalLeadsConverted,
        ];

        $headers = [
            [
                'title' => 'Total Completed Calls',
                'key' => 'Total Completed Calls',
            ],
            [
                'title' => 'Total Leads Contacted',
                'key' => 'Total Leads Contacted',
            ],
            [
                'title' => 'Total Leads Converted',
                'key' => 'Total Leads Converted',
            ],
            [
                'title' => 'Total Missed Calls',
                'key' => 'Total Missed Calls',
            ],
        ];

        // $headers = $this->getHeader($data);
        return response()->json(['data' => [$data], 'headers' => $headers]);
    }

    public function  perfomance(Request $request)
    {
        $report = new ReportService;
        return $report->generateReport($request->all());

        // return response()->json(['data' => [$data], 'headers' => $headers]);
    }


    public function  merchantPerfomance(Request $request)
    {
        $report = new ReportService;
        return $report->merchantReport($request->all());

        // return response()->json(['data' => [$data], 'headers' => $headers]);
    }

    public function  agents(Request $request)
    {

        $user = $request->user();

        $agents = User::setEagerLoads([])
            ->select(['id', 'name', 'ou_id', 'on_break', 'status', 'call_active']) // Select only necessary columns
            ->where('active', true)
            ->where('ou_id', $user->ou_id)
            ->role(['Call center', 'Call center admin', 'Follow UP'])
            ->get();

        return $agents;
    }
}
