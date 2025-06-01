<?php

namespace App\Jobs;

use App\Events\OrderUploadEvent;
use App\Http\Controllers\GoogledriveController;
use App\Http\Controllers\StatusController;
use App\Models\User;
use App\Models\Client;
use App\Models\Product;
use App\Models\ProductSale;
use App\Models\Sale;
use App\Models\SaleZone;
use App\Models\Sku;
use App\Models\Zone;
use App\Notifications\OrderUploadNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class ExcelUpload implements ShouldQueue
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


    // public function logged_user()
    // {
    //     $user = new User();
    //     return  $user->logged_user();
    // }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $request = $this->data;

        // Log::debug($request);

        $default_zone = Zone::where('ou_id', $this->user->ou_id)->where('default_zone', true)->first('id');

        foreach ($request['orders'] as  $order) {
            $entry = $order['entry'];
            $product = $order['product'];
            $sale_exists = Sale::where('order_no', $entry['order_id'])->exists();
            if (!$sale_exists) {
                // $discount = $request['discount'];
                // return $request['product']['subcategories'];
                $client = Client::updateOrCreate(
                    [
                        // 'name' => $order['name_of_the_client'],
                        'name' => $entry['client_name'],
                        'phone' => $entry['phone'],
                        'seller_id' => ($this->user->is_vendor) ? $this->user->id  : $request['vendor_id']
                    ],
                    [
                        'address' => $entry['address'],
                        // 'email' => $entry['email'],
                        'user_id' => ($this->user->is_vendor) ? 1 : $this->user->id,
                        'ou_id' => $this->user->ou_id
                    // 'user_id' => $this->user->id
                    ]
                );

                $sale = new Sale();
                $sale->total_price = $entry['total_amount'];
                $sale->sub_total = $entry['total_amount'];
                // $sale->discount = $discount;
                $sale->user_id = ($this->user->is_vendor) ? 1 : $this->user->id;
                // $sale->user_id = $this->user->id;
                $sale->ou_id = $this->user->ou_id;
                $sale->client_id = $client->id;
                // $sale->drawer_id = $drawers->id;
                // $sale->order_no = $request['price'];
                $sale->delivery_date = (array_key_exists('delivery_date', $entry)) ? $entry['delivery_date'] : '';

            
                $sale->customer_notes = (array_key_exists('customer_notes', $entry)) ? $entry['customer_notes'] : '';
                $sale->shipping_charges = (array_key_exists('shipping_charges', $entry)) ? $entry['shipping_charges'] : '';
                $sale->order_no = (array_key_exists('order_id', $entry)) ? $entry['order_id'] : '';
                $sale->warehouse_id = $request['warehouse_id'];
                // $sale->ou_id = $ou_id;
                $sale->seller_id = ($this->user->is_vendor) ? $this->user->id  : $request['vendor_id'];
                // $sale->seller_id = $request['vendor_id'];

                $product  = Product::find($product['id']);
                // $product = new Product;
                // dd($entry['product_name']);

                $sale_status = new GoogledriveController;

                $status = ($entry['status']) ? $sale_status->check_status($entry['status']) : '';

                $sale->delivery_status = $status;

                if ($status == 'Scheduled') {
                    $sale->status = 'Confirmed';
                    $sale->confirmed = true;
                    $sale->delivery_date = (array_key_exists('delivery_date', $request)) ? $request['delivery_date'] : '';

                    // $warehouse_update = new StatusController;
                    // $sale_order = Sale::find($sale->id);
                    // dd($sale_order->products);


                    // $warehouse_update->warehouse($sale_order, $request['warehouse_id'], $status, 'Inprogress');
                }
                // $product = $product->search($entry['product_name']);

                $total_p = $product['skus'][0]['price'] * $product['skus'][0]['quantity'];

                $sale->discount = (array_key_exists('discount', $request)) ? $request['discount'] : $total_p - $entry['total_amount'];
                $sale->save();
                // return $product->search($entry['product_name'], $request['vendor_id']);



                $zone = new SaleZone();
                $zone->create($sale->id, $default_zone->id, '');


                // return($product);
                $sku_id = Sku::where('sku_no', $product['sku_no'])->first('id');
                // return $sku_id;
                // if(!$product['id']) {
                //     dd($entry);
                // }
                $product_sale = new ProductSale();
                $product_sale->sale_id = $sale->id;
                $product_sale->product_id = $product['id'];
                $product_sale->seller_id = ($this->user->is_vendor) ? $this->user->id  : $request['vendor_id'];
                $product_sale->sku_id = $sku_id['id'];
                $product_sale->sku_no = $product['sku_no'];
                $product_sale->price = $product['skus'][0]['price'];
                $product_sale->quantity = $entry['quantity'];
                $product_sale->total_price = $product_sale->price * $product_sale->quantity;
                $product_sale->product_rate = $entry['total_amount'] / $product_sale->quantity;
                $product_sale->quantity_tobe_delivered = $entry['quantity'];

                // $product_sale->rate = $product_sale->price / $product_sale->quantity;

                $product_sale->save();
            }
        }
    }

    public function tags()
    {
        return [
            'tenant:' . tenant('id'),
        ];
    }
}
