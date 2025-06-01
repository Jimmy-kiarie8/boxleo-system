<?php

namespace App\Http\Controllers;

use App\Imports\ProductImport;
use App\Models\Product;
use App\Models\Warehouse\Product_warehouse;
use App\Models\Sku;
use App\Models\User;
use App\Models\Warehouse\Warehouse;
use App\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductUploadController extends Controller
{
    public function logged_user()
    {
        $user = new User();
        return  $user->logged_user();
    }
    public function get_products(Request $request)
    {
        // dd( $request->all());
        $products = Excel::toArray(new ProductImport, request()->file('file'));
        $arr = $products[0];


        // return response()->json([
        //     'products' => $arr,
        //     'exist_products' => [],
        // ], 200);

        $product_trans = [];
        $product_arr = [];
        foreach ($arr as $value) {
            $product_trans['price'] = (array_key_exists('price', $value)) ? $value['price'] : 0;
            $product_trans['product_name'] = (array_key_exists('product_name', $value)) ? $value['product_name'] : '';
            $product_trans['quantity'] = (array_key_exists('quantity', $value)) ? $value['quantity'] : 0;
            $product_trans['sku_no'] = (array_key_exists('sku_no', $value)) ? $value['sku_no'] : null;
            // $product_trans['vendor_id'] = $value['vendor'];
            // $product_trans['warehouse_id'] = $value['warehouse'];
            // return $value['vendor'];

            // $warehouse = Warehouse::where('name', $value['warehouse'])->first('id');
            // $vendor = Seller::where('name', $value['vendor'])->first('id');

            // $product_trans['warehouse_id'] = ($warehouse) ? $warehouse->id : null;
            // $product_trans['vendor_id'] = ($vendor) ? $vendor->id : null;
            // dd($value);
            // $product = new Product();
            // $product->product_name = $value['itemname'];
            // $product->sku_no = $value['sku'];
            // $product->bar_code = $value['barcode'];
            // $product->description = $value['itemdescription'];
            // $product->price = $value['price'];
            // $product->user_id = Auth::id();
            // $product->client_id = $request->client;
            // $product->save();

            $product_arr[] = $product_trans;
        }


        return response()->json([
            'products' => $product_arr,
            'exist_products' => [],
        ], 200);
    }

    public function productsUpload(Request $request)
    {

        // $product_update = Product::find($id);
        // $product_update->active = $product['active'];
        // $product_update->product_name = $product['product_name'];
        // $product_update->update_comment = 'Product created by ' . $user->name;
        // $product_update->vendor_id = $product['vendor_id'];
        // $product_update->save();
        // return $request->all();

        $user = $this->logged_user();

        foreach ($request->products as $key => $value) {
            // return $value['price'];

            $pro_exit = Product::where('product_name', $value['product_name'])->where('vendor_id', $request->vendor_id)->exists();
            if (!$pro_exit) {
                try {
                    DB::beginTransaction();

                    $product = new Product();
                    // $product->description = $request->description;
    
                    $product->product_name = $value['product_name'];
                    $product->user_id = $this->logged_user()->id;
                    $product->vendor_id = $request->vendor_id;
                    $product->active = true;
                    $product->update_comment = 'Product created by ' . $user->name;
                    // $sku_no = new AutoGenerate;


                    $sku_no = (array_key_exists('sku_no', $value)) ? $value['sku_no'] : make_reference_id('SKU', Sku::max('id') + 1);

                    $sku_no = ($sku_no) ?? make_reference_id('SKU', Sku::max('id') + 1);
                    
                    $product->sku_no =  $sku_no;
                    $product->save();
    
                    $id = $product->id;
    
    
                    // return $request->all();
                    // $sku_values = $request->sku_values;
                    // $product = $request->product;
                    // return $request->product['subcategories'];
                    Sku::updateOrCreate(
                        [
                            'sku_no' => $sku_no
                        ],
                        [
                            'buying_price' => $value['price'],
                            'price' => $value['price'],
                            'quantity' => $value['quantity'],
                            'product_id' => $id,
                            'reorder_point' => 0
                        ]
                    );
    
                    // $warehouse_arr = $product['warehouse_arr'];
                    // foreach ($warehouse_arr as  $item) {
                    // return $item['price'];
    
                    // Product_warehouse::updateOrCreate(
                    //     [
                    //         'warehouse_id' => $value['warehouse_id'],
                    //         'product_id' => $id,
                    //         'seller_id' => $value['vendor_id']
                    //     ],
                    //     [
                    //         'onhand' => $value['quantity'],
                    //         'available_for_sale' => $value['quantity'],
                    //         'user_id' => $user->id
                    //     ]
                    // );
                    // }
    
                    // $relation->category_fun($request->product['categories'], $update_product);
                    // $relation->subcategory_fun($request->product['subcategories'], $update_product);
                    // $relation->brand_fun($request->product['brands'], $update_product);
                    // return $update_product;
                    DB::commit();
                } catch (\Throwable $e) {
                    DB::rollBack();
                    // Log::debug($e);
                    
                }
            }
        }

        return 'success';
    }
}
