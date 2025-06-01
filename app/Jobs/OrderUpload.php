<?php

namespace App\Jobs;

use App\Mail\errors\JobErrorMail;
use App\Models\Billingaddress;
use App\Models\Client;
use App\Models\Product;
use App\Models\ProductSale;
use App\Models\Sale;
use App\Models\SaleZone;
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
use Illuminate\Support\Facades\Log;

class OrderUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    protected $data, $user, $vendor;

    public $timeout = 360;

    public function __construct($user, $data, $vendor)
    {
        $this->data = $data;
        $this->user = $user;
        $this->vendor = $vendor;
    }

    public function handle()
    {
        try {
            // DB::beginTransaction();

            $Uploaded = false;
            $default_zone = Zone::where('ou_id', $this->user['ou_id'])->where('default_zone', true)->first('id');
            if (isset($this->data) && !empty($this->data)) {
                $orders = $this->data['orders'];
                $vendor_id = $this->vendor->id ?? $this->data['vendor_id'];

                // Fetch existing sales in a single query
                $existing_sales = Sale::whereIn('order_no', Arr::pluck($orders, 'order_no'))
                    ->where('seller_id', $vendor_id)
                    ->pluck('order_no')
                    ->toArray();


                $sales_data = [];
                $shipping_addresses = [];
                $billing_addresses = [];
                $product_sales = [];
                $sale_order_map = [];  // Map to hold order_no and sale_id pairs

                foreach ($orders as $value) {
                    if (in_array($value['order_no'], $existing_sales)) {
                        continue;
                    }

                    $lock = Cache::lock('orderUpload', 15);
                    try {
                        $lock->block(15);
                        try {
                            $value = @json_decode(json_encode($value), true);

                            $products = $value['products'] ?? null;
                            if (!$products) {
                                $this->skipped([$value['order_no']]);
                                continue;
                            }

                            $client_ = new Client;
                            $phone = $client_->clean_phone($value['phone'], $this->user->ou_id);
                            $client_data = [
                                'ou_id' => $this->user->ou_id,
                                'phone' => $phone,
                                'seller_id' => $vendor_id,
                                'name' => $value['client_name'],
                                'address' => $value['address'],
                                'alt_phone' => $value['alt_phone'] ?? null,
                                'city' => $value['city'] ?? null,
                                'email' => $value['email'] ?? null,
                                'user_id' => $this->vendor->id ?? $this->user->id,
                            ];
                            $client = Client::updateOrCreate(
                                [
                                    'ou_id' => $this->user->ou_id,
                                    'phone' => $phone,
                                    'seller_id' => $vendor_id,
                                    'name' => $value['client_name'],
                                ],
                                $client_data
                            );

                            $client_address_data = [
                                'name' => $value['client_name'],
                                'phone' => $value['phone'],
                                'street_address' => $value['address'] ?? '',
                                'city' => $value['city'] ?? '',
                                'country' => $value['country'] ?? '',
                                'postal_code' => $value['postal_code'] ?? '',
                                'email' => $value['email'] ?? '',
                                'client_id' => $client->id,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];

                            $shipping_addresses[] = $client_address_data;
                            $billing_addresses[] = $client_address_data;



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




                            $agent_number = (array_key_exists('agent_number', $value)) ? $value['agent_number'] : null;

                            $agent_id = null;
                            if ($agent_number) {
                                $firstName = strtok($agent_number, ' ');
                                $agent = User::select('id', 'name', 'active')->where('name', $agent_number)->where('active', true)->first();

                                if($agent) {
                                    $agent_id = $agent->id;
                                }

                            }

                            $cod_amount = $this->extract_amount($value['cod_amount']);


                            $sales_data[] = [
                                'total_price' => $cod_amount,
                                'sub_total' => $cod_amount,
                                'user_id' => $this->vendor->id ?? $this->user->id,
                                'agent_id' => $agent_id,
                                'ou_id' => $this->user->ou_id,
                                'client_id' => $client->id,
                                'status' => $this->determineStatus($value['status'] ?? 'New'),
                                'delivery_status' => $delivery_status,
                                'order_no' => $value['order_no'],
                                'order_date' => $value['order_date'] ?? null,
                                'delivery_date' => $value['delivery_date'] ?? null,
                                'platform' => $value['platform'] ?? null,
                                'seller_id' => $vendor_id,
                                'warehouse_id' => $this->data['warehouse_id'] ?? null,
                                'weight' => $this->data['weight'] ?? 0,
                                'invoice_value' => $value['invoice_value'] ?? 0,
                                'waybill_no' => $value['waybill_no'] ?? null,
                                'weight' => $value['weight'] ?? 0,
                                'customer_notes' => $value['notes'] ?? '',
                                'distance' => $value['distance'] ?? 0,
                                'loading_no' => $value['loading_no'] ?? null,
                                'created_at' => now(),
                                'updated_at' => now(),
                                'boxes' => $value['boxes'] ?? 1,
                            ];


                            $Uploaded = true;
                            // Map order_no to the index in sales_data
                            $sale_order_map[$value['order_no']] = count($sales_data) - 1;

                            // DB::commit();
                        } catch (Exception $e) {
                            // DB::rollBack();
                            throw $e;
                        }
                    } catch (LockTimeoutException $e) {
                        // Handle lock timeout
                    } finally {
                        optional($lock)->release();
                    }
                }

                // Perform bulk insert for sales
                Sale::insert($sales_data);

                // Retrieve the inserted sales records
                $inserted_sales = Sale::whereIn('order_no', array_keys($sale_order_map))->get(['id', 'order_no'])->keyBy('order_no');

                // Prepare product sales data with correct sale_ids
                /*
                foreach ($orders as $value) {
                    if (!isset($inserted_sales[$value['order_no']])) {
                        continue;
                    }
                    $sale_id = $inserted_sales[$value['order_no']]->id;
                    $products = $value['products'];
                    // $products = $value['products'] ?? [];

                    foreach ($products as $product_item) {
                        $product = Product::find($product_item['id']);
                        if (!$product) {
                            continue;
                        }
    
                        $sku = Sku::where('product_id', $product->id)->first();
                        $product_sales[] = [
                            'sale_id' => $sale_id,
                            'product_id' => $product->id,
                            'seller_id' => $product->vendor_id,
                            'sku_id' => $sku->id ?? null,
                            'sku_no' => $product->sku_no,
                            'price' => $sku->price ?? 0,
                            'quantity' => $product_item['quantity'] ?? 1,
                            'quantity_tobe_delivered' => $product_item['quantity'] ?? 1,
                            'total_price' => ($sku->price ?? 0) * ($product_item['quantity'] ?? 1),
                        ];
                    }
                }*/



                foreach ($orders as $value) {
                    if (!isset($inserted_sales[$value['order_no']])) {
                        continue;
                    }
    
                    $sale_id = $inserted_sales[$value['order_no']]->id;
                    $products = $value['products'];
    
                    foreach ($products as $product_item) {
                        // Skip invalid products
                        if (!isset($product_item['id']) || empty($product_item['quantity'])) {
                            Log::warning("Skipping product in order {$value['order_no']} due to missing ID or quantity.");
                            continue;
                        }
    
                        $product = Product::find($product_item['id']);
                        if (!$product) {
                            Log::warning("Skipping product in order {$value['order_no']} because product ID {$product_item['id']} was not found.");
                            continue;
                        }
    
                        $sku = Sku::where('product_id', $product->id)->first();
                        $product_sales[] = [
                            'sale_id' => $sale_id,
                            'product_id' => $product->id,
                            'seller_id' => $product->vendor_id,
                            'sku_id' => $sku->id ?? null,
                            'sku_no' => $product->sku_no,
                            'price' => $sku->price ?? 0,
                            'quantity' => $product_item['quantity'],
                            'quantity_tobe_delivered' => $product_item['quantity'],
                            'total_price' => ($sku->price ?? 0) * $product_item['quantity'],
                        ];
                    }
                }
    

                // Perform bulk insert for product sales
                ProductSale::insert($product_sales);
                Shippingaddress::insert($shipping_addresses);
                Billingaddress::insert($billing_addresses);

                if ($Uploaded && !empty($skipped)) {
                    $this->skipped($skipped);
                }
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    private function extract_amount($string)
    {
        // Remove any non-numeric characters except for the decimal point.
        $amount = preg_replace('/[^\d.]/', '', $string);

        // Convert the string to a float to handle cases like "3,000.00"
        $amount = (float) str_replace(',', '', $amount);

        return $amount;
    }

    private function determineStatus($status)
    {
        switch ($status) {
            case 'Scheduled':
                return 'Confirmed';
            case 'Delivered':
            case 'Returned':
            case 'Cancelled':
                return 'Closed';
            case 'In Transit':
                return 'Shipped';
            case 'Pending':
                return 'Closed';
            default:
                return 'New';
        }
    }

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
        } catch (Exception $e) {
            throw $e;
        }
    }
}
