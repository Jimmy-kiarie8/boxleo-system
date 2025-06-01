<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ClientResource;
use App\Http\Resources\OuResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\SaleResource;
use App\Http\Resources\WarehouseResource;
use App\Models\Client;
use App\Models\Ou;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Warehouse\Warehouse;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;

class ApiController1 extends Controller
{
   /**
    * Display a listing of warehouses.
    *
    * @return \Illuminate\Http\Response
    */
   public function warehouse()
   {
      return WarehouseResource::collection(Warehouse::all());
   }

   /**
    * Display a listing of Operating units.
    *
    * @return \Illuminate\Http\Response
    */
   public function ou()
   {
      return OuResource::collection(Ou::all());
   }

   /**
    * Display a listing of clients.
    *
    * @return \Illuminate\Http\Response
    */
   public function clients()
   {
      return ClientResource::collection(Client::paginate(100));
   }

   /**
    * Display a listing of products.
    *
    * @return \Illuminate\Http\Response
    */
   public function products()
   {
      return ProductResource::collection(Product::paginate(200));
   }

   
   /**
    * Create a product.
    *
    * @return \Illuminate\Http\Response
    */
   public function create_product(Request $request)
   {
      $product = new Product();
      // $product->description = $request->description;

      $product->product_name = $request->product_name;
      $product->user_id = $this->logged_user()->id;
      $product->vendor_id = $request->vendor_id;
      // $sku_no = new AutoGenerate;
      $product->sku_no =  IdGenerator::generate(['table' => 'products', 'field' => 'sku_no', 'length' => 15, 'prefix' => 'SKU_']);
      $product->save();
   }

   /**
    * Track an order.
    *
    * @return \Illuminate\Http\Response
    */
   public function order_tracking($order)
   {
         return new SaleResource(Sale::setEagerLoads([])->with(['seller', 'orderHistory' => function($query) {
             $query->setEagerLoads([]);
         }])->where('order_no', $order)->firstOrFail());
   }
}
