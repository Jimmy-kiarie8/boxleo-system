<?php

namespace App\Models\Warehouse;

use App\Models\Product;
use App\Models\ProductInventory;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

class Product_warehouse extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'warehouse_id', 'product_id', 'seller_id', 'onhand', 'commited', 'available_for_sale'];

    public $with = ['products', 'warehouses'];

    public function scopeUserid($query)
    {
        // dd(Auth::user()->hasRole('Admin'));
        // if (!Auth::user()->hasRole('Admin')) {
        //     return $query->where('user_id', Auth::id());
        // }
        return;
    }

    public function products()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function warehouses()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function reduce_items($warehouse_id, $seller_id, $product_id, $quantity, $status, $old_status)
    {
        $product = Product::select('virtual', 'stock_management')->find($product_id);
        if (!$product->virtual) {
            // dd(tenant()->subscriber->tenant_plan->inventory_management, $product->stock_management);
            if (tenant()->subscriber->tenant_plan->warehouse_management && $product->stock_management == 2) {
                return $this->stock_update($product_id, $quantity, $status, $old_status, $warehouse_id);
            } elseif (tenant()->subscriber->tenant_plan->inventory_management && $product->stock_management == 1) {
                // dd($warehouse_id, $seller_id, $product_id, $quantity, $status, $old_status);
                $product = ProductInventory::where('warehouse_id', $warehouse_id)
                    ->where('seller_id', $seller_id)
                    ->where('product_id', $product_id)->first();


                if ($status == 'Delivered') {
                    $product->onhand -= $quantity;
                    $product->commited -= $quantity;
                } elseif ($status == 'Scheduled' || $status == 'Dispatched') {
                    // dd($quantity);
                    $product->available_for_sale -= $quantity;
                    $product->commited += $quantity;
                }
                $product->save();
                return $product;
            }
        }
    }

    public function delivered_items($warehouse_id, $seller_id, $product_id, $quantity, $status, $old_status)
    {
        $product = Product::select('virtual', 'stock_management')->find($product_id);
        if (!$product->virtual) {
            if (tenant()->subscriber->tenant_plan->warehouse_management && $product->stock_management == 2) {
                return $this->stock_update($product_id, $quantity, $status, $old_status, $warehouse_id);
            } elseif (tenant()->subscriber->tenant_plan->inventory_management && $product->stock_management == 1) {
                $product = ProductInventory::where('warehouse_id', $warehouse_id)
                    ->where('seller_id', $seller_id)
                    ->where('product_id', $product_id)->first();

                if ($product) {
                    $product->onhand -= $quantity;
                    $product->commited -= $quantity;
                    $product->save();
                    return $product;
                }
            }
        }
    }

    public function add_items($warehouse_id, $seller_id, $product_id, $quantity, $status, $old_status)
    {
        $product = Product::select('virtual', 'stock_management')->find($product_id);
        if (!$product->virtual) {
            if (tenant()->subscriber->tenant_plan->warehouse_management && $product->stock_management == 2) {
                return $this->stock_update($product_id, $quantity, $status, $old_status, $warehouse_id);
            } elseif (tenant()->subscriber->tenant_plan->inventory_management && $product->stock_management == 1) {
                $product = ProductInventory::where('warehouse_id', $warehouse_id)
                    ->where('seller_id', $seller_id)
                    ->where('product_id', $product_id)->first();

                if ($product) {
                    if ($old_status == 'Delivered' && $status == 'Returned') {
                        $product->available_for_sale += $quantity;
                        $product->onhand += $quantity;
                    } else {
                        $product->commited -= $quantity;
                        $product->available_for_sale += $quantity;
                    }
                    $product->save();
                }
            }
        }
        return $product;
    }

    public function api_return($status, $quantity, $product_id, $warehouse_id)
    {

        try {
            $url = 'http://warehouse.test/api/reduce_qty';
            $client = new Client;
            $response = $client->request('POST', $url);
            $response = $client->request('POST',  $url, [
                'form_params' => [
                    'quantity' => $quantity,
                    'product_id' => 1,
                    'warehouse_id' => $warehouse_id,
                    'status' => $status
                ]
            ]);


            $response = json_decode($response->getBody()->getContents());

            return $response;
        } catch (\Exception $e) {
            dd($e);

            // Log::error($e);
            return $e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile();
        }
    }

    // public function reduce_qty(Request $request)
    public function stock_update($product_id, $quantity, $status, $old_status, $warehouse_id)
    {


        // $status = $request->status;
        // $old_status = $request->old_status;
        // $quantity = $request->quantity;
        // $warehouse_id = $request->warehouse_id;
        // $product_id = $request->product_id;


        // $product_id= 11;
        // $quantity = 10;
        // // $status = 'Returned';
        // $status = 'Delivered';
        // // $status = 'Dispatched';
        // $old_status = 'Scheduled';
        // dd($warehouse_id, $seller_id, $product_id, $quantity, $status, $old_status);
        $bins = Bin::where('warehouse_id', $warehouse_id)->get('id');
        $ids = [];
        foreach ($bins as $key => $bin) {
            $ids[] = $bin->id;
        }

        $product = ProductBin::whereIn('bin_id', $ids)->where('product_id', $product_id)->first();

        if (!$product) {
            return;
        }

        if ($status == 'Delivered') {
            $product->onhand -= $quantity;
            $product->commited -= $quantity;
        } elseif ($status == 'Dispatched') {
            // dd($quantity);
            $product->available_for_sale -= $quantity;
            $product->commited += $quantity;
        } elseif ($status == 'Returned') {
            if ($old_status == 'Delivered' && $status == 'Returned') {
                $product->available_for_sale += $quantity;
                $product->onhand += $quantity;
            } else {
                $product->commited -= $quantity;
                $product->available_for_sale += $quantity;
            }
        }


        // return $product;
        $product->save();
        return $product;
    }
    public function getCreatedAtAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', Carbon::parse($date))->format('D d M Y');
    }
}
