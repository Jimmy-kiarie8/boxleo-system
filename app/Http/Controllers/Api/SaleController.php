<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SaleResource;
use App\Models\api\OrderApi;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleZone;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SaleController extends Controller
{
    /**
     * Display a listing of orders.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return SaleResource::collection(Sale::with(['orderHistory' => function ($query) {
            $query->setEagerLoads([]);
        }])->paginate(200));
    }

    /**
     * Display the specified order.
     *
     * @param  \App\Models\Sale  $sale
     * @return \Illuminate\Http\Response
     */
    public function show($order_no)
    {
        // return Sale::find($id);
        $orders = SaleResource::collection(Sale::where('order_no', $order_no)->get());

        if (count($orders) > 0) {
            return $orders[0];
        }
        return response()->json(['message' => 'Order not found']);
        // return SaleResource::collection(Sale::where('order_no', $order_no)->first());
    }

    /**
     * Store a newly created order.
     *
     * Example Input json
     * 
     * @bodyParam  
     * 
     * {
     *  "order_no": "ORD382922",
     * "total": "1100.00",
     * "customer_notes": null,
     * "delivery_date": "2021-05-07 00:00:00",
     * "status": "Inprocess",
     * "delivery_status": "Inprocess",
     * "pickup": {
     *     "pickup_city": "Main st",
     *     "pickup_address": "Main st",
     *     "pickup_phone": "08713012437",
     *     "pickup_shop": "Jane's store",
     * },
     * "client": {
     *     "name": "Jim martins",
     *     "email": test@logixsaas.com,
     *     "phone": ""08713012437"",
     *     "address": "Washington DC"
     * },
     * "products": [
     *      {
     *       "sku_no": 'SKU0001',
     *       "price": 18,
     *       "product_name": "Emergency, Portable Car Jump Starter Kit",
     *       "quantity": 2
     *      }
     *]
     *}
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request->all();
        // dd(Auth::guard('api')->user()->ou_id);
        // return Auth::id();
        $user = auth('api')->user();
        // return('dd');
        // return $request->all();
        // return $request->products;
        // $ou_id = $request->ou_id;

        if (Sale::where('order_no', $request->order_no)->exists()) {
            abort(422, 'Order Number ' . $request->order_no . ' exists');
        }
        $ou_id = Auth::guard('api')->user()->ou_id;

        // $tenant = new Tenant();
        // $tenant->limit('sales');
        // return $request->all();
        // return 11212;
        $default_zone = Zone::where('ou_id', $ou_id)->where('default_zone', true)->first('id');
        // $request->validate([
        //     // 'client_id' => 'required',
        //     // 'cart' => 'required'
        // ]);
        // $discount = ($request->discount) ? $request->discount : 0;
        $sale = new OrderApi();

        $products = $request->products;
        $product_array = [];


        foreach ($products as $item) {
            $product_data = [];
            // // $product_check = new Product();
            // $product_arr['id'] = $product['id'];
            // // $product_arr['id'] = $product_check->check($product['product_name'], Auth::guard('api')->id());
            // $product_arr['quantity'] = $product['quantity'];
            // $product_arr['product_name'] = $product['product_name'];
            // $product_array[] = $product_arr;

            // if (!$product_arr['id']) {
            //     abort(422, $product['product_name'] . ' does not exist');
            // }

            $product = Product::setEagerLoads([])->where('sku_no', $item['sku_no'])->where('vendor_id', $user->id)->first();

            if (!$product) {
                $user_item = User::where('email', env('ADMIN_MAIL', 'support@logixsaas.com'))->first();
                $productModel = new Product();
                $data  =  $item;
                $data['vendor_id'] = $user->id;
                $product = $productModel->product_create($data, $user_item);
            }

            $product_data['id'] = $product->id;
            $product_data['quantity'] = $item['quantity'];

            $product_array[] = $product_data;
        }

        // $this->stock_check($product_array);


        $sale = $sale->store($request->all(), $product_array, $request->client, '');
        // return $sale->id;

        if ($sale) {
            $zone = new SaleZone();
            $zone->create($sale->id, $default_zone->id, $default_zone->id);
            return response()->json(['message' => 'Order created', 'data' => $sale]);
        } else {
            return response()->json(['message' => 'Order exists in our system', 'data' => $sale]);
        }
        // return $sale;
    }

    /**
     * Store multiple orders.
     *
     * Example Input json
     * 
     * @bodyParam
     *  
     * [{
     *"customer_notes": lorem ipsum,
     * "order_no": "Boo2922",
     *"total": "1100.00",
     * "delivery_date": "2021-05-07 00:00:00",
     *  "status": "Inprocess",
     *  "delivery_status": "Inprocess",
     *   "client": {
     *       "name": "J Mack",
     *       "email": null,
     *       "phone": "+254713012437",
     *        "address": "Nyari"
     *},
     *"products": [
     *    {
     *     "id": 18,
     *      "product_name": "Emergency, Portable Car Jump Starter Kit",
     *       "quantity": 2,
     *        "vendor_id": 1,
     *         "user_id": 1
     *      }
     *   ]
     *},{
     *   "order_no": "ML212",
     *    "total": "1100.00",
     * "customer_notes": null,
     * "delivery_date": "2021-05-07 00:00:00",
     * "status": "Inprocess",
     * "delivery_status": "Inprocess",
     *  "client": {
     * "name": "Jane martins",
     *  "email": null,
     *   "phone": "+254713012437",
     *   "address": "Nairobi"
     *},
     *"products": [
     *{
     *     "id": 18,
     *      "product_name": "Emergency, Portable Car Jump Starter Kit",
     *       "quantity": 2,
     *        "vendor_id": 1,
     *         "user_id": 1
     *      }
     *   ]
     *}]
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function orders_store(Request $request)
    {
        // return('dd');
        // return $request->all();
        // return $request->products;
        // $ou_id = 1;
        $user = auth('api')->user();
        $ou_id =  $user->ou_id;

        // $tenant = new Tenant();
        // $tenant->limit('sales');
        // return $request->all();
        $default_zone = Zone::where('ou_id', $ou_id)->where('default_zone', true)->first('id');
        // $request->validate([
        //     // 'client_id' => 'required',
        //     // 'cart' => 'required'
        // ]);
        // $discount = ($request->discount) ? $request->discount : 0;
        $sale_store = new OrderApi();

        foreach ($request->all() as $key => $data) {
            $products = $data['products'];

            // $this->stock_check($products);


            $sale = $sale_store->store($data, $products, $data['client'], '');

            $zone = new SaleZone();
            $zone->create($sale->id, $default_zone->id, $default_zone->id);
            // return $sale;
        }

        return 'Create Sale';
    }
}
