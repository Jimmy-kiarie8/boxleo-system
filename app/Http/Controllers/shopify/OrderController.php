<?php

namespace App\Http\Controllers\shopify;

use App\Http\Controllers\Controller;
use App\Models\api\Order;
use App\models\Client;
use App\models\Product;
use App\models\ReceiveOrder;
use App\Models\Sale;
use App\models\Saleorder;
use App\models\Upload;
use App\Scopes\CountryScope;
use App\Seller;
use App\Shopify\Shopify;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return ;
        $shop = Auth::user();
        // dd($shop);

        $orders = $shop->api()->rest('GET', '/admin/orders.json');
        $orders = $orders['body']->container['orders'];

        $orders = collect($orders)->map(function ($order) {
            return (object) $order;
        });
        $orders->transform(function ($order) {
            $order->created_at = Carbon::parse($order->created_at)->format('D d M Y');
            return $order;
        });

        $shopify = new Shopify;
        return $shopify->shopify_transform($orders);
        // return $orders;
        //  $orders = $orders['body']->container['orders'];
        // return view('shopify.orders', compact('orders'));
    }

    public function assigned(Request $request)
    {
        return $request->all();
        $shop = Auth::user();
        return Saleorder::where('shop_id', $shop->id)->paginate();
        //  $orders = $orders['body']->container['orders'];
        // return view('shopify.orders', compact('orders'));
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
        // $shop = Auth::user();
        $data = $request->all();

        $shop = Auth::user();
        $vendor_id = Seller::where('shopify_email', $shop->email)->first('id');
        $warehouse_id = $request->form['warehouse'];
        $vendor_id = $vendor_id->id;

        $data['vendor_id'] = $vendor_id;
        $data['warehouse_id'] = $warehouse_id;



        $sale = new Order();
        $sale->import(new Request($data));

        return;



        $client = Seller::where('shopify_email', $shop->email)->first('id');

        $warehouse = $request->form['warehouse'];
        $country = $request->form['country'];
        $client = $client->id;
        $orders = $request->orders;

        foreach ($orders as $order) {
            // return $order;
            $order_data = new Sale();
            $order_data->warehouse_id = $warehouse;
            $order_data->country_id = $country;

            if ($client) {
                $order_data->seller_id = $client;
            }
            // dd($order["cod_amount"]);
            $productname = '';

            foreach ($order['products'] as $key => $product) {
                if ($key > 0) {
                    $productname = $productname . ' | ' . $product['name'];
                } else {
                    $productname = $product['name'];
                }
            }


            $order_data->order_no = (array_key_exists('order_no', $order)) ? $order["order_no"] : null;
            $order_data->client_name = (array_key_exists('client_name', $order)) ? $order["client_name"] : null;
            $order_data->client_email = $productname;
            $order_data->sender_email = ($productname) ? $productname : null;
            $order_data->client_address = (array_key_exists('address', $order)) ? $order["address"] : null;
            $order_data->client_city = (array_key_exists('city', $order)) ? $order["city"] : null;
            $order_data->client_phone = (array_key_exists('phone', $order)) ? $order["phone"] : null;
            $order_data->amount = (array_key_exists('cod_amount', $order)) ? $order["cod_amount"] : null;

            // return $order_data;
            // $order_data->country_id = array_key_exists('country', $order) ? $order["country"] : Auth::user()->country_id;

            $upload_o = new Upload;
            $product_id = $upload_o->check_product($productname, $client);
            // return;
            // $sender_n = strtolower($product_name);
            // $product_det = Product::whereRaw('LOWER(product_name) = ?', $sender_n)->first();
            // if (!$product_det) {
            //     $this->importProducts($order);
            // }
            // $product_det = Product::where('product_name', $order['sender_name'])->first();
            $order_data->product_id = ($product_id) ? $product_id : null;
            // dd(($statuses));


            $order_data->status = 'Processing';
            // dd($status_value);
            // $order_data->updated_at = (array_key_exists('updated_at', $order)) ? $order['updated_at'] : now();
            // $order_data->created_at = (array_key_exists('created_at', $order)) ? $order['created_at'] : now();
            $order_data->notes = (array_key_exists('notes', $order)) ? $order['notes'] : null;
            $order_data->comment = (array_key_exists('notes', $order)) ? $order['notes'] : null;
            $order_data->quantity = (array_key_exists('quantity', $order)) ? $order['quantity'] : null;
            // dd($order_data);
            $order_data->save();

            $order["productname"] = $productname;
            // $upload_o->receive_orders($rec_prod, $order_data->id, $order, $order_data->seller_id);



            foreach ($order['products'] as $value) {
                // return $value;
                $product = new ReceiveOrder();
                $product->order_qty = $value['quantity'];
                $product->remaining = $value['quantity'];
                $prod_check = new Upload;
                $prod_xpl = $prod_check->check_product($value['name'], $client);
                // return $prod_xpl;
                $product->product_id = $prod_xpl;
                $product->order_id = $order_data->id;
                $product_exists = ReceiveOrder::where('product_id', $prod_xpl)->where('order_id', $prod_xpl)->exists();
                if (!$product_exists) {
                    $product->save();
                }
            }
        }

        return;
    }

    public function orders_assigned()
    {
        $shop = Auth::user();
        $vendor = Seller::where('shopify_email', $shop->email)->first('id');

        if (!$vendor) {
            abort(422, 'No client account found');
        }

        // DB::enableQueryLog(); // Enable query log
        $orders = Sale::setEagerLoads([])->with(['products', 'client'])->latest()->where('seller_id', $vendor->id)->get();
        return $orders->transform(function ($query) {
            $query->client_name = $query->client->name;
            $query->email = $query->client->email;
            $query->phone = $query->client->phone;
            $query->address = $query->client->address;
            $query->cod_amount = $query->total_price;
            return $query;
        });
    }
}
