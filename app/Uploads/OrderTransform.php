<?php

namespace App\Uploads;

use App\Jobs\OrderUpload;
use App\Models\Client;
use App\Models\Product;
use App\Models\ProductSale;
use App\Models\Sale;
use App\Models\Sku;
use App\Models\Status;
use App\Models\User;
use App\Seller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Str;

class OrderTransform
{
    public function logged_user()
    {
        $user = new User();
        return  $user->logged_user();
    }
    public function shopify_transform($orders, $vendor_id)
    {
        // dd($orders);
        $order_arr = [];
        $all_orders = [];
        foreach ($orders as $key => $order) {
            // var_dump($key);
            // var_dump((isset($order->shipping_address)) ? 1: 2);
            $shipping = $order->shipping_address;
            // $billing = $order->billing_address;
            // dd($shipping);
            $order_arr['order_no'] = $order->order_number;
            $order_arr['address'] = $shipping->address1;
            $order_arr['client_name'] = $shipping->first_name . ' ' . $shipping->last_name;
            $order_arr['phone'] =  $shipping->phone;
            $order_arr['email'] = $order->email;
            // $order_arr['quantity'] = $order->number;
            $order_arr['cod_amount'] = $order->current_total_price;
            // $order_arr['status'] = $order->number;
            $order_arr['notes'] = $order->note;

            $products = $order->line_items;

            $product_arr = [];
            $all_products = [];
            $product_check = new Product;
            foreach ($products as $product) {
                // dd($product);
                $product_arr['name'] = $product->name;
                $product_arr['quantity'] = $product->quantity;
                $product_arr['price'] = $product->price;
                $product_arr['id'] = $product_check->check($product->name, $vendor_id);
                $all_products[] = $product_arr;
            }
            $order_arr['products'] = $all_products;
            $all_orders[] = $order_arr;
        }

        return $all_orders;
    }
    public function excel_transform($orders, $vendor_id)
    {
        $vendor = Seller::setEagerLoads([])->find($vendor_id);

        $prefix = $vendor->order_prefix;
        // $data_rq = new Request($orders);
        // $data_rq->validate([ 
        //     'delivery_date' => 'date_format:Y-' 
        // ]);  
        $all_orders = [];
        foreach ($orders as $key => $order) {
            // dd($order['delivery_date']);
            $order_arr = [];
            $order_no = $order['order_id'];
            $order_no = str_replace('#', '', $order_no);
            $order_no =  $prefix . $order_no;

            $order_arr['order_no'] = $order_no;
            $order_arr['address'] = $order['address'];
            $order_arr['client_name'] = $order['client_name'];
            $order_arr['phone'] =  $order['phone'];
            $order_arr['status'] =  (array_key_exists('status', $order)) ? $this->check_status($order['status']) : null;
            $order_arr['waybill_no'] = (array_key_exists('waybill_number', $order)) ? $order['waybill_number'] : null;
            // $order_arr['delivery_date'] = (array_key_exists('delivery_date', $order)) ? date('Y-m-d', Date::excelToTimestamp($order['delivery_date']))  : '';
            $order_arr['delivery_date'] = ($order['delivery_date'] != null) ? date('Y-m-d', Date::excelToTimestamp($order['delivery_date'])) : '';
            // $order_arr['delivery_date'] = (array_key_exists('delivery_date', $order)) ? Carbon::parse($order['delivery_date']) : '';
            $order_arr['platform'] = 'Upload';

            // $order_arr['email'] = $order['email'];
            // $order_arr['quantity'] = $order['number'];
            $order_arr['cod_amount'] = $order['total_amount'];
            // $order_arr['status'] = $order['number'];
            $order_arr['notes'] = (array_key_exists('special_instructions', $order)) ? $order['special_instructions']  : null;

            $product_check = new Product;

            if (!array_key_exists('product_name', $order)) {
                abort(422, 'Some required culumns are empty');
            }

            $sku = (array_key_exists('sku_number', $order)) ? $order['sku_number']  : null;

            // $product_arr[] = $product_check->check($order['product_name'], $vendor_id);
            $order_arr['products'][$key]['id'] = $product_check->product_compare($sku, $order['product_name'], $vendor_id);
            // $order_arr['products'][$key]['id'] = $product_check->check($order['product_name'], $vendor_id);

            // dd($order_arr);
            $order_arr['products'][$key]['product_name'] = $order['product_name'];
            $order_arr['products'][$key]['quantity'] = $order['quantity'];

            if (!$order_arr['products'][$key]['id']) {
                $order_arr['product_exists'] = false;
            } else {
                $order_arr['product_exists'] = true;
            }
            $all_orders[] = $order_arr;
        }

        return $all_orders;
    }

    public function check_status($status)
    {
        $statuses = Status::all();
        $status = strtolower($status);

        $status_value = '';
        // if ($status) {
        foreach ($statuses as $value) {
            if ($status == strtolower($value['status'])) {
                // dd(strtolower($status->name), strtolower($order['status']));
                $status_value = $value['status'];
                break;
            } else {
                $status_value = $status;
            }
        }
        return $status_value;
        // $order_data->status = $status_value;
        // }

        // foreach ($statuses as $key => $value) {



        // }
    }

    public function google_transform($orders, $vendor_id, $ou_id, $sync_all)
    {

        try {

            $orders = collect($orders);

            $targetStatuses = ['Delivered', 'In Transit', 'Returned', 'Awaiting Dispatch', 'Undispatched', 'awaiting return', 'Dispatched'];

            // $orders = $orders->reject(function ($order) use ($targetStatuses) {
            //     Log::info($order);
            //     return in_array($order['status'], $targetStatuses);
            // });

            // Log::info('***************************************************');
            // Log::info($orders);

            // dd(1);


            $order_transform = [];



            foreach ($orders as $key => $order) {
                $order_arr = [];

                if (array_key_exists('order_no', $order) || array_key_exists('order_id', $order)) {
                    // $order_prefix = Seller::find($vendor_id)->order_prefix;

                    if (array_key_exists('order_no', $order)) {
                        $order_id = $order['order_no'];
                    } else {
                        $order_id = $order['order_id'];
                    }

                    if ($order_id && (!in_array($order['status'], $targetStatuses) || $ou_id != 4)) {

                        // if ($order_prefix) {
                        //     $order_id = $order_prefix . $order_id;
                        // }

                        // $order_exists = Sale::where('order_no', $order_id)->where('created_at', '>', '2023-12-10')->exists();
                        // if (!$order_exists) {

                        $status = new Status();
                        $status_check = (array_key_exists('status', $order)) ? $order['status'] : null;
                        $order_arr['status'] =  $status->check_status($status_check);
                        if (Str::lower($order_arr['status']) == 'dispatched' || Str::lower($order_arr['status']) == 'returned' || Str::lower($order_arr['status']) == 'delivered' || Str::lower($order_arr['status']) == 'scheduled'  || Str::lower($order_arr['status']) == 'cancelled' || $sync_all) {


                            // Others
                            $delivery_date = null;
                            if (array_key_exists('delivery_date', $order)) {
                                if ($order['delivery_date']) {
                                    $delivery_date = $order['delivery_date'];
                                }
                            }
                            $product_name = (array_key_exists('product_name', $order)) ? $order['product_name'] : null;

                            if (!$product_name) {
                                $product_name = null;
                            }

                            $quantity = (array_key_exists('quantity', $order)) ? $order['quantity'] : 1;
                            $order_arr['order_date'] = (array_key_exists('order_date', $order)) ? $order['order_date'] : null;
                            $order_arr['order_no'] = (array_key_exists('order_id', $order)) ? $order_id : null;
                            $order_arr['waybill_no'] = (array_key_exists('waybill_no', $order)) ? $order['waybill_no'] : null;
                            $order_arr['address'] = (array_key_exists('address', $order)) ? $order['address'] : null;
                            $order_arr['weight'] = (array_key_exists('weight', $order)) ? $order['weight'] : null;
                            $order_arr['client_name'] = (array_key_exists('client_name', $order)) ? $order['client_name'] : null;
                            $order_arr['alt_phone'] = (array_key_exists('alt_phone', $order)) ? $order['alt_phone'] : null;
                            $order_arr['phone'] = (array_key_exists('phone', $order)) ? $order['phone'] : null;
                            $order_arr['email'] = (array_key_exists('email', $order)) ? $order['email'] : null;
                            $order_arr['quantity'] = $quantity;
                            $order_arr['cod_amount'] = (array_key_exists('cod_amount', $order)) ? $order['cod_amount'] : null;
                            $order_arr['invoice_value'] = (array_key_exists('invoice_value', $order)) ? $order['invoice_value'] : null;
                            $order_arr['notes'] = (array_key_exists('special_instructions', $order)) ? $order['special_instructions'] : null;;
                            $order_arr['city'] = (array_key_exists('city', $order)) ? $order['city'] : null;
                            $order_arr['agent_number'] = (array_key_exists('agent_number', $order)) ? $order['agent_number'] : null;
                            $order_arr['sku_no'] = (array_key_exists('sku_number', $order)) ? $order['sku_number'] : null;
                            $order_arr['delivery_date'] = $delivery_date;
                            $order_arr['platform'] = 'Google Sheets';
                            $user = User::where('email', env('ADMIN_MAIL', 'support@logixsaas.com'))->first();
                            $product_model = new Product();
                            $product_id = $product_model->product_compare($order_arr['sku_no'], $product_name, $vendor_id);
                            if (!$product_id) {
                                $product = new Product;
                                $data = ['vendor_id' => $vendor_id, 'product_name' => $product_name, 'price' => $order_arr['cod_amount']];
                                $product = $product->product_create($data, $user);

                                $product_id = ($product) ? $product->id : null;
                            }


                            if (!$product_id) {
                                $order_arr['products'] = null;
                            } else {
                                $order_arr['products'][$key]['id'] = $product_id;
                                $order_arr['products'][$key]['product_name'] = $product_name;
                                $order_arr['products'][$key]['quantity'] = $quantity;
                            }
                            if ($ou_id == 4 && $order_arr['order_no'] && $order_arr['client_name']) {
                                $order_transform[] = $order_arr;
                            }
                            // Check if Client name and phone exists
                            elseif ($order_arr['phone'] && $order_arr['client_name'] && $order_arr['order_no']) {
                                $order_transform[] = $order_arr;
                            }
                        }
                        // }
                    }
                }
            }
            // Log::alert($order_transform);
            // dd(1);
            return $order_transform;
        } catch (\Throwable $th) {
            // Log::debug($th);
            throw $th;
        }
    }

    public function woocommerce_transform($orders, $vendor_id)
    {
        // dd($orders);

        $order_arr = [];
        $all_orders = [];

        foreach ($orders as $order) {
            $shipping = $order->shipping;
            $billing = $order->billing;
            // dd($shipping->address_1);
            $order_arr['order_no'] = $order->number;
            $order_arr['address'] = $shipping->address_1;
            $order_arr['client_name'] = $shipping->first_name . ' ' . $shipping->last_name;
            $order_arr['phone'] =  $billing->phone;
            $order_arr['email'] = $billing->email;
            $order_arr['quantity'] = $order->number;
            $order_arr['cod_amount'] = $order->total;
            // $order_arr['status'] = $order->number;
            $order_arr['notes'] = $order->customer_note;

            $products = $order->line_items;

            $product_arr = [];
            $all_products = [];
            $product_check = new Product;
            foreach ($products as $product) {
                // dd($product);
                $product_arr['name'] = $product->name;
                $product_arr['quantity'] = $product->quantity;

                $product_arr['id'] = $product_check->check($product->name, $vendor_id);
                $all_products[] = $product_arr;
            }
            $order_arr['products'] = $all_products;


            $all_orders[] = $order_arr;
        }
        return $all_orders;
    }
    public function check_product($product_name)
    {
        // dd(similar_text(strtolower('Kusini - Minimal'), strtolower('Six Pack Care ABS Fitness Machine With Pedals Twister'), $percent)/strlen('Six Pack Care ABS Fitness Machine With Pedals Twister') *100);
        $perc = 0;
        $products = Product::setEagerLoads([])->get(['product_name', 'id', 'sku_no']);
        // $products = Product::where('client_id', $client_id)->get();
        $text_per = 0;
        foreach ($products as $product) {
            $length = (strlen($product_name) > strlen($product->product_name)) ? strlen($product_name) : strlen($product->product_name);
            $text_per = similar_text(strtolower($product->product_name), strtolower($product_name), $percent) / $length  * 100;
            if ($text_per > $perc) {
                $perc = $text_per;
                $product_id = $product;
            }
        }
        if ($perc > 50) {
            // dd($perc);
            // dd($product_id, $product_name, $perc);
            return $product_id;
        } else {
            // dd($perc);
            $product = new Product();
            $product->id = null;
            $product->sku_no = null;
            return $product->id;
        }
    }

    public function import(Request $request)
    {
        $user = $this->logged_user();
        OrderUpload::dispatch($user, $request->all());
        // $this->dispatch(new OrderUpload($user, $request->all()));
        return;
        // return $request->all();

        $orders = $request->orders;

        foreach ($orders as $value) {
            $products = $value['products'];

            $client = Client::updateOrCreate(
                [
                    // 'name' => $order['name_of_the_client'],
                    'name' => $value['client_name'],
                    'phone' => $value['phone'],
                    'seller_id' => ($this->logged_user()->is_vendor) ? $this->logged_user()->id  : $request->vendor_id
                ],
                [
                    'address' => $value['address'],
                    // 'email' => $entry['email'],
                    // 'user_id' => 1
                    'user_id' => ($this->logged_user()->is_vendor) ? 1 : $this->logged_user()->id,
                    'ou_id' => $user->ou_id
                ]
            );

            $order = new Sale();
            $order->order_no = $value['order_no'];
            $order->phone = $value['phone'];



            $sale = new Sale();
            $sale->total_price = $value['cod_amount'];
            $sale->sub_total = $value['cod_amount'];
            // $sale->discount = $discount;
            $sale->user_id = ($this->logged_user()->is_vendor) ? 1 : $this->logged_user()->id;
            // $sale->user_id = $this->user->id;
            $sale->ou_id = $user->ou_id;
            $sale->client_id = $client->id;
            // $sale->drawer_id = $drawers->id;
            // $sale->order_no = $request['price'];

            $sale->customer_notes = $value['notes'];
            $sale->order_no = $value['order_no'];
            $sale->warehouse_id = $request->warehouse_id;
            // $sale->ou_id = $ou_id;
            $sale->seller_id = ($this->logged_user()->is_vendor) ? $this->logged_user()->id  : $request->vendor_id;
            // $sale->seller_id = $request['vendor_id'];

            // $product  = Product::find($product['id']);
            // $product = new Product;
            // dd($entry['product_name']);


            // $product = $product->search($entry['product_name']);


            $sale->save();
            // return $product->search($entry['product_name'], $request['vendor_id']);

            foreach ($products as $product_item) {

                // return $product_item;
                // return $product_item['id'];

                // $product = Product::find(332);
                $product = Product::find($product_item['id']);
                // $product = Product::where('product_name', 'LIKE', "%{$product_item['name']}%")->first();
                // Log::debug($product);
                // Log::debug($product_item['name']);
                // return $product;

                $product['quantity'] = $product_item['quantity'];

                // return($product['vendor_id']);
                // dd($product['sku_no']);
                $sku_id = Sku::where('sku_no', $product['sku_no'])->first();
                // dd($sku_id);
                $product_sale = new ProductSale();
                $product_sale->sale_id = $sale->id;
                $product_sale->product_id = $product['id'];
                $product_sale->seller_id = $product['vendor_id'];
                $product_sale->sku_id = $sku_id->id;
                $product_sale->sku_no = $product['sku_no'];

                // dd($product);
                $product_sale->price = $sku_id->price;
                // $product_sale->price = (array_key_exists(0, $product['skus'])) ? $product['skus'][0]['price'] : 0;

                $product_sale->quantity = $product['quantity'];

                $product_sale->quantity_tobe_delivered = $product['quantity'];
                $product_sale->total_price = $product_sale->price * $product_sale->quantity;
                $product_sale->save();
            }



            $sale_arr[] = $sale;

            // return $product->search($order['product_name'], $request->vendor_id);

        }
    }
}
