<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductResourceCollection;
use App\Models\Client;
use App\Models\Company;
use App\Models\Ou;
use App\Models\Product;
use App\Models\ProductSale;
use App\Models\Sale;
use App\Models\settings\Webhook;
use App\Models\Sku;
use App\Models\Status;
use App\Models\User;
use App\Models\Warehouse\Warehouse;
use App\Seller;
use Illuminate\Http\Request;
use Spatie\WebhookServer\WebhookCall;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use DNS1D;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class APiSaleController extends Controller
{
    public function haversineGreatCircleDistance($latitudeTo, $longitudeTo, $centerLatitude = -1.282294123429601, $centerLongitude = 36.823482309861745)
    {
        // $centerLatitude = -1.282294123429601;
        // $centerLongitude = 36.823482309861745;
        $earthRadius = 6371;



        // Convert from degrees to radians
        $latFrom = deg2rad($centerLatitude);
        $lonFrom = deg2rad($centerLongitude);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

        return $angle * $earthRadius; // Distance in kilometers
    }

    public function shipping_charges(Request $request)
    {

        try {
            $baseCharges = [
                'Nairobi_CBD' => 150,
                'Nairobi_County' => 350,
                'Nairobi_Environs' => 450, // Assuming this is within the 10km radius
                'Outbound' => 600, // Outside the 10km radius
            ];
            $deliveryPoint = [
                'latitude' => $request->latitude,
                'longitude' => $request->longitude
            ];
            $weight = $request->weight;
            $seller_id = auth('api')->id();
            // Log::info($seller_id);

            if ($seller_id == 287) {
                $charge = $this->calculateDeliveryCharge($deliveryPoint, $weight);
            } elseif ($seller_id == 292) {
                $charge = $this->calculateCharge($deliveryPoint, $weight, $request->from_latitude, $request->from_longitude);
            }
            return response()->json(['shipping_charge' => $charge]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }


    // Function to calculate the charge
    public function calculateDeliveryCharge($deliveryPoint, $weight)
    {
        $baseCharges = [
            'Nairobi_CBD' => 150,
            'Nairobi_County' => 350,
            'Nairobi_Environs' => 450, // Assuming this is within the 10km radius
            'Outbound' => 600, // Outside the 10km radius
        ];
        // Calculate the distance from the center to the delivery point
        $distance = $this->haversineGreatCircleDistance(
            $deliveryPoint['latitude'],
            $deliveryPoint['longitude']
        );
        // dd($distance);
        // -1.283120036773655, 36.82354668287372
        $additionalChargePerKg = 50;

        // Determine the base charge based on the distance band
        if ($distance <= 2) {
            $charge = $baseCharges['Nairobi_CBD'];
        } elseif ($distance <= 12) {
            $charge = $baseCharges['Nairobi_County'];
        } elseif ($distance <= 20) {
            $charge = $baseCharges['Nairobi_Environs'];
        } else {
            $charge = $baseCharges['Outbound'];
        }

        // Add extra charge for additional weight over 5kgs
        if ($weight > 5) {
            $charge += ($weight - 5) * $additionalChargePerKg;
        }

        return $charge;
    }


    // Function to calculate the charge
    public function calculateCharge($deliveryPoint, $weight, $lat, $lng)
    {
        $baseCharges = [
            'Nairobi_CBD' => 150,
            'Nairobi_County' => 350,
            'Nairobi_Environs' => 450, // Assuming this is within the 10km radius
            'Outbound' => 600, // Outside the 10km radius
        ];
        // Calculate the distance from the center to the delivery point
        $distance = $this->haversineGreatCircleDistance(
            $deliveryPoint['latitude'],
            $deliveryPoint['longitude'],
            $lat,
            $lng,
        );
        // -1.283120036773655, 36.82354668287372
        $additionalChargePerKg = 50;

        // Determine the base charge based on the distance band
        if ($distance <= 2) {
            $charge = $baseCharges['Nairobi_CBD'];
        } elseif ($distance <= 12) {
            $charge = $baseCharges['Nairobi_County'];
        } elseif ($distance <= 20) {
            $charge = $baseCharges['Nairobi_Environs'];
        } else {
            $charge = $baseCharges['Outbound'];
        }

        // Add extra charge for additional weight over 5kgs
        if ($weight > 5) {
            $charge += ($weight - 5) * $additionalChargePerKg;
        }

        return $charge;
    }


    public function store(Request $request)
    {
        // Log::debug($request->all());
        $order_no = ($request->reference_id) ?  $request->reference_id :  $request->order_no;

        if (!$order_no) {
            abort(422, 'Order number is required');
        }
        if (Sale::where('order_no', $order_no)->exists()) {
            abort(422, 'Order number ' . $order_no . ' is taken');
        }

        $validator = Validator::make($request->all(), [
            'order_no' => 'required|string',
            'customer.email' => 'nullable|email',
            'customer.full_name' => 'required|string',
            'customer.mobile' => 'required|string',
            'shipping_address.address' => 'required|string',
            'shipping_address.city' => 'nullable|string',
            'shipping_address.country' => 'nullable|string',
            'shipping_address.full_name' => 'required|string',
            'shipping_address.latitude' => 'nullable',
            'shipping_address.longitude' => 'nullable',
            'shipping_address.region' => 'nullable|string',
            'shipping_address.zipcode' => 'nullable|string',
            'billing_address.address' => 'nullable|string',
            'billing_address.city' => 'nullable|string',
            'billing_address.country' => 'nullable|string',
            'billing_address.full_name' => 'nullable|string',
            'billing_address.latitude' => 'nullable',
            'billing_address.longitude' => 'nullable',
            'billing_address.region' => 'nullable|string',
            'billing_address.zipcode' => 'nullable|string',
            'currency' => 'nullable|string',
            'customer_notes' => 'nullable|string',
            'line_items' => 'required|array',
            'line_items.*.price' => 'required',
            'line_items.*.quantity' => 'required|integer',
            'line_items.*.variant.sku' => 'required|string',
            'paid' => 'nullable|boolean',
            'payment_method' => 'nullable|string',
            'addition_cost' => 'nullable',
            'shipping_charge' => 'nullable',
            'weight' => 'nullable',
            // 'country' => 'numeric'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $weight = $request->weight;
        $delivery_address = ($request->delivery_address) ? $request->delivery_address : $request->shipping_address;
        $pickup = $request->pickup_address;
        $pointLatitude = (array_key_exists('latitude', $delivery_address)) ?  $delivery_address["latitude"] : null;
        $pointLongitude = (array_key_exists('longitude', $delivery_address)) ?  $delivery_address["longitude"] : null;
        // $distance = $this->haversineGreatCircleDistance($pointLatitude, $pointLongitude);


        // Example usage


        try {


            $total = 0;
            $currency = $request->currency;
            $customer_notes = $request->customer_notes;
            $status = 'New';
            $delivery_date = null;

            // $warehouse_code = (array_key_exists('label', $request->warehouse)) ? $request->warehouse['label'] : null;
            $customer = $request->customer;

            $customer_name = ($customer['full_name']) ? $customer['full_name'] : $customer['full_name'] . ' ' . $customer['last_name'];
            $customer_email = (array_key_exists('email', $customer)) ? $customer['email'] : null;
            $customer_mobile = $customer['mobile'];
            $address = (array_key_exists('address', $delivery_address)) ? $delivery_address['address'] : null;
            $city = (array_key_exists('city', $delivery_address)) ? $delivery_address['city'] : null;
            $paid = $request->paid;
            $payment_method = ($request->payment_method)  ? $request->payment_method : null;
            $shipping_charge = ($request->shipping_charge)  ? $request->shipping_charge : 0;
            $seller_id = auth('api')->id();

            if ($seller_id == 287) {
                // $deliveryPoint = ['latitude' => $pointLatitude, 'longitude' => $pointLongitude];
                // $deliveryCharge = $this->calculateDeliveryCharge($deliveryPoint, $weight);
                // if ($pointLatitude && $pointLongitude) {
                //     $shipping_charge = $deliveryCharge;
                // }
                $shipping_charge = $request->shipping_charge;
            } elseif ($seller_id == 292) {
                // $fromLatitude = (array_key_exists('from_latitude', $delivery_address)) ?  $delivery_address["from_latitude"] : null;;
                // $fromLongitude = (array_key_exists('from_longitude', $delivery_address)) ?  $delivery_address["from_longitude"] : null;;
                $deliveryPoint = ['latitude' => $pointLatitude, 'longitude' => $pointLongitude];
                // $center = ['from_latitude' => $fromLatitude, 'from_longitude' => $fromLongitude];


                $fromLatitude = (array_key_exists('from_latitude', $pickup)) ? $pickup['from_latitude'] : null;
                $fromLongitude = (array_key_exists('from_longitude', $pickup)) ? $pickup['from_longitude'] : null;


                $deliveryCharge = $this->calculateCharge($deliveryPoint, $weight, $fromLatitude, $fromLongitude);
                if ($pointLatitude && $pointLongitude) {
                    $shipping_charge = $deliveryCharge;
                }
            }
            $addition_cost = ($request->addition_cost)  ? $request->addition_cost : 0;
            $ou_code = (array_key_exists('country', $delivery_address)) ? $delivery_address['country'] : null;

            $client = Client::where('address', $customer_mobile)->where('name', $customer_name)->first();
            $ou_id = 1;

            $ou = Ou::where('ou_code', $ou_code)->first();
            // if (!$ou) {
            //     abort(422, 'Operating unit not found');
            // }
            $sale = new Sale();

            if ($ou) {
                $ou_id = $ou->id;
            } elseif (auth('api')->check()) {
                $ou_id = auth('api')->user()->ou_id;
            }
            DB::beginTransaction();

            // $ou = Country::where()
            if (!$client) {
                $client = Client::create([
                    'ou_id' => $ou_id,
                    'seller_id' => $seller_id,
                    'name' => $customer_name,
                    'email' => $customer_email,
                    'phone' => $customer_mobile,
                    'address' => $address,
                    'city' => $city
                ]);
            }


            foreach ($request->line_items as $key => $line_item) {
                $product_sku = $line_item['variant']['sku'];
                $quantity = $line_item['quantity'];
                $price = $line_item['price'];
                $total += $price;

                $product = Product::where('sku_no', $product_sku)->first();

                if (!$product) {
                    abort(422, 'Product with SKU ' . $product_sku .  ' does not exist');
                }
            }
            $sub_total = $total;
            if ($seller_id == 277 || $seller_id == 344) {
                $total += $shipping_charge;
            }


            $sale->total_price = $total;
            $sale->sub_total = $sub_total;
            $sale->user_id =  1;
            $sale->client_id = $client->id;
            $sale->seller_id = $seller_id;
            $sale->customer_notes = $customer_notes;
            $sale->order_no = $order_no;
            $sale->weight = $weight;

            $sale->latitude = $pointLatitude;
            $sale->longitude = $pointLongitude;

            $sale->paid = $paid;
            $sale->payment_method = $payment_method;
            $sale->shipping_charges = $shipping_charge;
            // $sale->addition_cost = $addition_cost;

            $sale->delivery_status = ($request->delivery_status) ? $request->delivery_status : 'New';
            $sale->status = (Str::lower($sale->delivery_status) == 'scheduled') ? 'Confirmed' : 'New';
            $sale->delivery_date = ($request->delivery_date) ? $request->delivery_date : Carbon::tomorrow();


            // $warehouse = Warehouse::where('code', $warehouse_code)->first();
            // $sale->warehouse_id = $warehouse->id;
            $sale->ou_id = $ou_id;
            $sale->warehouse_id = Warehouse::where('ou_id', $ou_id)->first()->id;
            $sale->platform = 'API';


            if ($pickup) {
                $sale->pickup_address = (array_key_exists('address', $pickup)) ? $pickup['address'] : null;
                $sale->pickup_phone = (array_key_exists('phone', $pickup)) ? $pickup['phone'] : null;
                $sale->pickup_city = (array_key_exists('city', $pickup)) ? $pickup['city'] : null;
                $sale->pickup_shop = (array_key_exists('shop', $pickup)) ? $pickup['shop'] : null;
            }

            $sale->save();


            foreach ($request->line_items as $key => $line_item) {
                $product_sku = $line_item['variant']['sku'];
                $quantity = $line_item['quantity'];
                $price = $line_item['price'];

                $product = Product::where('sku_no', $product_sku)->first();


                $sku_id = Sku::where('sku_no', $product_sku)->first('id');
                $product_sale = new ProductSale();
                $product_sale->sale_id = $sale->id;
                $product_sale->product_id = $product->id;
                $product_sale->seller_id = $product->vendor_id;
                $product_sale->sku_id = $sku_id->id;
                $product_sale->sku_no = $product_sku;
                $product_sale->price = $price;

                $product_sale->quantity = $quantity;

                $product_sale->quantity_tobe_delivered = $quantity;
                $product_sale->total_price = $product_sale->price * $product_sale->quantity;
                $product_sale->save();
            }

            $order = Sale::with(['orderHistory' => function ($query) {
                $query->setEagerLoads([]);
            }])->find($sale->id);

            $order =  new OrderResource($order);

            DB::commit();

            return response()->json(['message' => 'Order ' . $order_no . ' created', 'data' => $order]);
        } catch (\Throwable $e) {
            DB::rollBack();
            // Log::warning($e);
            throw $e;
            abort(422, 'Something went wrong');
        }
    }

    public function show($order_no)
    {
        // return SaleResource::collection(Sale::where('order_no', $order_no)->first());
        $sale = Sale::with(['orderHistory' => function ($query) {
            $query->setEagerLoads([]);
        }])->where('order_no', $order_no)->first();

        if (!$sale) {
            abort(422, 'Order not found');
        }
        return new OrderResource($sale);
    }

    public function bulk_search(Request $request)
    {
        $order_no = $request->all();
        $sale = Sale::whereIn('order_no', $order_no)->get();
        return OrderResource::collection($sale);
    }

    public function product(Request $request)
    {
        $seller = auth('api')->user();

        if (!$seller) {
            abort(422, 'Unauthenticated');
        }
        $exists = Product::where('vendor_id', $seller->id)->where('sku_no', $request->sku)->exists();
        // $exists = Product::where('vendor_id', $seller->id)->where('product_name', $request->name)->orWhere('sku_no', $request->sku)->exists();
        if ($exists) {
            abort(422, 'Product exists');
        }
        $data = [
            'product_name' => $request->name,
            'sku_no' => $request->sku,
            'price' => $request->retail_price,
            'vendor_id' => $seller->id
        ];

        $product = new Product();
        $user = User::first();
        // $user = User::where('email', 'support@logixsaas.com')->first();
        $res = $product->product_create($data, $user);
        return response()->json(['message' => 'Product ' . $request->name . ' created', 'data' => new ProductResource($res)]);
    }


    public function cancel($order_no)
    {
        $sale = Sale::where('order_no', $order_no)->first();

        $sale->withoutEvents(function () use ($sale) {
            // code that should execute without observers
            $status = ['Delivered', 'Returned', 'Dispatched', 'Scheduled'];

            if (!$sale) {
                abort(422, 'Order not found');
            } elseif (in_array($sale->delivery_status, $status)) {
                abort(422, 'Order cannot be updated, current status is ' . $status);
            }
            $sale->update(['delivery_status' => 'Cancelled', 'history_comment' => 'Cancelled via API']);
        });

        return response()->json(['message' => 'Order ' . $order_no . ' cancelled']);
    }



    public function add_product(Request $request, $order_no)
    {


        $sale = Sale::where('order_no', $order_no)->first();

        $weight = $request->weight;
        // $addition_cost = $request->addition_cost;
        $total = 0;

        DB::beginTransaction();

        try {
            foreach ($request->line_items as $key => $line_item) {
                $product_sku = $line_item['variant']['sku'];
                $quantity = $line_item['quantity'];
                $price = $line_item['price'];

                $product = Product::where('sku_no', $product_sku)->first();


                $sku_id = Sku::where('sku_no', $product_sku)->first('id');


                $product_exists = ProductSale::where('sale_id', $sale->id)->where('product_id', $product->id)->exists();

                if (!$product_exists) {

                    $product_sale = new ProductSale();
                    $product_sale->sale_id = $sale->id;
                    $product_sale->product_id = $product->id;
                    $product_sale->seller_id = $product->vendor_id;
                    $product_sale->sku_id = $sku_id->id;
                    $product_sale->sku_no = $product_sku;
                    $product_sale->price = $price;

                    $product_sale->quantity = $quantity;

                    $product_sale->quantity_tobe_delivered = $quantity;
                    $product_sale->total_price = $product_sale->price * $product_sale->quantity;
                    $product_sale->save();
                }
            }

            $sale = Sale::setEagerLoads([])->with(['products'])->find($sale->id);
            foreach ($sale->products as $key => $product) {
                $total += $product->pivot->price;
            }

            // 'longitude', 'latitude',
            $shipping_charge = $this->calculateDeliveryCharge(['longitude' => $sale->longitude, 'latitude' => $sale->latitude], $weight, []);

            // Log::debug($shipping_charge);

            $total += $shipping_charge;

            $sale->shipping_charges = $shipping_charge;
            $sale->total_price = $total;
            $sale->save();

            DB::commit();
            return response()->json(['message' => 'Order ' . $order_no . '  Updated']);
        } catch (\Throwable $e) {
            DB::rollBack();
            // Log::debug($e);
            throw $e;
        }
    }

    public function status()
    {
        return Status::all();
    }

    public function webhook_create(Request $request)
    {
        $user = auth('api')->user();
        $webhook = Webhook::where('vendor_id', $user->id)->first();
        if ($webhook) {
            abort(422, 'You already have an active webhook');
        }
        $Webhook = new Webhook();
        $Webhook->webhook_create($request->all(), $user->id);

        return response()->json(['message' => 'Webhook created', 'data' => $webhook]);
    }


    public function waybills(Request $request)
    {
        $request->validate([
            'order_numbers' => 'required|array'
        ]);

        $seller = Seller::find(auth('api')->id());

        // $start_date = Carbon::parse($request->start_date);
        // $end_date = Carbon::parse($request->end_date);

        $orders = Sale::setEagerLoads([])->with(['products', 'client'])->whereIn('order_no', $request->order_numbers)->get();
        // $orders = Sale::setEagerLoads([])->with(['products', 'client'])->whereIn('order_no', $request->order_numbers)->where('printed', false)->get();

        // if($request->start_date === $request->end_date) {
        //     $orders = $orders->whereDate('delivery_date', $start_date);
        // } else {
        //     $orders = $orders->whereBetween('delivery_date', [$start_date, $end_date]);
        // }

        $orders->transform(function ($order) {
            $order_n = str_replace('_', '', $order->order_no);
            $order->barcode = 'data:image/png;base64,' . DNS1D::getBarcodePNG($order_n, "C39");
            return $order;
        });


        $ou_id = $seller->ou_id;
        $ou = Ou::find($ou_id);
        $currency_value = $ou->currency;

        $company = Company::first();

        $company->terms = ($seller->terms) ? $seller->terms : $company->terms;

        $waybill_terms = ($ou->waybill_terms) ? $ou->waybill_terms : $company->terms;

        $pdf_arr = array('data' => $orders, 'currency' => $currency_value, 'company' => $company, 'ou' => $ou, 'waybill_terms' => $waybill_terms, 'pack_list' => []);

        $pdf = PDF::loadView('pdf.waybills.boxleo-waybills', $pdf_arr);
        // return $pdf->download($company->name . ' - waybills.pdf');

        $pdfContents = $pdf->output();
        //  Set the headers to indicate that the response is a PDF file
        $headers = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="example.pdf"',
        ];

        // Create the response with the PDF file as the content
        return response()->streamDownload(function () use ($pdfContents) {
            echo $pdfContents;
        }, $company->name . ' - waybills.pdf', $headers);
    }

    public function products()
    {
        $pro = new Product();
        $product = $pro->transform_display_product(Product::paginate(30));
        return new ProductResourceCollection($product);
    }

    // ML
    public function ml_orders($id)
    {
        $product = Product::setEagerLoads([])->with(['sales' => function ($q) {
            $q->setEagerLoads([]);
        }])->find($id)->sales;

        return $product;
        // return count($product);
    }

    public function search($search)
    {
        $sale = new Sale();
        return $sale->search($search);
    }

    public function latest_orders()
    {
        return Sale::where('delivery_status', 'Delivered')->orderBy('delivered_on', 'DESC')->with(['user', 'products' => function ($q) {
            $q->setEagerLoads([]);
        }, 'client', 'orderHistory' => function ($q) {
            $q->setEagerLoads([]);
        }])->take(10)->paginate();
    }

    public function user()
    {
        return auth()->user();
    }


    public function elementor1(Request $request)
    {
        // Log::alert($request->all());

        $data = array(
            'Name' => 'Deeka Nwiganale',
            'Phone_Number' => '8183585812',
            'WhatsappNumber' => '0',
            'Delivery_Address' => '+2348183585812',
            'Region' => 'Rivers',
            'Quantity' => 'Two Smart Projectors = TSH 630,000',
            'Message' => NULL,
            'Date' => 'August 6, 2024',
            'Time' => '10:27 am',
            'Page_URL' => 'https://shopnownow.com.ng/miniprojectortanzania/',
            'User_Agent' => 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/127.0.0.0 Mobile Safari/537.36',
            'Remote_IP' => '105.112.179.105',
            'Powered_by' => 'Elementor',
            'form_id' => '45b194e',
            'form_name' => 'Tanzania Projector',
        );

        // Parse the quantity and price
        list($product_name, $price) = explode(' = TSH ', $data['Quantity']);
        $price = str_replace(',', '', $price); // Remove commas for correct decimal formatting

        // Create the sale
        $sale = Sale::create([
            'order_no' => uniqid(), // Generate a unique order number
            'name' => $data['Name'],
            'address' => $data['Delivery_Address'],
            'phone' => $data['Phone_Number'],
            'alt_phone' => $data['WhatsappNumber'],
            'city' => $data['Region'],
            'price' => (float) $price,
            'product_name' => $product_name,
            'notes' => $data['Message']
        ]);

        DB::beginTransaction();

        try {
            //code...
            // $ou = Country::where()
            if (!$client) {
                $client = Client::create([
                    'ou_id' => 2,
                    'seller_id' => $seller_id,
                    'name' => $data['Name'],
                    'email' => $customer_email,
                    'phone' => $data['Phone_Number'],
                    'alt_phone' => $$data['WhatsappNumber'],
                    'address' => $data['Delivery_Address'],
                    'city' => $data['Region']
                ]);
            }


            foreach ($request->line_items as $key => $line_item) {
                $product_sku = $line_item['variant']['sku'];
                $quantity = $line_item['quantity'];
                $price = $line_item['price'];
                $total += $price;

                $product = Product::where('sku_no', $product_sku)->first();

                if (!$product) {
                    abort(422, 'Product with SKU ' . $product_sku .  ' does not exist');
                }
            }
            $sub_total = $total;
            if ($seller_id == 277) {
                $total += $shipping_charge;
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function elementor(Request $request)
    {
        $data = $request->all();



        // $data = array(
        //     'Name' => 'Deeka Nwiganale',
        //     'Phone_Number' => '8183585812',
        //     'WhatsappNumber' => '0',
        //     'Delivery_Address' => '+2348183585812',
        //     'Region' => 'Rivers',
        //     'Quantity' => 'Two Smart Projectors = TSH 630,000',
        //     'Message' => NULL,
        //     'Date' => 'August 6, 2024',
        //     'Time' => '10:27 am',
        //     'Page_URL' => 'https://shopnownow.com.ng/miniprojectortanzania/',
        //     'User_Agent' => 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/127.0.0.0 Mobile Safari/537.36',
        //     'Remote_IP' => '105.112.179.105',
        //     'Powered_by' => 'Elementor',
        //     'form_id' => '45b194e',
        //     'form_name' => 'Tanzania Projector'
        // );
        $seller_id = 346;
        // Parse the quantity and price
        list($product_name, $price) = explode(' = TSH ', $data['Quantity']);
        $price = str_replace(',', '', $price); // Remove commas for correct decimal formatting

        DB::beginTransaction();
        $product = Product::where('product_name', $product_name)->where('vendor_id', $seller_id)->first();
        $user = User::first();

        if (!$product) {
            $item = new Product;
            $arr = ['product_name' => $product_name, 'vendor_id' => $seller_id];
            $item->product_create($arr, $user);
        }

        try {
            // Create the sale

            // Create or update client
            $client = Client::updateOrCreate(
                ['phone' => $data['Phone_Number']],
                [
                    'ou_id' => 2, // Assuming this is a constant
                    'seller_id' => $seller_id, // You need to define this variable
                    'name' => $data['Name'],
                    // 'email' => $customer_email, // You need to define this variable
                    'alt_phone' => $data['WhatsappNumber'],
                    'address' => $data['Delivery_Address'],
                    'city' => $data['Region']
                ]
            );

            $sale = Sale::create([
                'order_no' => uniqid(),
                'ou_id' => 2,
                'user_id' => $user->id,
                'seller_id' => $seller_id, // You need to define this variable
                // 'name' => $data['Name'],
                // 'address' => $data['Delivery_Address'],
                // 'phone' => $data['Phone_Number'],
                // 'alt_phone' => $data['WhatsappNumber'],
                // 'city' => $data['Region'],
                'price' => (float) $price,
                // 'product_name' => $product_name,
                'notes' => $data['Message'],
                'client_id' => $client->id
            ]);
            // Process line items (if available)
            // if (isset($request->line_items)) {
            //     foreach ($request->line_items as $line_item) {
            $product_sku = $product->sku_no;
            $quantity = 1;

            // $product = Product::where('sku_no', $product_sku)->first();

            // Here you can add logic to associate the product with the sale
            // For example:
            // $sale->products()->attach($product->id, ['quantity' => $quantity, 'price' => $item_price]);

            $sku_id = Sku::where('sku_no', $product_sku)->first('id');
            $product_sale = new ProductSale();
            $product_sale->sale_id = $sale->id;
            $product_sale->product_id = $product->id;
            $product_sale->seller_id = $seller_id;
            $product_sale->sku_id = $sku_id->id;
            $product_sale->sku_no = $product_sku;
            $product_sale->price = $price;

            $product_sale->quantity = $quantity;

            $product_sale->quantity_tobe_delivered = $quantity;
            $product_sale->total_price = $product_sale->price * $product_sale->quantity;
            $product_sale->save();
            // }
            // }

            // $sub_total = $total;


            // Update the sale with the calculated total
            $sale->update(['price' => $price]);

            DB::commit();
            return response()->json(['message' => 'Order processed successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            throw ($e);
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function bulk_orders(Request $request)
    {
        $orders = $request->all();
        $results = [
            'successful' => [],
            'failed' => []
        ];

        // Process orders in chunks of 10
        foreach (array_chunk($orders, 20) as $orderChunk) {
            DB::beginTransaction();
            try {
                // Collect all SKUs to pre-fetch products
                $allSkus = collect($orderChunk)->flatMap(function ($order) {
                    return collect($order['line_items'] ?? [])->pluck('variant.sku');
                })->unique()->values()->all();

                // Pre-fetch all products for this chunk
                $products = Product::whereIn('sku_no', $allSkus)
                    ->with('skus')
                    ->get()
                    ->keyBy('sku_no');

                // Pre-fetch all warehouses
                $warehouses = Warehouse::all()->keyBy('ou_id');

                foreach ($orderChunk as $order) {
                    $request = new Request($order);
                    $order_no = ($request->reference_id) ?  $request->reference_id :  $request->order_no;

                    if (!$order_no) {
                        $results['failed'][] = [
                            'order_no' => 'unknown',
                            'error' => 'Order number is required'
                        ];
                        continue;
                    }

                    if (Sale::where('order_no', $order_no)->exists()) {
                        $results['failed'][] = [
                            'order_no' => $order_no,
                            'error' => 'Order number already exists'
                        ];
                        continue;
                    }

                    $validator = Validator::make($request->all(), [
                        'order_no' => 'required|string',
                        'customer.email' => 'nullable|email',
                        'customer.full_name' => 'required|string',
                        'customer.mobile' => 'required|string',
                        'shipping_address.address' => 'required|string',
                        'shipping_address.city' => 'nullable|string',
                        'shipping_address.country' => 'nullable|string',
                        'shipping_address.full_name' => 'required|string',
                        'shipping_address.latitude' => 'nullable',
                        'shipping_address.longitude' => 'nullable',
                        'shipping_address.region' => 'nullable|string',
                        'shipping_address.zipcode' => 'nullable|string',
                        'line_items' => 'required|array',
                        'line_items.*.price' => 'required',
                        'line_items.*.quantity' => 'required|integer',
                        'line_items.*.variant.sku' => 'required|string',
                    ]);

                    if ($validator->fails()) {
                        $results['failed'][] = [
                            'order_no' => $order_no,
                            'error' => $validator->errors()->first()
                        ];
                        continue;
                    }

                    try {
                        $weight = $request->weight;
                        $delivery_address = ($request->delivery_address) ? $request->delivery_address : $request->shipping_address;
                        $pickup = $request->pickup_address;
                        
                        $customer = $request->customer;
                        $customer_name = ($customer['full_name']) ? $customer['full_name'] : $customer['full_name'] . ' ' . $customer['last_name'];
                        $customer_email = (array_key_exists('email', $customer)) ? $customer['email'] : null;
                        $customer_mobile = $customer['mobile'];
                        $address = (array_key_exists('address', $delivery_address)) ? $delivery_address['address'] : null;
                        $city = (array_key_exists('city', $delivery_address)) ? $delivery_address['city'] : null;
                        
                        // Find or create client in a single query
                        $client = Client::firstOrCreate(
                            [
                                'phone' => $customer_mobile,
                                'name' => $customer_name
                            ],
                            [
                                'ou_id' => 1, // Default OU
                                'seller_id' => auth('api')->id(),
                                'email' => $customer_email,
                                'address' => $address,
                                'city' => $city
                            ]
                        );

                        $total = 0;
                        $line_items_data = [];

                        // Process line items and calculate total
                        foreach ($request->line_items as $line_item) {
                            $product_sku = $line_item['variant']['sku'];
                            $product = $products->get($product_sku);

                            if (!$product) {
                                throw new \Exception("Product with SKU {$product_sku} does not exist");
                            }

                            $quantity = $line_item['quantity'];
                            $price = $line_item['price'];
                            $total += $price;

                            $line_items_data[] = [
                                'product_id' => $product->id,
                                'seller_id' => $product->vendor_id,
                                'sku_id' => $product->skus->first()->id,
                                'sku_no' => $product_sku,
                                'price' => $price,
                                'quantity' => $quantity,
                                'quantity_tobe_delivered' => $quantity,
                                'total_price' => $price * $quantity,
                                'created_at' => now(),
                                'updated_at' => now()
                            ];
                        }

                        $seller_id = auth('api')->id();
                        $shipping_charge = ($request->shipping_charge) ? $request->shipping_charge : 0;

                        if ($seller_id == 277 || $seller_id == 344) {
                            $total += $shipping_charge;
                        }

                        // Create sale with all data at once
                        $sale = Sale::create([
                            'total_price' => $total,
                            'sub_total' => $total - $shipping_charge,
                            'user_id' => 1,
                            'client_id' => $client->id,
                            'seller_id' => $seller_id,
                            'customer_notes' => $request->customer_notes,
                            'order_no' => $order_no,
                            'weight' => $weight,
                            'latitude' => $delivery_address['latitude'] ?? null,
                            'longitude' => $delivery_address['longitude'] ?? null,
                            'paid' => $request->paid,
                            'payment_method' => $request->payment_method,
                            'shipping_charges' => $shipping_charge,
                            'delivery_status' => $request->delivery_status ?? 'New',
                            'status' => (Str::lower($request->delivery_status ?? 'New') == 'scheduled') ? 'Confirmed' : 'New',
                            'delivery_date' => $request->delivery_date ?? Carbon::tomorrow(),
                            'ou_id' => 1,
                            'warehouse_id' => $warehouses->get(1)->id,
                            'platform' => 'API',
                            'pickup_address' => $pickup['address'] ?? null,
                            'pickup_phone' => $pickup['phone'] ?? null,
                            'pickup_city' => $pickup['city'] ?? null,
                            'pickup_shop' => $pickup['shop'] ?? null
                        ]);

                        // Bulk insert product sales
                        foreach ($line_items_data as &$item) {
                            $item['sale_id'] = $sale->id;
                        }
                        ProductSale::insert($line_items_data);

                        $order = Sale::with(['orderHistory' => function ($query) {
                            $query->setEagerLoads([]);
                        }])->find($sale->id);

                        $results['successful'][] = [
                            'order_no' => $order_no,
                        ];

                    } catch (\Throwable $e) {
                        $results['failed'][] = [
                            'order_no' => $order_no,
                            'error' => $e->getMessage()
                        ];
                        continue;
                    }
                }

                DB::commit();
            } catch (\Throwable $e) {
                DB::rollBack();
                // Log::error('Error processing order chunk: ' . $e->getMessage());
            }
        }

        return response()->json([
            'message' => 'Order processing completed',
            'summary' => [
                'total_orders' => count($orders),
                'successful_orders' => count($results['successful']),
                'failed_orders' => count($results['failed'])
            ],
            'results' => $results
        ]);
    }
    
public function bulk_products(Request $request)
{
    // Validate incoming request
    $validator = Validator::make($request->all(), [
        '*.description' => 'required|string',
        '*.name' => 'required|string|max:255',
        '*.sku' => 'required|string|max:50'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'message' => 'Validation failed',
            'errors' => $validator->errors()
        ], 422);
    }

    $products = $request->all();
    $totalProducts = count($products);
    $successfulProducts = 0;
    $failedProducts = 0;
    $successful = [];
    $failed = [];

    // Get authenticated seller
    $seller = auth('api')->user();
    if (!$seller) {
        return response()->json([
            'message' => 'Unauthenticated'
        ], 401);
    }

    $user = User::first(); // Consider if this is really needed or should be modified

    foreach ($products as $product) {
        try {
            // Check if product SKU already exists for this vendor
            $exists = Product::where('vendor_id', $seller->id)
                           ->where('sku_no', $product['sku'])
                           ->exists();

            if ($exists) {
                throw new \Exception('Product with SKU already exists');
            }

            DB::beginTransaction();

            $data = [
                'product_name' => $product['name'],
                'description' => $product['description'],
                'sku_no' => $product['sku'],
                'vendor_id' => $seller->id
            ];

            $newProduct = new Product();
            $newProduct->product_create($data, $user);

            DB::commit();

            $successfulProducts++;
            $successful[] = [
                'sku_no' => $product['sku']
            ];

        } catch (\Throwable $e) {
            DB::rollBack();
            
            $failedProducts++;
            $failed[] = [
                'sku_no' => $product['sku'],
                'error' => $e->getMessage()
            ];
        }
    }

    return response()->json([
        'message' => 'Products processing completed',
        'summary' => [
            'total_products' => $totalProducts,
            'successful_products' => $successfulProducts,
            'failed_products' => $failedProducts,
        ],
        'results' => [
            'successful' => $successful,
            'failed' => $failed,
        ]
    ]);
}
}
