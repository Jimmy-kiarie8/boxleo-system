<?php

namespace App\Models;

use App\Models\Warehouse\Bin;
use App\Models\Warehouse\ProductBin;
use App\Models\Warehouse\Warehouse;
use App\Scopes\StatsScope;
use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DailyStats extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'delivered', 'dispatched', 'scheduled', 'uploaded', 'returned', 'pedding', 'closing_count', 'starting_count', 'date', 'ou_id'];


    /**
     * Get the product that owns the DailyStats
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    // public function product(): BelongsTo
    // {
    //     return $this->belongsTo(Product::class, 'product_id');
    // }


    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function getCreatedAtAttribute($value)
    {
        $timezone = optional(auth()->user())->timezone ?? config('app.timezone');
        return Carbon::parse($value)->timezone($timezone)->format('D d M Y H:i');
    }

    public static function booted()
    {
        static::addGlobalScope(new StatsScope);
    }

    public function delivered_count($products)
    {
        $ou_id = (Auth::check()) ? Auth::user()->ou_id : 1;
        foreach ($products as $key => $product) {
            $quantity = $product['pivot']['quantity'];
            $daily_stats = DailyStats::where('ou_id',   $ou_id)->where('product_id', $product->id)
                ->where('created_at', '>=', new DateTime('today'))
                ->first();
            if (!$daily_stats) {
                $daily_stats = new DailyStats;
                $daily_stats->ou_id = $ou_id;
            }
            $daily_stats->delivered += $quantity;
            $daily_stats->product_id = $product['id'];
            $daily_stats->save();
        }

        $order_exists = orderStats::where('ou_id', $ou_id)->where('created_at', '>=', new DateTime('today'))
            ->first();
        if (!$order_exists) {
            $order_exists = new orderStats;
            $order_exists->ou_id = $ou_id;
            $order_exists->delivered = 0;
        }
        $order_exists->delivered += 1;
        $order_exists->save();
    }

    public function scheduled_count()
    {

        $order_exists = orderStats::where('ou_id', Auth::user()->ou_id)->where('created_at', '>=', new DateTime('today'))
            ->first();
        if (!$order_exists) {
            $order_exists = new orderStats;
            $order_exists->ou_id = Auth::user()->ou_id;
            $order_exists->scheduled = 0;
        }
        $order_exists->scheduled += 1;
        $order_exists->save();
    }
    public function dispatched_count($products)
    {
        foreach ($products as $key => $product) {
            $product_id = $product->id;
            $quantity = $product['pivot']['quantity'];
            $daily_stats = DailyStats::where('ou_id', Auth::user()->ou_id)->where('product_id', $product_id)
                ->where('created_at', '>=', new DateTime('today'))
                ->first();
            if (!$daily_stats) {
                $daily_stats = new DailyStats;
                $daily_stats->ou_id = Auth::user()->ou_id;
            }
            $daily_stats->dispatched += $quantity;
            $daily_stats->product_id = $product_id;
            $daily_stats->save();
        }


        $order_exists = orderStats::where('ou_id', Auth::user()->ou_id)->where('created_at', '>=', new DateTime('today'))
            ->first();
        if (!$order_exists) {
            $order_exists = new orderStats;
            $order_exists->ou_id = Auth::user()->ou_id;
            $order_exists->dispatched = 0;
        }
        $order_exists->dispatched += 1;
        $order_exists->save();
    }

    public function returned_count($products)
    {
        foreach ($products as $key => $product) {
            $product_id = $product->id;
            $quantity = $product['pivot']['quantity'];
            $daily_stats = DailyStats::where('ou_id', Auth::user()->ou_id)->where('product_id', $product_id)
                ->where('created_at', '>=', new DateTime('today'))
                ->first();
            if (!$daily_stats) {
                $daily_stats = new DailyStats;
                $daily_stats->ou_id = Auth::user()->ou_id;
            }
            $daily_stats->returned += $quantity;
            $daily_stats->product_id = $product_id;
            $daily_stats->save();
        }

        $order_exists = orderStats::where('ou_id', Auth::user()->ou_id)->where('created_at', '>=', new DateTime('today'))
            ->first();
        if (!$order_exists) {
            $order_exists = new orderStats;
            $order_exists->ou_id = Auth::user()->ou_id;
            $order_exists->returned = 0;
        }
        $order_exists->returned += 1;
        $order_exists->save();
    }
    public function uploaded_count($quantity, $product_id)
    {
        $daily_stats = DailyStats::where('ou_id', Auth::user()->ou_id)->where('product_id', $product_id)
            ->where('created_at', '>=', new DateTime('today'))
            ->first();
        if (!$daily_stats) {
            $daily_stats = new DailyStats;
            $daily_stats->ou_id = Auth::user()->ou_id;
            $daily_stats->uploaded = 0;
        }
        $daily_stats->uploaded += $quantity;
        $daily_stats->product_id = $product_id;
        $daily_stats->save();
    }

    public function recordOpeningStats($product_id, $ou_id, $type)
    {
        $today = Carbon::today();
        try {
            $quantity = ($this->getProductQuantity($product_id, $ou_id)) ? $this->getProductQuantity($product_id, $ou_id) : 0;
            if ($type == 'Opening') {
                $dailyStats = DailyStats::firstOrNew([
                    'date' => $today,
                    'product_id' => $product_id,
                    'ou_id' => $ou_id,
                    'starting_count' => $quantity
                ]);
            } else {
                $dailyStats = DailyStats::where('created_at', $today)->where('product_id', $product_id)->where('ou_id', $ou_id);
                $dailyStats->closing_count = $quantity;
            }
            $dailyStats->save();
            // Log::info($product_id);
            // Log::warning($quantity);
        } catch (\Throwable $th) {
            // Log::info($th);
            throw $th;
        }
    }

    public function getProductQuantity($product_id, $ou_id)
    {
        $warehouse = Warehouse::where('ou_id', $ou_id)->first();
        $warehouse_id = $warehouse->id;
        $product = Product::find($product_id);
        if ($product->stock_management == 1) {
            return ProductInventory::where('warehouse_id', $warehouse_id)
                ->where('product_id', $product->id)->sum('available_for_sale');
        } elseif ($product->stock_management == 2) {
            $ids = Bin::where('warehouse_id', $warehouse_id)->pluck('id')->toArray();
            return ProductBin::whereIn('bin_id', $ids)->where('product_id', $product->id)->sum('available_for_sale');
        }
    }
}
