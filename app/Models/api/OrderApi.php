<?php

namespace App\Models\api;

use App\Models\Client;
use App\Models\Product;
use App\Models\ProductSale;
use App\Models\Sale;
use App\Models\Sku;
use App\Models\Shippingaddress;
use App\Models\Billingaddress;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OrderApi extends Model
{
    use HasFactory;

    /*    public function store($order, $products, $client, $type)
    {
        $user_id = (Auth::guard('api')->check() || Auth::guard('seller')->check()) ? 1 : Auth::id();
        $seller_id = (Auth::guard('api')->check() || Auth::guard('seller')->check()) ? 1 : $order['vendor_id'];
        $client = Client::updateOrCreate(
            [
                'name' => $client['name'],
                'phone' => $client['phone']
            ],
            [
                'address' => (array_key_exists('address', $client)) ? $client['address'] : '',
                'email' => (array_key_exists('email', $client)) ? $client['email'] : '',
                'user_id' =>  $user_id
            ]
        );

        // return $client;

        $sale = new Sale();

        $total = (array_key_exists('total', $order)) ? $order['total'] : 0 ;
        // $sale->total_price = $request->total;
        // $sale->sub_total = $request->sub_total;
        // $sale->total_price = $order['total'] - $order['adjustment'];
        $sale->total_price = $total;
        $sale->sub_total = (array_key_exists('sub_total', $order)) ? $order['sub_total'] :  $total ;
        $sale->discount = (array_key_exists('discount', $order)) ? $order['discount'] : 0;
        // $sale->user_id = 1;
        $sale->user_id =  $user_id;
        // $sale->user_id = $this->logged_user()->id;
        $sale->client_id = $client->id;
        $sale->seller_id = $seller_id;
        // $sale->drawer_id = $drawers->id;
        // $sale->order_no = $order->price;
        $sale->delivery_date = (array_key_exists('delivery_date', $order)) ? $order['delivery_date'] : null;
        $sale->customer_notes = (array_key_exists('customer_notes', $order)) ? $order['customer_notes'] : null;
        // $sale->shipping_charges = (array_key_exists('shipping_charges', $order)) ? $order['shipping_charges'] : 0;
        $sale->order_no = $order['order_no'];

        // $sale->ou_id = Auth::user()->ou_id;
        $sale->ou_id = (Auth::guard('web')->check()) ? Auth::user()->ou_id :$order['ou_id'];

        if ($type == 'POS') {
            $sale->warehouse_id = 1;
        } else {
            $sale->warehouse_id = $order['warehouse_id'];
        }

        $sale->save();

        foreach ($products as $product) {
            $product_db = Product::find($product['id']);
            // return($product['vendor_id']);
            $sku_id = Sku::where('sku_no', $product_db->sku_no)->first('id');
            // return $sku_id;
            $product_sale = new ProductSale();
            $product_sale->sale_id = $sale->id;
            $product_sale->product_id = $product_db->id;
            $product_sale->seller_id = $product_db->vendor_id;
            $product_sale->sku_id = $sku_id->id;
            $product_sale->sku_no = $product_db->sku_no;
            $product_sale->price = $product_db->skus[0]['price'];

            $quantity = $product['quantity'];

            $product_sale->quantity = $quantity;

            $product_sale->quantity_tobe_delivered = $quantity;
            $product_sale->total_price = $product_sale->price * $product_sale->quantity;
            $product_sale->save();
        }

        // return $request->all();
        // $drawers = Drawer::latest()->where('user_id', Auth::id())->first();
        // $products = $request->cart;

        // $drawer = Drawer::where('user_id', Auth::id())->first();
        // $drawer->sale_total += $total_price;
        // $drawer->expected_closing_amount += $total_price;
        // $drawer->save();
        return $sale;
    } */



    public function store($order, $products, $client, $type)
    {
        $pickup = (array_key_exists('pickup', $order)) ? $order['pickup'] : null;

        $user_id = (Auth::guard('api')->check() || Auth::guard('seller')->check()) ? 1 : Auth::id();
        // $seller_id = (Auth::guard('api')->check() || Auth::guard('seller')->check()) ? Auth::guard('api')->id() : $order['vendor_id'];

        if (Auth::guard('api')->check()) {
            $seller_id = Auth::guard('api')->id();
        } elseif (Auth::guard('seller')->check()) {
            $seller_id = Auth::guard('seller')->id();
        } else {
            $seller_id = $order['vendor_id'];
        }

        $sale_exist = Sale::withoutGlobalScope(OrderScope::class)->where('order_no', $order['order_no'])->first();
        if (!$sale_exist) {
            $sale = new Sale();

            // $sale->ou_id = Auth::user()->ou_id;
            if (auth('api')->check()) {
                $ou_id = auth('api')->user()->ou_id;
                // $sale->ou_id = 1;
            } else {
                $ou_id = (Auth::guard('web')->check()) ? Auth::user()->ou_id : $order['ou_id'];
            }
            if ($type == 'POS') {
                $sale->warehouse_id = 1;
            } else {
                if (auth('api')->check()) {
                    $sale->warehouse_id = 1;
                } else {
                    $sale->warehouse_id = $order['warehouse_id'];
                }
            }

            // $new_client = Client::updateOrCreate(
            //     [
            //         'name' => $client['name'],
            //         'phone' => $client['phone'],
            //         'seller_id' => $seller_id
            //     ],
            //     [
            //         'address' => (array_key_exists('address', $client)) ? $client['address'] : '',
            //         'email' => (array_key_exists('email', $client)) ? $client['email'] : '',
            //         'city' => (array_key_exists('city', $client)) ? $client['city'] : '',
            //         'ou_id' =>  $ou_id,
            //         'user_id' =>  $user_id
            //     ]
            // );

            $clientAttributes = [
                'ou_id' =>  $ou_id,
                'phone' => $client['phone'],
                'name' => $client['name'],
                'seller_id' => $seller_id
            ];
            
            $additionalAttributes = [
                'address' => $client['address'] ?? '',
                'email' => $client['email'] ?? '',
                'city' => $client['city'] ?? '',
                'user_id' => $user_id
            ];
            
            $new_client = Client::updateOrCreate($clientAttributes, $additionalAttributes);
            


            $client_data = [
                'name' => $client['name'],
                'phone' => $client['phone'],
                'address' => (array_key_exists('address', $client)) ? $client['address'] : '',
                'city' => (array_key_exists('city', $client)) ? $client['city'] : '',
                'country' => (array_key_exists('country', $client)) ? $client['country'] : '',
                'postal_code' => (array_key_exists('postal_code', $client)) ? $client['postal_code'] : '',
                'email' => (array_key_exists('email', $client)) ? $client['email'] : '',
                'client_id' =>  $new_client->id
            ];

            $shipping = new Shippingaddress;
            $shipping->address($client_data);
            $billing = new Billingaddress;
            $billing->address($client_data);


            // return $client;
            $sale->ou_id = $ou_id;

            $total = (array_key_exists('total', $order)) ? $order['total'] : 0;
            // $sale->total_price = $request->total;
            // $sale->sub_total = $request->sub_total;
            // $sale->total_price = $order['total'] - $order['adjustment'];
            $sale->total_price = $total;
            $sale->sub_total = (array_key_exists('sub_total', $order)) ? $order['sub_total'] :  $total;
            $sale->discount = (array_key_exists('discount', $order)) ? $order['discount'] : 0;
            // $sale->user_id = 1;
            $sale->user_id =  $user_id;
            // $sale->user_id = $this->logged_user()->id;
            $sale->client_id = $new_client->id;
            $sale->seller_id = $seller_id;
            // $sale->drawer_id = $drawers->id;
            // $sale->order_no = $order->price;
            $sale->upsell = (array_key_exists('upsell', $order)) ? $order['upsell'] : false;
            $sale->delivery_date = (array_key_exists('delivery_date', $order)) ? $order['delivery_date'] : null;
            $sale->customer_notes = (array_key_exists('customer_notes', $order)) ? $order['customer_notes'] : null;
            // $sale->shipping_charges = (array_key_exists('shipping_charges', $order)) ? $order['shipping_charges'] : 0;
            $sale->order_no = $order['order_no'];

            if ($pickup) {
                $sale->pickup_address = $pickup['pickup_address'];
                $sale->pickup_phone = $pickup['pickup_phone'];
                $sale->pickup_shop = $pickup['pickup_shop'];
                $sale->pickup_city = $pickup['pickup_city'];
            }


            $status = 'New';
            $delivery_status = 'New';

            if (array_key_exists('delivery_status', $order)) {
                if ($order['delivery_status']) {
                    $delivery_status = $order['delivery_status'];

                    if (Str::lower($order['delivery_status']) == 'scheduled') {
                        $status = 'Confirmed';
                    }
                } else {
                    $delivery_status = 'New';
                }
            }
            $sale->delivery_status = $delivery_status;
            $sale->status = $status;
            $sale->platform = 'Logixsaas';

            $sale->save();

            foreach ($products as $product) {
                // dd($product['id']);
                // return $product['id'];
                $product_db = Product::find($product['id']);
                // Log::debug($product_db);
                // dd($product_db);
                // return($product['vendor_id']);
                $sku_id = Sku::where('product_id', $product_db->id)->first('id');
                // return $sku_id;
                $product_sale = new ProductSale();
                $product_sale->sale_id = $sale->id;
                $product_sale->product_id = $product_db->id;
                $product_sale->seller_id = $product_db->vendor_id;
                $product_sale->sku_id = $sku_id->id;
                $product_sale->sku_no = $product_db->sku_no;
                $product_sale->price = $product_db->skus[0]['price'];

                $quantity = $product['quantity'];

                $product_sale->quantity = $quantity;

                $product_sale->quantity_tobe_delivered = $quantity;
                $product_sale->total_price = $product_sale->price * $product_sale->quantity;
                $product_sale->save();
            }
            return $sale;
        } else {
            return $sale_exist;
        }

        // return $request->all();
        // $drawers = Drawer::latest()->where('user_id', Auth::id())->first();
        // $products = $request->cart;

        // $drawer = Drawer::where('user_id', Auth::id())->first();
        // $drawer->sale_total += $total_price;
        // $drawer->expected_closing_amount += $total_price;
        // $drawer->save();
    }
}
