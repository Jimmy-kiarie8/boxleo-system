<?php

namespace App\Http\Controllers;

use App\Imports\ProductImport;
use App\Jobs\ExcelMultiProduct;
use App\Models\api\Order;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\User;
use App\Uploads\OrderUpload;
use Illuminate\Support\Facades\Log;

class OrderUploadController extends Controller
{
    public function logged_user()
    {
        $user = new User();
        return  $user->logged_user();
    }

    public function salesUpload(Request $request)
    {
        $orders = $request->orders;

        $orders = $this->combine_orders($orders);
        $user = $request->user;
        // return $request->all();
        if ($request->platform == 'Google Sheets' || Auth::guard('seller')->check()) {
            $user = ($user) ? $user : User::where('email', env('ADMIN_MAIL', 'support@logixsaas.com'))->first();
            $vendor = Auth::guard('seller')->user();
        } else {
            $user = $this->logged_user();
            $vendor = null;
        }

        $sale = new Order();

        $data = ['orders' => $orders, 'vendor_id' => $request->vendor_id, 'warehouse_id' => $request->warehouse_id, $request->platform];
        $sale->import($user, $data, $vendor);
        return;
    }

    public function combine_orders($data)
    {
        try {
            //code...

            // Assuming you have the JSON data as an array

            // Convert the JSON data into a Laravel Collection
            $collection = collect($data);

            // Group the data by 'order_no' and 'phone'
            $grouped = $collection->groupBy(['order_no', 'phone'])->map(function ($orders) {
                // Combine the products for the same 'order_no' and 'phone'
                $combinedProducts = [];
                $total_cod_amount = 0;

                foreach ($orders as $order) {
                    $invoice_value = 0;
                    foreach ($order as $productId => $product) {
                        // Use product ID as the key in the combinedProducts array
                        $combinedProducts[$productId] = $product['products'];
                        
                        // Sum up cod_amount if it exists
                        if (array_key_exists('cod_amount', $product)) {
                            $total_cod_amount += floatval(preg_replace('/[^0-9.]/', '', $product['cod_amount']));
                        }
                    }

                    if ((array_key_exists('invoice_value', $order[0]))) {
                        $cleanedPrice = preg_replace('/[^0-9.]/', '', $product['invoice_value']);

                        if ($cleanedPrice == '' || !$cleanedPrice || !$cleanedPrice == null) {
                            $cleanedPrice = 0;
                        }

                        if (!is_numeric($cleanedPrice)) {
                            $cleanedPrice = 0;
                        }
                        // Assign the cleaned value to the "total_price" attribute
                        $invoice_value += $cleanedPrice;
                    }

                    $flattenedProducts = collect($combinedProducts)->map(function ($item) {
                        if (is_array($item)) {
                            // Extract the inner array value and merge it with the outer key
                            $productId = key($item);
                            $productData = $item[$productId];
                            return array_merge(['id' => $productId], $productData);
                        } else {
                            // Handle the case where $item is not an array (e.g., if it's null)
                            // You can return an error value or take another appropriate action.
                            // For example:
                            return [];
                        }
                    })->values()->all();

                    $cod_amount = null;

                    if (array_key_exists('vendor_id', $order[0])) {
                        if ($order[0]['vendor_id'] == 376) {
                            $cod_amount = $total_cod_amount;
                        } elseif (array_key_exists('cod_amount', $order[0])) {
                            $cod_amount = $order[0]['cod_amount'];
                        }
                    } elseif (array_key_exists('cod_amount', $order[0])) {
                        $cod_amount = $order[0]['cod_amount'];
                    }

                    // dd($formattedProducts);
                    // Create a new structure with combined products
                    $combinedOrder = [
                        'invoice_value' => 0,
                        'order_date' => (array_key_exists('order_date', $order[0])) ? $order[0]['order_date'] : null,
                        'status' => (array_key_exists('status', $order[0])) ? $order[0]['status'] : null,
                        'waybill_no' => (array_key_exists('waybill_no', $order[0])) ? $order[0]['waybill_no'] : null,
                        'address' => (array_key_exists('address', $order[0])) ? $order[0]['address'] : null,
                        'client_name' => (array_key_exists('client_name', $order[0])) ? $order[0]['client_name'] : null,
                        'alt_phone' => (array_key_exists('alt_phone', $order[0])) ? $order[0]['alt_phone'] : null,
                        'email' => (array_key_exists('email', $order[0])) ? $order[0]['email'] : null,
                        'quantity' => (array_key_exists('quantity', $order[0])) ? $order[0]['quantity'] : null,
                        'cod_amount' => $cod_amount,
                        'notes' => (array_key_exists('notes', $order[0])) ? $order[0]['notes'] : null,
                        'city' => (array_key_exists('city', $order[0])) ? $order[0]['city'] : null,
                        'agent_number' => (array_key_exists('agent_number', $order[0])) ? $order[0]['agent_number'] : null,
                        'sku_no' => (array_key_exists('sku_no', $order[0])) ? $order[0]['sku_no'] : null,
                        'order_no' => (array_key_exists('order_no', $order[0])) ? $order[0]['order_no'] : null,
                        'weight' => (array_key_exists('weight', $order[0])) ? $order[0]['weight'] : 0,
                        'delivery_date' => (array_key_exists('delivery_date', $order[0])) ? $order[0]['delivery_date'] : null,
                        'platform' => (array_key_exists('platform', $order[0])) ? $order[0]['platform'] : null,
                        'client_name' => (array_key_exists('client_name', $order[0])) ? $order[0]['client_name'] : null,
                        'phone' => (array_key_exists('phone', $order[0])) ? $order[0]['phone'] : null,
                        'products' => $flattenedProducts,
                    ];
                }
                $combinedOrder['invoice_value'] = $invoice_value;
                return $combinedOrder;
            });
            // dd($grouped);

            // Convert the result back to an array
            return $result = $grouped->values()->all();
        } catch (\Throwable $th) {
            throw $th;
        }
        // Output the result
        // echo json_encode($result, JSON_PRETTY_PRINT);
    }


    public function get_orders(Request $request)
    {
        // $request->validate();
        // return $request->all();
        // abort(422, 'boom');
        // return tenant()->subscriber['tenant_plan']['inventory_management'];

        $data = $request->all();

        if (Auth::guard('seller')->check()) {
            $request->validate([
                'warehouse_id' => 'required'
            ]);
            $data['vendor_id'] = Auth::guard('seller')->id();
        } else {
            $request->validate([
                'vendor_id' => 'required',
            ]);
        }


        if ($request->platform != 'Google Sheets') {
            // $this->authorize('upload', Sale::class);
        }
        $sale = new OrderUpload;
        if ($request->platform == 'Shopify') {
            $sale = $sale->shopify($request);
        } elseif ($request->platform == 'Excel') {
            $file = request()->file('file');
            $sale = $sale->excel($file, new Request($data));
        } elseif ($request->platform == 'Excel2') {
            $sale = $sale->excel_2($request->all());
        } elseif ($request->platform == 'Google Sheets') {
            $sale = $sale->google($request);
        } elseif ($request->platform == 'Woocommerce') {
            $sale = $sale->woocommerce($request);
        }
        return $sale;
    }

    public function sales_upload(Request $request)
    {
        // return $request->all();
        $this->dispatch(new ExcelMultiProduct($this->logged_user(), $request->all()));
        return;
    }


    public function importProducts(Request $request)
    {
        // dd( $request->all());
        $products = Excel::toArray(new ProductImport, request()->file('products'));
        $arr = $products[0];
        // dd($arr);
        foreach ($arr as $value) {
            // dd($value);
            $product = new Product();
            $product->product_name = $value['itemname'];
            $product->sku_no = $value['sku'];
            $product->bar_code = $value['barcode'];
            $product->description = $value['itemdescription'];
            $product->price = $value['price'];
            $product->user_id = Auth::id();
            $product->client_id = $request->client;
            $product->save();
        }
        return redirect('/#/products');
        // dd($arr);
    }
}
