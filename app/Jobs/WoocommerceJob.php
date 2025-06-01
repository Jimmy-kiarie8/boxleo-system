<?php

namespace App\Jobs;

use App\Models\Client;
use App\Models\Ou;
use App\Models\Product;
use App\Models\ProductSale;
use App\Models\Sale;
use App\Models\SaleZone;
use App\Models\Shopify;
use App\Models\Sku;
use App\Models\Warehouse\Warehouse;
use App\Models\Zone;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;

class WoocommerceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 60;
    protected $data, $user, $shop, $market_place;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data, $user, $shop, $market_place)
    {
        $this->data = $data;
        $this->user = $user;
        $this->shop = $shop;
        $this->market_place = $market_place;
    }

    public function handle()
    {
        if (isset($this->data) && !empty($this->data)) {
            $orders = $this->data['orders'];


            // $exists($orders);

            foreach ($orders as $value) {
                $order_no = $value['order_no'];

                $sale_exist = Sale::where('seller_id', $this->data['vendor_id'])->where('order_no', $order_no)->exists();
                $products = $value['products'];

                if (!$sale_exist) {
                    $country = Ou::where('ou_code', $value['country'])->first();
                    $ou_id = ($country) ? $country->id : 1;

                    $client = Client::updateOrCreate(
                        [
                            'name' => $value['client_name'],
                            'phone' => $value['phone'],
                            'seller_id' => ($this->user->is_vendor) ? $this->user->id  : $this->data['vendor_id']
                        ],
                        [
                            'address' => $value['address'],
                            'email' => $value['email'],
                            'user_id' => ($this->user->is_vendor) ? 1 : $this->user->id,
                            'ou_id' => $ou_id
                        ]
                    );


                    $sale = new Sale();

                    $cod_amount = str_replace(',', '', $value['cod_amount']);

                    $sale->total_price = (int)$cod_amount;
                    $sale->sub_total = (int)$cod_amount;
                    // $sale->discount = $discount;
                    $sale->user_id = ($this->user->is_vendor) ? 1 : $this->user->id;
                    // $sale->user_id = $this->user->id;

                    $warehouse_id = Warehouse::where('ou_id', $ou_id)->first();
                    $default_zone = Zone::withoutGlobalScopes()->where('ou_id', $ou_id)->where('default_zone', true)->first('id');


                    $sale->ou_id = $ou_id;
                    $sale->client_id = $client->id;
                    $sale->platform = $this->market_place;
                    // $sale->drawer_id = $drawers->id;
                    // $sale->order_no = $this->data['price']'];

                    $status = 'New';
                    $delivery_status = 'New';

                    if (array_key_exists('status', $value)) {
                        if ($value['status']) {
                            $delivery_status = $value['status'];

                            if ($value['status'] == 'Scheduled') {
                                $status = 'Confirmed';
                            }
                        }
                    }

                    // if (tenant()->subscriber->tenant_plan->warehouse_management || tenant()->subscriber->tenant_plan->inventory_management) {
                    //     $warehouse = Warehouse::first();
                    //     $warehouse_id = $warehouse->id;
                    // }
                    $sale->prepaid = (array_key_exists('paid', $value)) ?  $value['paid'] : false;
                    $sale->paid = (array_key_exists('paid', $value)) ?  $value['paid'] : false;
                    $sale->waybill_no = (array_key_exists('waybill_no', $value)) ?  $value['waybill_no'] : null;
                    $sale->customer_notes = (array_key_exists('notes', $value)) ? $value['notes'] : null;
                    $sale->order_no = $order_no;
                    $sale->delivery_status = $delivery_status;
                    $sale->status = $status;
                    $sale->delivery_date = (array_key_exists('delivery_date', $value)) ? $value['delivery_date'] : null;
                    $sale->warehouse_id = ($warehouse_id) ? $warehouse_id : 1;
                    // $sale->warehouse_id = (array_key_exists('warehouse_id', $this->data)) ? $this->data['warehouse_id'] : null;
                    // $sale->ou_id = $ou_id;
                    $sale->seller_id = ($this->user->is_vendor) ? $this->user->id  : $this->data['vendor_id'];
                    // $sale->seller_id = $this->data['vendor_id']'];

                    // $product  = Product::find($product['id']);
                    // $product = new Product;
                    // dd($entry['product_name']);


                    // $product = $product->search($entry['product_name']);



                    $sale->save();
                    // return $product->search($entry['product_name'], $this->data['vendor_id']]);


                    $zone = new SaleZone();
                    $zone->create($sale->id, $default_zone->id, $default_zone->id);

                    foreach ($products as $key => $product_item) {

                        // return $product_item;
                        // $product = Product::where('id', $product_item['id'])->first();
                        $product = Product::where('product_name', $product_item['name'])->where('vendor_id', $sale->seller_id)->first();
                        if (!$product) {
                            $product = new Product;
                            $this->data = ['vendor_id' => $sale->seller_id, 'product_name' => $product_item['name'], 'price' => 0];
                            $product = $product->product_create($this->data, $this->user);
                        }


                        // return($product['vendor_id']);
                        $sku_id = Sku::where('sku_no', $product['sku_no'])->first();
                        $quantity = $product_item['quantity'];
                        // Log::debug($sku_id['id']);
                        // return ;
                        // dd($sku_id);
                        $product_sale = new ProductSale();
                        $product_sale->sale_id = $sale->id;
                        $product_sale->product_id = $product['id'];
                        $product_sale->seller_id = $product['vendor_id'];
                        $product_sale->sku_id = $sku_id['id'];
                        $product_sale->sku_no = $product['sku_no'];

                        // Log::debug($sku_id['price']);
                        // return ;
                        $product_sale->price = $sku_id['price'];
                        // $product_sale->price = (array_key_exists(0, $product['skus'])) ? $product['skus'][0]['price'] : 0;

                        $product_sale->quantity = $quantity;

                        $product_sale->quantity_tobe_delivered = $quantity;
                        $product_sale->total_price = (int)$product_sale->price * (int)$product_sale->quantity;
                        $product_sale->save();
                    }

                    $sale_arr[] = $sale;

                    // return $product->search($order['product_name'], $this->data['vendor_id']);


                }
            }
        }
    }


    public function exists($sales)
    {

        $order_arr = [];
        foreach ($sales as $order) {
            if ($order['order_no'] !== null) {
                $real_orders[] = $order;
                $order_arr[] = $order['order_no'];
            }
        }
        $exists_row = Sale::setEagerLoads([])->whereIn('order_no', $order_arr)->get('order_no');
        foreach ($sales as $index => $rep) {
            foreach ($exists_row as $key => $value) {
                // return $rep['order_no'];
                if ($rep['order_no'] == $value['order_no']) {
                    Arr::forget($sales, $index);
                }
            }
        }
    }
}
