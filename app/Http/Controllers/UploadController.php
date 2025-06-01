<?php

namespace App\Http\Controllers;

use App\Imports\OrderSheetImport;
use App\Imports\SalesImport;
use App\Jobs\ExcelMultiProduct;
use App\Jobs\ExcelUpload;
use App\Models\Admin\Subscription;
use App\Models\api\Order;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
// use App\Models\AutoGenerate;
use Illuminate\Support\Facades\Auth;
use App\Models\Client;
use App\Models\Plan;
use App\Models\Product;
use App\Models\ProductSale;
use App\Models\Sale;
use App\Models\Sku;
use App\Models\User;
use Illuminate\Support\Arr;

class UploadController extends Controller
{
    public function logged_user()
    {
        $user = new User();
        return  $user->logged_user();
    }

    public function salesUpload(Request $request)
    {
        // return $request->all();
        // $tenant = new Tenant;
        // $tenant->limit('sales');

        $sale = new Order();
        if ($request->platform == 'Shopify') {
            $sale->import($request);
        } elseif ($request->platform == 'Excel' || $request->platform == 'Excel2') {
            $sale->import($request);
        } elseif ($request->platform == 'Google') {
            $sale->import($request);
        } elseif ($request->platform == 'Woocommerce') {
            $sale->import($request);
        }
        return;
    }

    public function sales_upload(Request $request)
    {
        // return $request->all();
        $this->dispatch(new ExcelMultiProduct($this->logged_user(), $request->all()));
        return;
    }


    public function importOrder(Request $request)
    {
        // return ($request->all());
        // $order_id = new AutoGenerate;
        $orders = Excel::toArray(new OrderSheetImport, request()->file('orders'));
        $arr = $orders[0];

        $transform = new Order;
        // $vedor_id = $request->vendor_id;
        $vedor_id = 1;
        $sales = $transform->excel_transform($arr, $vedor_id);

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
        $sales = array_values($sales);

        // return $order_arr;
        return response()->json([
            'sales' => $sales,
            'exist_orders' => $exists_row,
        ], 200);


        // return $representative;

    }

    public function orderUploadValidation($order)
    {
        foreach ($order as $value) {
            // dd($value);
            if (!$value['recipient'] || !$value['shipment method'] || !$value['payment type"'] || !$value['instructions'] || !$value['warehouse'] || !$value['pickup date'] || !$value['client'] || !$value['product'] || !$value['quantity']) {
                return response()->json(["message" => "Some fields in the file are empty"], 422);
            }
        }
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



    public function importOrderSheet(Request $request)
    {
        // return ($request->all());
        // $order_id = new AutoGenerate;
        $orders = Excel::toArray(new SalesImport, request()->file('orders'));

        $sales = $orders['Orders'];
        $products = $orders['Products'];
        // $arr = $orders[0];
        $real_orders = [];
        $sales = collect($sales);
        $vendor_id = 1;
        $sales->transform(function ($sale) use ($products, $vendor_id) {
            if ($sale['order_id'] != null) {
                $order_product = [];
                foreach ($products as $key => $product) {
                    if ($product['order_id'] == $sale['order_id']) {
                        $product_check = new Product;
                        $product['id'] = $product_check->check($product['product_name'], $vendor_id);
                        $order_product[] = $product;
                    }
                }
                $sale['products'] = $order_product;
                $sale['order_no'] = $sale['order_id'];
                $sale['notes'] = $sale['special_instructions'];
                $sale['cod_amount'] = $sale['total_amount'];
        }
            return $sale;
        });





        // foreach ($sales as  $order) {
        //     if ($order['order_id'] !== null) {
        //         $order_product = [];
        //         foreach ($products as  $product) {

        //             if($product['order_id'] == $order['order_id']) {
        //                 $order_product[] = $product;
        //             }


        //             $real_orders[] = $order;
        //         }

        //         dd($order_product);

        //         $order['products'] = $order_product;
        //     }
        // }

        // foreach ($products as  $product) {
        //     $product_name = $product['product_name'];
        //     if ($order['order_id'] !== null) {
        //         $product = Product::where('product_name', 'LIKE', "%{$product_name}%")->first();
        //         // $data_items['product'] = ;
        //         $pro_check = new GoogledriveController;
        //         if (!$product) {
        //             $product = $pro_check->check_product($product_name);
        //         }
        //         $products_array[] = $product;
        //     }
        // }

        // return $sales;





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
        $sales = array_values($sales->toArray());

        // return $order_arr;
        return response()->json([
            'sales' => $sales,
            'exist_orders' => $exists_row,
        ], 200);
        // return $representative;

    }
}
