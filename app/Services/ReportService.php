<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\User;
use App\Scopes\OrderScope;
use App\Seller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ReportService
{

    public function generateReport($data)
    {


        $startDate = Carbon::parse($data['start_date'])->startOfDay();
        $endDate = Carbon::parse($data['end_date'])->endOfday();

        $agents = $data['agent_id'];
        //
        // Define delivery statuses
        $deliveryStatuses = ['Pending', 'Cancelled', 'Scheduled', 'Delivered', 'Returned', 'Buyout'];

        // Fetch agents
        $agents = User::when(!empty($agents), function ($q) use ($agents) {
            return $q->whereIn('id', $agents);
        })->where('active', true)->select(['id', 'name', 'ou_id', 'on_break', 'status', 'call_active'])->where('ou_id', Auth::user()->ou_id)->role(['Call center', 'Call center admin', 'Follow UP'])->get();


        // $agents = User::where('can_receive_orders', true)->when(!empty($agents), function ($q) use ($agents) {
        //     return $q->whereIn('id', $agents);
        // })->where('active', true)->select(['id', 'name', 'ou_id', 'on_break', 'status', 'call_active'])->where('ou_id', Auth::user()->ou_id)->get();

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
            $sales = Sale::withoutGlobalScope(OrderScope::class)->setEagerLoads([])->select('id', 'agent_id', 'created_at', 'delivery_status')->whereBetween('created_at', [$startDate, $endDate])->where('agent_id', $agent->id)->get();

            $total_orders = $sales->count();

            if ($total_orders > 0) {
                $total_count = $total_orders;
                $total = 0;

                $out_orders = Sale::withoutGlobalScope(OrderScope::class)->where('delivery_status', '!=', 'Out of stock')->whereBetween('created_at', [$startDate, $endDate])->where('agent_id', $agent->id)->count();

                // Count sales for each delivery status
                foreach ($deliveryStatuses as $status) {
                    $total_orders = $total_count;

                    if ($status === 'Scheduled') {
                        $statuses = ['In Transit', 'Scheduled', 'Awaiting Dispatch', 'Delivered', 'Awaiting Return', 'Returned', 'Dispatched', 'Rescheduled', 'Undispatched', 'Return Received'];
                        // $statuses = ['In Transit', 'Scheduled', 'Awaiting Dispatch', 'Delivered', 'Awaiting Return', 'Returned', 'Rescheduled'];
                        $count = $sales->whereIn('delivery_status', $statuses)->count();
                        $total_orders = $out_orders;
                    } elseif ($status === 'Delivered') {
                        // $statuses = ['In Transit', 'Scheduled', 'Awaiting Dispatch', 'Delivered', 'Awaiting Return', 'Returned', 'Dispatched', 'Rescheduled', 'Undispatched', 'Return Received'];
                        $statuses = ['Cancelled', 'Pending' . 'Out of stock'];
                        $count = $sales->where('delivery_status', 'Delivered')->count();
                        // $total_orders =  Sale::whereNotIn('delivery_status', $statuses)->whereBetween('created_at', [$startDate, $endDate])->where('agent_id', $agent->id)->count();

                        $statuses = ['In Transit', 'Scheduled', 'Awaiting Dispatch', 'Delivered', 'Awaiting Return', 'Returned', 'Dispatched', 'Rescheduled', 'Undispatched', 'Return Received'];
                        // $statuses = ['In Transit', 'Scheduled', 'Awaiting Dispatch', 'Delivered', 'Awaiting Return', 'Returned', 'Rescheduled'];
                        $total_orders = $sales->whereIn('delivery_status', $statuses)->count();
                        $deliveredCount = $count;
                    } elseif ($status === 'Buyout') {
                        $count = $sales->where('delivery_status', 'Delivered')->count();
                        $total_orders = $out_orders;
                    } elseif ($status === 'Returned') {
                        $statuses = ['Returned', 'Awaiting Return', 'Return Received'];

                        $count = $sales->whereIn('delivery_status', $statuses)->count();
                        $total_orders = $out_orders;
                        $returnCount = $count;
                    } else {
                        $total_orders = $out_orders;
                        $count = $sales->where('delivery_status', $status)->count();
                    }
                    if ($total_orders > 0) {
                        $salesCountByStatus[$status] = number_format(($count / $total_orders) * 100, 2) . '%';
                    } else {
                        $salesCountByStatus[$status] = 0 . '%';
                    }
                    // if ($status != 'Buyout') {
                    //     if ($status === 'Scheduled') {
                    //         $statuses = ['In Transit', 'Scheduled', 'Awaiting Dispatch', 'Awaiting Return'];
                    //         $count = $sales->whereIn('delivery_status', $statuses)->count();
                    //     }
                    //     $total += $count;
                    // }
                }


                // Sum sales count for all delivery statuses
                // $totalSalesCount = array_sum($salesCountByStatus['Delivered']);

                // Add total sales count to rows array
                $del_rows[] = $deliveredCount;
                $ret_rows[] = $returnCount;

                // Prepare data for each agent
                $reportData[] = [
                    'seller_name' => $agent->name,
                    'total' => $total_count,
                    'sales_count_by_status' => $salesCountByStatus,
                ];
            }
        }

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
                'text' => 'Scheduling Rate '
            ],
            [
                'value' => 'sales_count_by_status.Buyout',
                'text' => 'Buyout Rate'
            ],
            [
                'value' => 'sales_count_by_status.Cancelled',
                'text' => 'Cancellation Rate'
            ],
            [
                'value' => 'sales_count_by_status.Pending',
                'text' => 'Pending Rate'
            ],
            [
                'value' => 'sales_count_by_status.Delivered',
                'text' => 'Delivery Rate'
            ],
            [
                'value' => 'sales_count_by_status.Returned',
                'text' => 'Return Rate '
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

        return response()->json(['data' => $reportData, 'headers' => $headers, 'delivery_chart' => $delivery_chart, 'return_chart' => $return_chart]);
    }

    public function merchantReport($data)
    {

        $startDate = Carbon::parse($data['start_date'])->startOfDay();
        $endDate = Carbon::parse($data['end_date'])->endOfday();

        $deliveryStatuses = ['Pending', 'Cancelled', 'Scheduled', 'Delivered', 'Returned', 'Buyout'];
        $seller_ids = $data['seller_id'] ?? [];
    
        // Fetch sellers with a single query
        $sellers = Seller::where('active', true)
            ->when(!empty($seller_ids), function ($q) use ($seller_ids) {
                return $q->whereIn('id', $seller_ids);
            })
            ->select(['id', 'name'])
            ->get();
    
        // Fetch all relevant sales data in a single query
        $salesData = Sale::select('id', 'seller_id', 'created_at', 'delivery_status')
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
            $total_count = $sales->count();
    
            if ($total_count > 0) {
                $out_orders = $sales->where('delivery_status', '!=', 'Out of stock')->count();
                $salesCountByStatus = $this->calculateSalesCountByStatus($sales, $deliveryStatuses, $out_orders);
    
                $del_rows[] = $salesCountByStatus['Delivered']['count'];
                $ret_rows[] = $salesCountByStatus['Returned']['count'];
    
                $reportData[] = [
                    'agent_name' => $seller->name,
                    'total' => $total_count,
                    'sales_count_by_status' => array_map(function($item) {
                        return $item['percentage'];
                    }, $salesCountByStatus),
                ];
            }
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
    
    private function calculateSalesCountByStatus($sales, $deliveryStatuses, $out_orders)
    {
        $result = [];
        $scheduledStatuses = ['In Transit', 'Scheduled', 'Awaiting Dispatch', 'Delivered', 'Awaiting Return', 'Returned', 'Dispatched', 'Rescheduled', 'Undispatched', 'Return Received'];
        $returnedStatuses = ['Returned', 'Awaiting Return', 'Return Received'];
    
        foreach ($deliveryStatuses as $status) {
            switch ($status) {
                case 'Scheduled':
                    $count = $sales->whereIn('delivery_status', $scheduledStatuses)->count();
                    $total = $out_orders;
                    break;
                case 'Delivered':
                    $count = $sales->where('delivery_status', 'Delivered')->count();
                    $total = $sales->whereIn('delivery_status', $scheduledStatuses)->count();
                    break;
                case 'Buyout':
                    $count = $sales->where('delivery_status', 'Delivered')->count();
                    $total = $out_orders;
                    break;
                case 'Returned':
                    $count = $sales->whereIn('delivery_status', $returnedStatuses)->count();
                    $total = $out_orders;
                    // $total = $sales->whereIn('delivery_status', $scheduledStatuses)->count();
                    break;
                default:
                    $count = $sales->where('delivery_status', $status)->count();
                    $total = $out_orders;
            }
    
            $result[$status] = [
                'count' => $count,
                'percentage' => $total > 0 ? number_format(($count / $total) * 100, 2) . '%' : '0%'
            ];
        }
    
        return $result;
    }
    
    private function getHeaders()
    {
        return [
            ['value' => 'agent_name', 'text' => 'Agent Name'],
            ['value' => 'total', 'text' => 'Total Orders'],
            ['value' => 'sales_count_by_status.Scheduled', 'text' => 'Scheduling Rate'],
            ['value' => 'sales_count_by_status.Buyout', 'text' => 'Buyout Rate'],
            ['value' => 'sales_count_by_status.Cancelled', 'text' => 'Cancellation Rate'],
            ['value' => 'sales_count_by_status.Pending', 'text' => 'Pending Rate'],
            ['value' => 'sales_count_by_status.Delivered', 'text' => 'Delivery Rate'],
            ['value' => 'sales_count_by_status.Returned', 'text' => 'Return Rate'],
        ];
    }
}
