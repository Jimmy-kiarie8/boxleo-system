<?php

namespace App\Http\Controllers\shopify;

use App\Http\Controllers\Controller;
use App\Jobs\ProductUpload;
use App\Models\api\ProductApi;
use App\Models\Product;
use App\Models\User;
use App\Seller;
use App\Shopify\Shopify;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function logged_user()
    {
        $user = new User();
        return  $user->logged_user();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return ;
        $shop = Auth::user();
        $products = $shop->api()->rest('GET', '/admin/products.json');
        $products = $products['body']->container['products'];

        $products = collect($products)->map(function ($order) {
            return (object) $order;
        });


        // $product = new ProductApi;
        return $this->shopify_product($products);
        return $products->transform(function ($order) {
            $order->created_at = Carbon::parse($order->created_at)->format('D d M Y');
            return $order;
        });

        $shopify = new Shopify;
        return $shopify->shopify_transform($products);
    }

    public function shopify_product($products)
    {
        $shop = Auth::user();
        $vendor_id = Seller::where('shopify_email', $shop->email)->first('id');
        $vendor_id = $vendor_id->id;
        // dd($shop);
        $exists_orders = Product::setEagerLoads([])->where('vendor_id', $vendor_id)->get('product_name');
        $id = [];
        foreach ($exists_orders as  $exists) {
            $id[] = $exists->product_name;
        }

        // dd($id);

        $product_arr = [];
        $all_products = [];

        foreach ($products as $product) {
            // dd($product->variants[0]->price);
            if (!in_array($product->title, $id)) {
                $product_arr['product_name'] = $product->title;
                $product_arr['price'] = $product->variants[0]['price'];


                $product_arr['buying_price'] = $product->variants[0]['price'];
                $product_arr['quantity'] = 0;
                $product_arr['onhand'] = 0;
                $product_arr['reorder_point'] = 0;
                $product_arr['active'] = 0;
                $all_products[] = $product_arr;
            }
        }
        return $all_products;
    }

    public function products_assigned()
    {
        $shop = Auth::user();
        $vendor = Seller::where('shopify_email', $shop->email)->first('id');

        if (!$vendor) {
            abort(422, 'No client account found');
        }

        // DB::enableQueryLog(); // Enable query log
        $products = Product::setEagerLoads([])->with('skus')->latest()->where('vendor_id', $vendor->id)->get();
        return $products->transform(function ($query) {
            $query->price = $query->skus[0]['price'];
            return $query;
        });
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request->all();
        $data = $request->all();

        $shop = Auth::user();
        $vendor_id = Seller::where('shopify_email', $shop->email)->first('id');
        $warehouse_id = $request->form['warehouse'];
        $vendor_id = $vendor_id->id;

        $data['vendor_id'] = $vendor_id;
        $data['warehouse_id'] = $warehouse_id;

        $user = $this->logged_user();
        ProductUpload::dispatch(new Request($data), $user);
        return;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }

    public function authenticate(Request $request)
    {
        // dd($request->all());
    }
}
