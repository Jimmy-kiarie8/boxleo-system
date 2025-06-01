<?php

namespace App\Models;

use App\Models\Warehouse\Product_warehouse;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class ReturnSale extends Model
{
    public function sales()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }

    // public function product_sale()
    // {
    //     return $this->belongsTo(ProductSale::class, 'product_sale_id');
    // }

    public function getCreatedAtAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', Carbon::parse($date))->format('D d M Y H:i');
    }

    public function product_sales()
    {
        return $this->belongsToMany(ProductSale::class)->withPivot('id', 'status', 'receive_status', 'refund_status', 'amount_refunded', 'remaining_refunded', 'returned', 'tobe_returned', 'comment', 'return_date', 'received_on', 'warehouse_id', 'product_sale_id', 'return_sale_id', 'sale_id');
    }
    public static function booted()
    {
        static::addGlobalScope('created_at', function (Builder $builder) {
            $builder->orderBy('id', 'DESC');
        });
    }

    public function warehouse_return($sale, $warehouse_id, $status, $old_status)
    {
        // dd($sale, $warehouse_id, $status, $old_status);
        foreach ($sale['products'] as $product) {
            // dd($product);
            if ($product['pivot']['sent']) {
                // dd($product['pivot']['quantity']);
                $warehouse = new Product_warehouse();
                $warehouse_id = $warehouse_id;
                $seller_id = $product['vendor_id'];
                $product_id = $product['id'];
                $quantity = ((int) $product['returned'] > 0) ? (int) $product['returned'] : $product['pivot']['quantity'];
                // dd($warehouse_id, $seller_id, $product_id, $quantity);
                //    $warehouse_item = $warehouse->add_items($warehouse_id, $seller_id, $product_id, $quantity, $status, $old_status);
                $product_sale = ProductSale::find($product['pivot']['id']);
                // dd(!$product_sale->returned);
                // dd($quantity);
                $daily_start = new DailyStats();
                // $daily_start->returned_count($sale, $quantity, $product_id);

                if (!$product_sale->returned) {
                    // $product_warehouse = Product_warehouse::find($product['pivot']['id']);
                    $warehouse->add_items($warehouse_id, $seller_id, $product_id, $quantity, $status, $old_status);
                    // dd($warehouse_item['id']);
                    $product_sale->returned = true;
                    $product_sale->sent = false;
                    // $product_sale->quantity_sent -= $quantity;
                    $product_sale->quantity_returned = $quantity;
                    $product_sale->quantity_tobe_delivered -= $quantity;
                    $product_sale->save();
                }
                $daily_start = new DailyStats();
                // $daily_start->returned_count($quantity, $product_id);
            }
        }
    }


    public function return_item($data, $comment)
    {
        // return $request->all();
        $warehouse_id = $data['warehouse_id'];
        $sale_id = $data['id'];
        $return_order = new ReturnSale();

        $return_order->rma  = make_reference_id('rma', ReturnSale::max('id') + 1);
        $return_order->return_date = now();
        $return_order->comment = $comment;
        $return_order->sale_id = $sale_id;
        $return_order->receive_status = 'Complete';
        $return_order->received_on = now();
        $return_order->return_count += 1;
        // $return_order->returned = $request->returned;
        // $return_order->product_sale_id = 39;
        $return_order->save();
        // return $return_order;

        foreach ($data['products'] as $product) {
            // dd($product);
            $warehouse_id = $data['warehouse_id'];
            $sale_id = $data['id'];
            $return_product = new ReturnProductSales();
            $return_product->return_date = now();
            $return_product->comment = $comment;
            $return_product->product_sale_id = $product['pivot']['id'];
            $return_product->sale_id = $sale_id;
            $return_product->warehouse_id = $warehouse_id;
            $return_product->tobe_returned = $product['pivot']['quantity'];
            $return_product->return_sale_id = $return_order->id;
            $return_product->status = 'Received';
            $return_product->receive_status = 'Received';
            $return_product->save();
        }
    }
}
