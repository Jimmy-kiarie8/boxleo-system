<?php

namespace App\Http\Controllers\Marketplace;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Shopify;
use App\Shopify\Shopify as ShopifyShopify;
use Illuminate\Support\Facades\Log;

class ShopifyOrderController extends Controller
{

    public function app_assigned()
    {
        $vendor = auth('api')->user();
        // $vendor = Seller::where('shopify_email', $shop->email)->first('id');

        if (!$vendor) {
            abort(422, 'No client account found');
        }

        // DB::enableQueryLog(); // Enable query log
        $orders = Sale::setEagerLoads([])->with(['products', 'client'])->latest()->where('platform', 'Shopify')->where('seller_id', $vendor->id)->paginate(100);
        $orders->transform(function ($query) {
            $query->client_name = $query->client->name;
            $query->email = $query->client->email;
            $query->phone = $query->client->phone;
            $query->address = $query->client->address;
            $query->cod_amount = $query->total_price;
            return $query;
        });

        return $orders;
    }
    public function shopify_app(Request $request)
    {
        // Log::debug($request->all());
        // return $request->all();

        $form = $request->form;
        $user = auth('api')->user();
        $shopify = new Shopify;
        $shopify->ou_id = $form['ou'];
        $shopify->order_prefix = '';
        $vendor_id = $user->id;
        $warehouse_id =  $form['warehouse'];;
        $data = ['vendor_id' => $vendor_id, 'warehouse_id' => $warehouse_id];
        $orders = $request->orders;

        $data = ['orders' => $orders, 'vendor_id' => $vendor_id, 'warehouse_id' => $warehouse_id];
        // Log::debug($shopify);
        $user = User::orderBy('id', 'ASC')->first();
        // ShopifyUpload::dispatch($data, $user, $shopify, 'Shopify-app');
        $shop = new ShopifyShopify;
        return $shop->upload_function($data, $user, $shopify, 'Shopify');
    }
}
