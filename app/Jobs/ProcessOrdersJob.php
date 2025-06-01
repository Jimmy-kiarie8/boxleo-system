<?php

namespace App\Jobs;

use App\Models\api\Order;
use App\Models\Ou;
use App\Models\Product;
use App\Models\User;
use App\Models\Warehouse\Warehouse;
use App\Seller;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProcessOrdersJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $url = 'https://boxleocourier.com/dashboard/api/v1/fetch-orders/100';

            $response_1 = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ])->get($url);

            $ordersData =  json_decode($response_1->getBody()->getContents());
            $lastPage = $ordersData->last_page;
            // $lastPage = 10;

            for ($i = 598; $i < $lastPage; $i++) {
                // Log::debug('*************');
                // Log::debug($i);
                // Log::debug('*************');
                $url = 'https://boxleocourier.com/dashboard/api/v1/fetch-orders/100?page=' . $i;

                $response = Http::withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json'
                ])->get($url);

                $orders = json_decode($response->getBody()->getContents());

                $orderArr = [];

                foreach ($orders->data as $key => $value) {
                    $order_arr = [];
                    // return $value;
                    $order_arr['order_no'] = $value->order_no;

                    $seller = Seller::setEagerLoads([])->where('reference', $value->merchant_id)->first('id');
                    $vendor_id = ($seller) ? $seller->id : 221;

                    $ou = Ou::where('reference', $value->sender_country)->first();
                    $ou_id = ($ou) ? $ou->id : 1;

                    $warehouse_id = Warehouse::where('ou_id', $ou_id)->first('id')->id;
                    $order_arr['order_date'] = $value->created_at;
                    $order_arr['status'] = ($value->order_status) ? ucfirst($value->order_status) : null;
                    $order_arr['order_no'] = $value->order_no;
                    $order_arr['waybill_no'] = $value->order_no;
                    $order_arr['address'] = $value->receiver_address;
                    $order_arr['client_name'] = $value->receiver_name;
                    $order_arr['phone'] = $value->receiver_phone;
                    $order_arr['email'] = $value->receiver_email;
                    $order_arr['cod_amount'] = $value->amount;
                    $order_arr['notes'] = $value->custom_reason;
                    $order_arr['delivery_date'] = $value->delivery_date;
                    $order_arr['platform'] = 'API';
                    $order_arr['scheduled_date'] = $value->scheduled_date;
                    $order_arr['vendor_id'] = $vendor_id;
                    $user = User::where('email', env('ADMIN_MAIL', 'support@logixsaas.com'))->first();

                    foreach ($value->order_items as $key => $items) {
                        $product_name = $items->description;

                        $product_model = new Product();

                        $sku = null;

                        if ($vendor_id === 197) {
                            $sku = $this->extractSku($product_name);
                        }
                        $product_id = $product_model->product_compare($sku, $product_name, $vendor_id);
                        if (!$product_id) {
                            $product = new Product();
                            $data = ['vendor_id' => $vendor_id, 'product_name' => $product_name, 'price' => $order_arr['cod_amount']];

                            if ($vendor_id === 197) {
                                $data['sku_no'] = $sku;
                            }

                            $product = $product->product_create($data, $user);
                            $product_id = $product->id;
                        }
                        $order_arr['products'][$key]['id'] = $product_id;
                        $order_arr['products'][$key]['product_name'] = $product_name;
                        $order_arr['products'][$key]['quantity'] = $items->quantity;
                    }

                    $orderArr[] = $order_arr;
                }

                $sale = new Order();
                $data = ['orders' => $orderArr, 'vendor_id' => $vendor_id, 'warehouse_id' => $warehouse_id, 'platform' => 'API'];
                $sale->import($user, $data, null);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }


    public function extractSku($productName)
    {
        $pattern = '/^([^\-]+)/'; // Regular expression pattern to match the first string before the hyphen

        preg_match($pattern, $productName, $matches);

        if (isset($matches[1])) {
            return $matches[1]; // Return the matched SKU
        }

        return null; // SKU not found
    }
}
