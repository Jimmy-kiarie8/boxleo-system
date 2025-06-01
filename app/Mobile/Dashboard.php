<?php

namespace App\Mobile;

use App\Models\RiderSale;
use App\Models\Sale;
use Carbon\Carbon;

class Dashboard
{
    public function today_orders($ids)
    {
        // $orders = RiderSale::where('rider_id', $rider_id);
        // return $orders->whereDate('created_at', Carbon::today())->count();
        return Sale::whereIn('id', $ids)->count();
    }
    public function today_delivery($ids)
    {
        // $orders = RiderSale::where('rider_id', $rider_id);
        $status = ['Delivered', 'pending approval'];
        return Sale::whereIn('id', $ids)->whereIn('delivery_status', $status)->count();
        // return $orders->whereDate('created_at', Carbon::today())->where('delivery_status', 'Delivered')->count();
    }
    public function today_returns($ids)
    {
        // $orders = RiderSale::where('rider_id', $rider_id);
        $status = ['Returned', 'Return pending'];
        return Sale::whereIn('id', $ids)->whereIn('delivery_status', $status)->count();
        // return  $orders->where('delivery_status', 'Returned')->whereDate('created_at', Carbon::today())->count();
    }
    public function pending($ids)
    {
        $statuses = ['Returned', 'Delivered', 'Return pending', 'pending approval'];
        return Sale::whereIn('id', $ids)->whereDate('created_at', Carbon::today())->whereNotIn('delivery_status', $statuses)->count();
        // $orders = RiderSale::where('rider_id', $rider_id);
        // $statuses = ['Returned', 'Delivered', 'Return pending', 'pending approval'];
        // return $orders->whereDate('created_at', Carbon::today())->whereNotIn('delivery_status', $statuses)->count();
    }
    public function sale_cod($ids)
    {
        $status = ['Delivered', 'pending approval'];
        return Sale::whereIn('id', $ids)->whereIn('delivery_status', $status)->sum('total_price');
    }
    public function delivery_count($ids)
    {
        $statuses = ['Delivered', 'pending approval'];
        return Sale::whereIn('id', $ids)->whereIn('delivery_status', $statuses)->count();
    }
    public function return_count($ids)
    {
        $statuses = ['Returned', 'Return pending'];
        return Sale::whereIn('id', $ids)->whereIn('delivery_status', $statuses)->count();
    }
    public function order_count($ids)
    {
        return Sale::whereIn('id', $ids)->count();
    }
    public function pending_count($ids)
    {
        $status = ['Returned', 'Delivered', 'Return pending', 'pending approval'];
        return Sale::whereIn('id', $ids)->whereNotIn('delivery_status', $status)->count();
    }
}
