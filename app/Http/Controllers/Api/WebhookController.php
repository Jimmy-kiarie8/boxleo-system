<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\ShopifyUpload;
use App\Models\api\Order;
use App\Models\settings\Webhook;
use App\Models\Shopify;
use App\Models\User;
use App\Models\Warehouse\Warehouse;
use App\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
   public function index()
   {
      return Webhook::all();
   }
   public function show($id)
   {
      return Webhook::find($id);
   }
   public function store(Request $request)
   {
      $webhook = new Webhook;
      $data = $request->all();
      $data['vendor_id'] = Auth::guard('api')->user()->id;
      // return $data;
      return $webhook->create($data);
   }
   public function update(Request $request, $id)
   {
   }
   public function destroy($id)
   {
   }

   public function webhook(Request $request, $id)
   {
      // Log::debug($request->all());
      // return;
      $order_transform = new Order;
      $orders = $request->all();
      $seller = Seller::find($id);
      $vendor_id = $seller->id;
      $orders = $order_transform->shopify_webhook($orders, $vendor_id);



      $shopify = Shopify::where('vendor_id', $id)->first();
      $warehouse_id = Warehouse::where('ou_id', $shopify->ou_id)->first();
      // Log::debug($shopify);

      $data = ['orders' => $orders, 'vendor_id' => $vendor_id, 'warehouse_id' => $warehouse_id];
      $user = User::where('email', env('ADMIN_MAIL', 'support@logixsaas.com'))->first();
      ShopifyUpload::dispatch($data, $user, $shopify, 'Shopify');
   }
}
