<?php

namespace App\Jobs;

use App\Events\OrderUploadEvent;
use App\Mail\errors\JobErrorMail;
use App\Models\Billingaddress;
use App\Models\Client;
use App\Models\Product;
use App\Models\ProductSale;
use App\Models\Sale;
use App\Models\SaleZone;
use App\Models\SheetUpdate;
use App\Models\Shippingaddress;
use App\Models\Sku;
use App\Models\User;
use App\Models\Zone;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Bus\Batchable;



class OrderUpload1 implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    protected $data, $user, $vendor;

    public $timeout = 360;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $data, $vendor)
    {
        $this->data = $data;
        $this->user = $user;
        $this->vendor = $vendor;
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $Uploaded = false;
        $default_zone = Zone::where('ou_id', $this->user['ou_id'])->where('default_zone', true)->first('id');
        if (isset($this->data) && !empty($this->data)) {
            $orders = $this->data['orders'];

            foreach ($orders as $value) {

                $lock = Cache::lock('orderUpload', 30);

                try {
                    $lock->block(30);

                    DB::beginTransaction();
                    try {

                        $value = @json_decode(json_encode($value), true);

                        $sale_exist = Sale::where('seller_id', $this->data['vendor_id'])->where('order_no', $value['order_no'])->exists();


                        $products = (array_key_exists('products', $value)) ?  $value['products'] : null;
                        $skipped = [];
                        if (!$products) {

                            $skipped_a[] = $value['order_no'];
                            $skipped[] = $skipped_a;
                        } else {
                            if (!$sale_exist) {
                                $client_ = new Client;
                                $phone = $client_->clean_phone($value['phone'], $this->user->ou_id);
                                if (!$phone) {
                                    $client = new Client;
                                    $client->phone = null;
                                    $client->ou_id = $this->user->ou_id;
                                    $client->seller_id = ($this->vendor) ? $this->vendor->id  : $this->data['vendor_id'];
                                    $client->name = $value['client_name'];
                                    $client->address = $value['address'];
                                    $client->alt_phone = (array_key_exists('alt_phone', $value)) ? $value['alt_phone'] : null;
                                    $client->user_id = ($this->vendor) ? 1 : $this->user->id;
                                    $client->email = (array_key_exists('email', $value)) ? $value['email'] : null;
                                    $client->save();
                                } else {
                                    $client = Client::updateOrCreate(
                                        [
                                            'ou_id' => $this->user->ou_id,
                                            'phone' => $phone,
                                            'seller_id' => ($this->vendor) ? $this->vendor->id  : $this->data['vendor_id'],
                                            'name' => $value['client_name'],
                                        ],
                                        [
                                            'address' => $value['address'],
                                            'alt_phone' => (array_key_exists('alt_phone', $value)) ? $value['alt_phone'] : null,
                                            'city' => (array_key_exists('city', $value)) ? $value['city'] : null,
                                            'email' => (array_key_exists('email', $value)) ? $value['email'] : null,
                                            'user_id' => ($this->vendor) ? 1 : $this->user->id,
                                        ]
                                    );
                                }


                                $client_data = [
                                    'name' => $value['client_name'],
                                    'phone' => $value['phone'],
                                    'address' => (array_key_exists('address', $value)) ? $value['address'] : '',
                                    'city' => (array_key_exists('city', $value)) ? $value['city'] : '',
                                    'country' => (array_key_exists('country', $value)) ? $value['country'] : '',
                                    'postal_code' => (array_key_exists('postal_code', $value)) ? $value['postal_code'] : '',
                                    'email' => (array_key_exists('email', $value)) ? $value['email'] : '',
                                    'client_id' =>  $client->id
                                ];

                                $shipping = new Shippingaddress;
                                $shipping->address($client_data);
                                $billing = new Billingaddress;
                                $billing->address($client_data);


                                $sale = new Sale();

                                $cod_amount = $value['cod_amount'];

                                $sale->total_price = $cod_amount;
                                $sale->sub_total = $cod_amount;
                                $sale->user_id = ($this->vendor) ? 1 : $this->user->id;
                                $sale->ou_id = $this->user->ou_id;
                                $sale->client_id = $client->id;




                                $status = 'New';
                                $delivery_status = 'New';

                                if (array_key_exists('status', $value)) {
                                    if ($value['status']) {
                                        $delivery_status = $value['status'];

                                        if ($value['status'] == 'Scheduled') {
                                            $status = 'Confirmed';
                                        } elseif ($value['status'] == 'Delivered' || $value['status'] == 'Returned' || $value['status'] == 'Cancelled') {
                                            $status = 'Closed';
                                        } elseif ($value['status'] == 'In Transit') {
                                            $status = 'Shipped';
                                        } elseif ($value['status'] == 'Pending') {
                                            $status = 'Closed';
                                        }
                                    }
                                }



                                $sale->invoice_value = (array_key_exists('invoice_value', $value)) ?  $value['invoice_value'] : 0;
                                $sale->waybill_no = (array_key_exists('waybill_no', $value)) ?  $value['waybill_no'] : null;
                                $sale->weight = (array_key_exists('weight', $value)) ?  $value['weight'] : 0;
                                $sale->customer_notes = $value['notes'];
                                $sale->order_no = $value['order_no'];
                                $sale->order_date = (array_key_exists('order_date', $value)) ? $value['order_date'] : null;
                                $sale->distance = (array_key_exists('distance', $value)) ? $value['distance'] : 0;
                                $sale->delivery_status = $delivery_status;
                                $sale->status = $status;
                                $sale->delivery_date = (array_key_exists('delivery_date', $value)) ? $value['delivery_date'] : null;
                                $sale->warehouse_id = (array_key_exists('warehouse_id', $this->data)) ? $this->data['warehouse_id'] : null;
                                $sale->seller_id = ($this->vendor) ? $this->vendor->id  : $this->data['vendor_id'];
                                $sale->platform = (array_key_exists('platform', $value)) ?  $value['platform'] : null;

                                $sale->loading_no = (array_key_exists('loading_no', $value)) ?  $value['loading_no'] : null;
                                $sale->boxes = (array_key_exists('boxes', $value)) ?  $value['boxes'] : 1;




                                $agent_number = (array_key_exists('agent_number', $value)) ? $value['agent_number'] : null;


                                if ($agent_number) {
                                    $firstName = strtok($agent_number, ' ');
                                    $agent_id = User::select('id', 'name', 'active')->where('name', 'like', $firstName . '%')->where('active', true)->where('agent_sip', '!=', null)->first();

                                    if ($agent_id) {
                                        $sale->agent_id = $agent_id->id;
                                    }
                                }


                                $sale->save();

                                $zone = new SaleZone;
                                $zone->create($sale->id, $default_zone->id, $default_zone->id);

                                foreach ($products as $product_item) {
                                    if ((array_key_exists('products', $value))) {
                                        $product = Product::where('id', $product_item['id'])->first();
                                    } else {
                                        return;
                                    }

                                    if (!$product) {
                                        return;
                                    }
                                    $sku_id = Sku::where('product_id', $product->id)->first();
                                    $quantity = (array_key_exists('quantity', $product_item)) ? $product_item['quantity'] : 1;
                                    $product_sale = new ProductSale();
                                    $product_sale->sale_id = $sale->id;
                                    $product_sale->product_id = $product->id;
                                    $product_sale->seller_id = $product->vendor_id;
                                    if (!$sku_id) {
                                    }
                                    $product_sale->sku_id = $sku_id['id'];
                                    $product_sale->sku_no = $product->sku_no;

                                    $product_sale->price = $sku_id['price'];

                                    $product_sale->quantity = $quantity;

                                    $product_sale->quantity_tobe_delivered = $quantity;
                                    $product_sale->total_price = (int)$product_sale->price * (int)$product_sale->quantity;
                                    $product_sale->save();
                                }


                                $sale_arr[] = $sale;

                                $Uploaded = true;
                            }
                        }
                        DB::commit();
                    } catch (\Throwable $th) {
                        DB::rollBack();
                        throw $th;
                    }

                } catch (LockTimeoutException $e) {
                } finally {
                    optional($lock)->release();
                }
            }

            if (!empty($skipped)) {
                $this->skipped($skipped);
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
                if ($rep['order_no'] == $value['order_no']) {
                    Arr::forget($sales, $index);
                }
            }
        }
    }

    /**
     * The job failed to process.
     *
     * @param  Exception  $exception
     * @return void
     */
    public function failed(Exception $exception)
    {
        $message = 'Orders did not upload. Error message: ' . $exception->getMessage();
        $subject = 'Order upload failed';
        Mail::to(['jimlaravel@gmail.com'])->send(new JobErrorMail($message, $subject));
    }


    public function skipped($orders)
    {

        try {
            $string = json_encode($orders);
            $message = 'Below order numbers were skipped due to product missing ' . $string;
            $subject = 'Order upload failed';
            Mail::to(['jimlaravel@gmail.com'])->send(new JobErrorMail($message, $subject));
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
