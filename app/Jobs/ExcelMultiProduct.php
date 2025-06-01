<?php

namespace App\Jobs;

use App\Models\Client;
use App\Models\Product;
use App\Models\ProductSale;
use App\Models\Sale;
use App\Models\Sku;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ExcelMultiProduct implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 360;

    protected $data, $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $data)
    {
        $this->data = $data;
        $this->user = $user;
    }


    // public function user
    // {
    //     $user = new User();
    //     return  $user->user;
    // }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // return $request->all();
        $request = $this->data;

        $orders = $request['orders']['Orders'];
        $products = $request['orders']['Products'];


        foreach ($orders as  $order) {
            // return $order;
            // $product = $order['product'];
            // return preg_replace('/[^A-Za-z0-9\-]/', '', $order['phone']);
            // $discount = $request->discount;
            // return $request->product['subcategories'];
            $client = Client::updateOrCreate(
                [
                    // 'name' => $order['name_of_the_client'],
                    'name' => $order['client_name'],
                    'phone' => preg_replace('/[^A-Za-z0-9\-]/', '', $order['phone'])
                ],
                [
                    'address' => $order['address'],
                    // 'email' => $entry['email'],
                    'user_id' => $this->user->id,
                    'seller_id' => $request['vendor_id'],
                    'ou_id' => $this->user->ou_id
                ]
            );

            $sale = new Sale();
            $sale->total_price = $order['total_amount'];
            $sale->sub_total = $order['total_amount'];
            // $sale->discount = $discount;
            $sale->user_id = $this->user->id;
            $sale->ou_id = $this->user->ou_id;
            $sale->client_id = $client->id;
            // $sale->drawer_id = $drawers->id;
            // $sale->order_no = $request->price;
            $sale->delivery_date = (array_key_exists('delivery_date', $order)) ? $order['delivery_date'] : '';
            $sale->customer_notes = (array_key_exists('customer_notes', $order)) ? $order['customer_notes'] : '';
            $sale->shipping_charges = (array_key_exists('shipping_charges', $order)) ? $order['shipping_charges'] : '';
            $sale->order_no = (array_key_exists('order_id', $order)) ? $order['order_id'] : '';
            $sale->warehouse_id = $request['warehouse_id'];
            // $sale->ou_id = $ou_id;

            // $product = new Product;
            // dd($entry['product_name']);

            // $product = $product->search($entry['product_name']);

            // $total_p = $product['skus'][0]['price'] * $product['skus'][0]['quantity'];

            // $sale->discount = ($request->discount) ? $request->discount : $total_p - $order['total_amount'];
            $sale->save();

            foreach ($products as $value) {

                if ($value['order_id'] == $order['order_id']) {

                    $product  = Product::where('product_name', $value['product_name'])->first();
                    $sku_id = Sku::where('sku_no', $product->sku_no)->first('id');
                    $product_sale = new ProductSale;
                    $product_sale->sale_id = $sale->id;
                    $product_sale->product_id = $product->id;
                    $product_sale->seller_id = $product->vendor_id;
                    $product_sale->sku_id = $sku_id->id;
                    $product_sale->sku_no = $product->sku_no;
                    $product_sale->price = $product->skus[0]['price'];
                    $quantity = $value['quantity'];
                    $product_sale->quantity = $quantity;

                    $product_sale->quantity_tobe_delivered = $quantity;
                    $product_sale->total_price = $product_sale->price * $product_sale->quantity;
                    $product_sale->save();
                }
            }
        }
    }
}
