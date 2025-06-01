<?php

namespace App\Services;

use App\Models\DailyStats;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductReportService
{
    // Daily Product Status Report
    public function getDailyProductStatusReport($date)
    {
        $query = DailyStats::with(['product' => function ($query) {
            $query->setEagerLoads([])->select('id', 'product_name');
        }])->where('date', $date);
    
        if (Auth::guard('seller')->check()) {
            $vendorId = Auth::guard('seller')->id();
            $query->whereHas('product', function ($query) use ($vendorId) {
                $query->where('vendor_id', $vendorId);
            });
        }
    
        return $query->get();
    }
    

    public function getProductSalesReport($startDate, $endDate)
    {
        // DB::enableQueryLog();

        return  $products = Product::withCount(['sales' => function ($query) use ($startDate, $endDate) {
            $query->setEagerLoads([])->whereBetween('sales.created_at', [$startDate, $endDate]);
        }])
            ->setEagerLoads([])
            ->with(['sales' => function ($query) use ($startDate, $endDate) {
                $query->setEagerLoads([])->whereBetween('sales.created_at', [$startDate, $endDate]);
            }])
            ->paginate(1000)
            ->map(function ($product) {
                $totalSales = $product->sales_count;
                if ($totalSales == 0) {
                    return null;
                }
                $totalQuantitySold = $product->sales->sum('quantity');
                $revenueGenerated = $product->sales->sum('price');

                return [
                    'product_id' => $product->id,
                    'product_name' => $product->product_name,
                    'sku_no' => $product->sku_no,
                    'total_sales' => $totalSales,
                    'total_quantity_sold' => $totalQuantitySold,
                    'revenue_generated' => $revenueGenerated,
                ];
            })
            ->filter()
            ->sortByDesc('total_sales')
            ->values();

        // Log::debug(DB::getQueryLog());

        return $products;
    }
    
    public function getMonthlyProductMovementReport($year, $month)
    {
        $query = DailyStats::with(['product' => function ($query) {
            $query->setEagerLoads([])->select('id', 'product_name');
        }])
        ->whereYear('date', $year)
        ->whereMonth('date', $month);
    
        if (Auth::guard('seller')->check()) {
            $vendorId = Auth::guard('seller')->id();
            $query->whereHas('product', function ($query) use ($vendorId) {
                $query->where('vendor_id', $vendorId);
            });
        }
    
        $stats = $query->get()
            ->groupBy('product_id')
            ->map(function ($stats, $productId) {
                $productName = $stats->first()->product ? $stats->first()->product->product_name : 'Unknown';
    
                return [
                    'product_id' => $productId,
                    'product_name' => $productName,
                    'total_delivered' => $stats->sum('delivered'),
                    'total_dispatched' => $stats->sum('dispatched'),
                    'total_returned' => $stats->sum('returned'),
                    'total_scheduled' => $stats->sum('scheduled'),
                ];
            })
            ->values();
    
        return $stats;
    }
    

    // Product Availability Report
    public function getProductAvailabilityReport()
    {
        return Product::with('bins')->get()->map(function ($product) {
            $totalQuantity = $product->bins->sum('quantity');
            $binLocations = $product->bins->map(function ($bin) {
                return [
                    'bin_id' => $bin->id,
                    'row' => $bin->row,
                    'bay' => $bin->bay,
                    'level' => $bin->level,
                    'quantity' => $bin->quantity,
                ];
            });

            return [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'total_quantity' => $totalQuantity,
                'bin_locations' => $binLocations,
            ];
        });
    }


    public function getProductPerformanceReport($startDate, $endDate)
    {
        return Product::with(['sales' => function ($query) use ($startDate, $endDate) {
            $query->setEagerLoads([])->whereBetween('sales.created_at', [$startDate, $endDate]);
        }, 'dailyStats' => function ($query) use ($startDate, $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        }])
        ->whereHas('sales', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('sales.created_at', [$startDate, $endDate]);
        })
        ->paginate(1000)
        ->map(function ($product) {
            $totalSales = $product->sales->count();
            $totalReturns = $product->dailyStats->sum('returned');
            $averageDailySales = ($product->dailyStats->count() > 0) ?  $totalSales / $product->dailyStats->count() : 0;
            $currentStock = ($product->dailyStats->last()) ? $product->dailyStats->last()->closing_count : 0;
    
            return [
                'product_id' => $product->id,
                'product_name' => $product->product_name,
                'total_sales' => $totalSales,
                'total_returns' => $totalReturns,
                'average_daily_sales' => $averageDailySales,
                'current_stock' => $currentStock,
            ];
        })
        ->filter(); // Remove null values
    }
    
}
