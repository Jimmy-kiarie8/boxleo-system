<?php

namespace App\Http\Controllers;

use App\Jobs\WebhookDispatchJob;
use App\Models\AutoGenerate;
use App\Models\Category;
use App\Models\Company;
use App\Models\Product;
use App\Models\ProductInventory;
use App\Models\ProductReceive;
use App\Models\Warehouse\Product_warehouse;
use App\Models\ProductSale;
use App\Models\Sale;
use App\Models\settings\Webhook;
use App\Models\Setup;
use App\Models\Sku;
use App\Seller;
use App\Models\User;
use App\Models\Warehouse\Warehouse;
use App\Scopes\ProductScope;
use App\Services\ProductService;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use PDF; // Make sure to import the PDF class if it's not already imported

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
        // return Product::all();
        // return $products = Product::paginate(10);
        if (Auth::check()) {

            $cacheKey = 'products_' . Auth::user()->ou_id;

            return Cache::remember($cacheKey, now()->addHours(2), function () {
                return Product::with('images')->paginate(500);
            });
        }
        return Product::with('images')->paginate(500);

        // $product_transform = new Product;
        // return $product_transform->transform_product($products);
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


        if (Auth::guard('seller')->check()) {
            $user = Auth::guard('seller')->user();
            $vendor_id = $user->id;
        } else {
            $this->authorize('create', Product::class);
            $vendor_id = $request->vendor_id;
            $user = Auth::user();
        }
        // $product = new Product();
        // $product->product_name = $request->product_name;
        // $product->user_id = $this->logged_user()->id;
        // $product->vendor_id = $request->vendor_id;
        // $product->sku_no =  IdGenerator::generate(['table' => 'products', 'field' => 'sku_no', 'length' => 15, 'prefix' => 'SKU_']);
        // $product->save();
        $data = [];

        $produt_exist = Product::where('vendor_id', $vendor_id)->where('product_name', $request->product_name)->exists();

        if ($produt_exist) {
            abort(422, 'Product exists');
        }

        // $sku_no =  IdGenerator::generate(['table' => 'products', 'field' => 'sku_no', 'length' => 15, 'prefix' => 'SKU_']);
        $sku_no = make_reference_id('SKU', Sku::max('id') + 1);

        $data['price'] = $request->price;
        $data['sku_no'] = $sku_no;
        $data['vendor_id'] = $vendor_id;
        $data['product_name'] = $request->product_name;
        $product = new Product;
        $product->product_create($data, $user);

        $setup = new Setup();
        $setup->updateSetup('products');
        $cacheKey = 'products_' . Auth::user()->ou_id;
        Cache::forget($cacheKey);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $cacheKey = 'product_' . $id;

        // return Cache::remember($cacheKey, now()->addHours(2), function () use($id) {
        $products = Product::withoutGlobalScope(ProductScope::class)->with(['dailyStats' => function ($query) {
            $query->take(7)->latest();
        }, 'inventory' => function ($query) {
            $query->setEagerLoads([]);
        }, 'warehouses' => function ($query) {
            $query->setEagerLoads([]);
        }, 'seller' => function ($query) {
            $query->setEagerLoads([]);
        }, 'bins' => function ($query) {
            $query->setEagerLoads([]);
        }])->where('id', $id)->get();
        /* $product_t = new Product();
        return $product_t->transform_display_product($products); */
        // $products = Product::with(['warehouses', 'productHistory'])->where('id', $id)->get();

        $product_transform = new Product;
        $product_transform = $product_transform->transform_display_product($products);
        return (!empty($product_transform)) ? $product_transform[0] : [];
        // });
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->authorize('update', Product::find($id));
        $warehouse = Warehouse::first();


        $user = $this->logged_user();

        // return $request->all();

        $sku_values = $request->sku_values;
        $product = $request->product;
        // return $request->product['subcategories'];
        Sku::updateOrCreate(
            [
                'product_id' => $id,
            ],
            [
                'buying_price' => $sku_values['buying_price'],
                'price' => $sku_values['price'],
                'sku_no' => $product['sku_no'],
                'quantity' => $sku_values['quantity'],
                'reorder_point' => $sku_values['reorder_point']
            ]
        );

        if ($product['stock_management'] == '1') {
            $inv_array = $product['inventory'];
            foreach ($inv_array as  $item) {
                ProductInventory::updateOrCreate(
                    [
                        'warehouse_id' => ($item['warehouse_id']) ? $item['warehouse_id'] : $warehouse->id,
                        'product_id' => $id,
                        'seller_id' => $product['vendor_id']
                    ],
                    [
                        'onhand' => $item['onhand'],
                        'available_for_sale' => $item['onhand'],
                        'user_id' => $user->id
                    ]
                );
            }
        }

        $relation = new VariantController;
        $update_product = Product::find($id);
        $update_product->virtual = $product['virtual'];
        $update_product->active = $product['active'];
        $update_product->stock_management = $product['stock_management'];
        // $update_product->save();

        $relation->category_fun($request->product['categories'], $update_product);
        $relation->subcategory_fun($request->product['subcategories'], $update_product);
        $relation->brand_fun($request->product['brands'], $update_product);
        // return $update_product;

        // $product_update = Product::find($id);
        $update_product->active = $product['active'];
        $update_product->product_name = $product['product_name'];
        $update_product->update_comment = 'Product updated by ' . $user->name;
        $update_product->vendor_id = $product['vendor_id'];
        $update_product->sku_no = $product['sku_no'];
        $update_product->save();
        return 'success';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        $this->authorize('update', $product);
        $product->delete();
    }

    public function product_image(Request $request, $id)
    {
        // dd($request->image);
        $upload = Product::find($request->id);
        if ($request->hasFile('image')) {
            $img = $request->image;
            // dd($upload->image);
            if (File::exists($upload->image)) {
                // return ('test');
                $image_path = $upload->image;
                File::delete($image_path);
            }
            $imagename = Storage::disk('public')->put('products', $img);
            // return ('noop');
            $imgArr = explode('/', $imagename);
            $image_name = $imgArr[1];
            $upload->image = '/storage/products/' . $image_name;
            // $upload->image = env('APP_URL') . '/storage/products/' . $image_name;
            $upload->save();
            return $upload;
        }
    }

    public function product_search($search)
    {
        return Product::with(['warehouses'])->where('active', true)
            ->where('sku_no', 'LIKE', "%{$search}%")
            ->orWhere('product_name', 'LIKE', "%{$search}%")
            // ->orWhere('product_barcode', 'LIKE', "%{$search}%")
            // ->orWhere('description', 'LIKE', "%{$search}%")
            ->paginate(100);
    }


    public function product_filter_search(Request $request)
    {
        $search = $request->search;
        if (Auth::guard('seller')->check()) {
            $vendor_id = Auth::id();
        } else {
            $vendor_id = $request->vendor_id;
        }
        $vendors = $request->vendors;
        $products = Product::with(['warehouses'])->where('active', true)
            ->when($vendor_id, function ($q) use ($vendor_id) {
                return $q->where('vendor_id', $vendor_id);
            })
            ->when((!empty($vendors)), function ($q) use ($vendors) {
                return $q->where('vendor_id', $vendors);
            })
            ->where('product_name', 'LIKE', "%{$search}%")
            // ->orWhere('product_barcode', 'LIKE', "%{$search}%")
            // ->orWhere('description', 'LIKE', "%{$search}%")
            ->paginate(100);


        $product_t = new Product();
        return $product_t->transform_display_product($products);
    }

    public function product_table()
    {
        // return Product::all();
        // return $products = Product::paginate(10);
        $active = (request()->type == 'inactive') ? false : true;

        // if (Auth::check()) {

        //     // $cacheKey = 'products_' . Auth::user()->ou_id;

        //     return Cache::remember('product_table', now()->addHours(2), function () use ($active) {
        //         $products = Product::withoutGlobalScope(ProductScope::class)->with(['inventory' => function ($query) {
        //             $query->setEagerLoads([]);
        //         }, 'warehouses' => function ($query) {
        //             $query->setEagerLoads([]);
        //         }, 'seller' => function ($query) {
        //             $query->setEagerLoads([]);
        //         }, 'bins' => function ($query) {
        //             $query->setEagerLoads([]);
        //         }])->where('active', $active)->paginate(500);
        //         $product_t = new Product();
        //         return $product_t->transform_display_product($products);
        //     });
        // }

        $products = Product::withoutGlobalScope(ProductScope::class)->with(['inventory' => function ($query) {
            $query->setEagerLoads([]);
        }, 'warehouses' => function ($query) {
            $query->setEagerLoads([]);
        }, 'seller' => function ($query) {
            $query->setEagerLoads([]);
        }, 'bins' => function ($query) {
            $query->setEagerLoads([]);
        }])->where('active', $active)->paginate(500);
        $product_t = new Product();
        return $product_t->transform_display_product($products);
    }


    public function category_products($category)
    {
        // return $category;
        $category =  Category::with('products')->where('category', $category)->first();
        // $category = Category::first();
        if ($category) {
            $products = $category->products()->paginate(10);

            $product_transform = new Product;
            return $product_transform->transform_product($products);
        }
    }

    public function product_transactions($id)
    {
        $sales = new ProductSale;
        $sale_arr = $sales->product_sales($id);
        $status_arr = ['Scheduled', 'Dispatched', 'Delivered', 'Returned', 'In Transit', 'Cancelled'];
        $sales =  Sale::setEagerLoads([])->with(['products' => function ($q) {
            $q->withoutGlobalScope(ProductScope::class)->select('products.id', 'products.product_name')->setEagerLoads([]);
        }, 'services' => function ($q) {
            $q->select('services.id', 'services.name')->setEagerLoads([]);
        }, 'client' => function ($q) {
            $q->select('clients.id', 'clients.name', 'clients.phone', 'clients.address')->setEagerLoads([]);
        }])->whereIn('id', $sale_arr)->whereIn('delivery_status', $status_arr)->paginate(100);



        $counts = Sale::setEagerLoads([])->with(['products' => function ($q) {
            $q->withoutGlobalScope(ProductScope::class)->select('products.id', 'products.product_name')->setEagerLoads([]);
        }, 'services' => function ($q) {
            $q->select('services.id', 'services.name')->setEagerLoads([]);
        }, 'client' => function ($q) {
            $q->select('clients.id', 'clients.name', 'clients.phone', 'clients.address')->setEagerLoads([]);
        }])->whereIn('id', $sale_arr)->whereIn('delivery_status', $status_arr)->select('delivery_status', DB::raw('COUNT(*) as count'))
            ->groupBy('delivery_status')
            ->pluck('count', 'delivery_status');

        $sale_trans = new Sale;
        $sales_collection = $sale_trans->trans_sales($sales, '', '');
        $data1 = $sales_collection->getData();
        $sales = $data1->sales;
        $columns = $data1->columns;
        $count = $counts;
        // return [
        //     $sales_collection,
        // ];

        return response()->json([
            'counts' => $count,
            'sales' => $sales,
            'columns' => $columns
        ], 200);
    }

    public function client_products($id)
    {
        if (Auth::guard('seller')->check()) {
            // $seller = Seller::find(Auth::guard('seller')->id());
            $seller = Auth::guard('seller')->user();
        } else {
            $seller = Seller::find($id);
        }
        return $seller->products()->paginate(100);
    }

    public function product_arr(Request $request, $id)
    {
        if (Auth::guard('seller')->check()) {
            // $seller = Seller::find(Auth::guard('seller')->id());
            $seller = Auth::guard('seller')->user();
        } else {
            $seller = Seller::find($id);
        }
        $products = $seller->products()->select('product_name')->setEagerLoads([])->get();

        // return $flattened = Arr::flatten($products);
        $array = [];

        foreach ($products as $product) {
            $array[] =   $product->product_name;
        }

        return ($array);
    }


    public function low_stock()
    {
        $pro_arr = Sku::whereRaw('quantity < reorder_point')->pluck('id');
        $products = Product::with(['warehouses'])->whereIn('id', $pro_arr)->paginate(500);


        $product_t = new Product();
        return $product_t->transform_display_product($products);
    }

    public function stock_update(Request $request)
    {
        foreach ($request->warehouses as  $item) {
            // return $item['price'];
            // return $item['add_qty'];

            Product_warehouse::updateOrCreate(
                [
                    'warehouse_id' => $item['id'],
                    'product_id' => $item['pivot']['product_id'],
                    'seller_id' => $item['pivot']['seller_id']
                ],
                [
                    'onhand' => $item['pivot']['onhand'] + $item['add_qty'],
                    'available_for_sale' => $item['pivot']['available_for_sale'] + $item['add_qty']
                    // 'user_id' => $user->id
                ]
            );
        }
    }


    public function product_filter(Request $request)
    {
        if (!$request->client) {
            return $this->product_table();
        }

        $products = Product::with([
            'inventory',
            'warehouses',
            'seller',
            'bins'
        ])
            ->where('vendor_id', $request->client)
            ->paginate(500);

        $productService = new ProductService();
        return $productService->transformDisplayProduct($products);
    }


    public function productFilter(Request $request)
    {
        if (!$request->client) {
            return $this->product_table();
        }
        $products = Product::with(['inventory' => function ($query) {
            $query->setEagerLoads([]);
        }, 'warehouses' => function ($query) {
            $query->setEagerLoads([]);
        }, 'seller' => function ($query) {
            $query->setEagerLoads([]);
        }, 'bins' => function ($query) {
            $query->setEagerLoads([]);
        }])->where('vendor_id', $request->client)->paginate(500);

        $product_t = new Product();
        return $product_t->transform_display_product($products);
    }

    public function product_status(Request $request, $status)
    {
        // return $request->all();

        if ($status == 'deactivate') {
            $active = false;
        } else {
            $active = true;
        }

        foreach ($request->all() as $product) {
            Product::withoutGlobalScope(ProductScope::class)->where('id', $product['id'])->update(['active' => $active]);
        }
    }
    public function product_delete(Request $request)
    {
        foreach ($request->all() as $key => $value) {
            Product::find($value['id'])->delete();
        }
    }

    public function checkProductMatch()
    {
        // Product being uploaded
        $product_name = 'NEW Lift Up Invisible Bra Tape';
        $existing_product_name = 'Pants';

        // Existing product in the database
        // $existing_product_name = Product::where('product_name', $product_name)->first();

        // Calculate the Levenshtein distance between the two strings
        return $distance = levenshtein($product_name, $existing_product_name);

        // Set the threshold for what constitutes a close enough match
        $threshold = 3;

        // If the distance is less than or equal to the threshold, consider the products a match
        if ($distance <= $threshold) {
            return "Product is a match";
        } else {
            return "Product is not a match";
        }
    }

    public function product_update1(Request $request, $id)
    {

        DB::beginTransaction();

        try {
            $sale = Sale::find($id);

            $sale->history_comment = '';
            $product_1 = '';

            foreach ($sale->products as $product) {
                $product_1 .= sprintf($product['product_name'], 'QTY: %d | %s', $product['pivot']['quantity']) . PHP_EOL;
            }


            $product_2 = '';


            $products = $request->all();

            $exist_id =  collect($products)->pluck('pivot.product_id');

            ProductSale::whereNotIn('product_id', $exist_id)->where('sale_id', $id)->delete();

            foreach ($products as $product) {
                $product_id = $product['pivot']['product_id'];
                $product_item = Product::find($product_id);
                $sku_id = Sku::where('product_id', $product_id)->first('id');
                $quantity = $product['pivot']['quantity'];
                $product_2 .= sprintf($product['product_name'], 'QTY: %d | %s', $product['pivot']['quantity']) . PHP_EOL;


                $productSale = ProductSale::where('product_id', $product_id)->where('sale_id', $id)->first();

                if (!$productSale) {
                    $productSale = new ProductSale;
                    $productSale->sale_id = $id;
                    $productSale->product_id = $product_id;
                    $productSale->seller_id = $sale->seller_id;
                    $productSale->quantity = $quantity;
                    $productSale->quantity_tobe_delivered = $quantity;
                    $productSale->price = $product_item->price;
                    $productSale->sku_no = $product_item->sku_no;
                    $productSale->sku_id = $sku_id->id;
                } else {
                    $productSale->quantity = $quantity;
                    $productSale->quantity_tobe_delivered = $quantity;
                    $productSale->product_id = $product_id;
                    $productSale->sku_no = $product_item->sku_no;
                    $productSale->sku_id = $sku_id->id;
                }

                $productSale->save();
            }



            $sale->history_comment = 'Product updated from <b>' . $product_1 . '</b> to <b>' . $product_2 . '</b>';
            $sale->save();


            DB::commit();
            // WebhookDispatchJob::dispatch($sale, 'product_updated', []);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw ($th);

            abort(422, $th->getMessage());
        }
    }


    public function product_update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $sale = Sale::with(['products' => function ($query) {
                $query->withoutGlobalScope(ProductScope::class)->setEagerLoads([]);
            }])->findOrFail($id);

            $originalProducts = $sale->products->pluck('pivot.quantity', 'id')->toArray();
            $originalProductDetails = $sale->products->pluck('product_name', 'id')->toArray();
            $product_1 = $this->formatProducts($originalProductDetails, $originalProducts);

            $requestProducts = collect($request->all());
            $requestedProductIds = $requestProducts->pluck('pivot.product_id')->toArray();

            // Delete removed products
            ProductSale::where('sale_id', $id)
                ->whereNotIn('product_id', $requestedProductIds)
                ->delete();

            $newProductDetails = [];
            $newProducts = [];
            $webhook = Webhook::where('vendor_id', $sale->seller_id)->exists();

            foreach ($requestProducts as $product) {
                $product_id = $product['pivot']['product_id'];
                $quantity = $product['pivot']['quantity'];

                $productItem = Product::findOrFail($product_id);
                $sku = Sku::where('product_id', $product_id)->firstOrFail();

                $productSale = ProductSale::updateOrCreate(
                    [
                        'sale_id' => $id,
                        'product_id' => $product_id,
                    ],
                    [
                        'seller_id' => $sale->seller_id,
                        'quantity' => $quantity,
                        'quantity_tobe_delivered' => $quantity,
                        'price' => $productItem->price,
                        'sku_no' => $productItem->sku_no,
                        'sku_id' => $sku->id,
                    ]
                );

                $newProducts[$product_id] = $quantity;
                $newProductDetails[$product_id] = $productItem->product_name;
            }

            $product_2 = $this->formatProducts($newProductDetails, $newProducts);

            $sale->user_id = Auth::id();
            $sale->history_comment = 'Product updated from <b>' . $product_1 . '</b> to <b style="color:#1564c0;">' . $product_2 . '</b>';
            $sale->save();

            DB::commit();

            if ($webhook) {
                WebhookDispatchJob::dispatch($sale, 'product_updated', []);
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(422, $th->getMessage());
        }
    }

    private function formatProducts(array $productDetails, array $quantities): string
    {
        $formattedProducts = '';

        foreach ($productDetails as $id => $productName) {
            $formattedProducts .= sprintf('%s: QTY: %d', $productName, $quantities[$id]) . PHP_EOL;
        }

        return $formattedProducts;
    }


    public function product_inventory(Request $request)
    {
        // return $request->all();
        $products = $request->all();
        foreach ($products as $key => $product) {
            $update_product = Product::find($product['id']);
            $update_product->virtual = false;
            $update_product->stock_management = 2;
            $update_product->save();
        }
    }

    public function product_analysis($id)
    {
        $product_sale = ProductSale::where('product_id', $id)->get();

        $sumQuantityDelivered = $product_sale->sum('quantity_delivered');
        $sumQuantityReturned = $product_sale->sum('quantity_returned');
        $sumQuantitySent = $product_sale->sum('quantity_sent');

        return [
            'quantity_delivered' => $sumQuantityDelivered,
            'quantity_returned' => $sumQuantityReturned,
            'quantity_intransit' => $sumQuantitySent
        ];
    }

    public function product_stats($productId)
    {
        $startDate = '2023-05-01';
        $endDate = '2023-08-31';
        $product = Product::find($productId);

        $sentData = $product->sales()
            ->whereBetween('sales.dispatched_on', [$startDate, $endDate])
            ->selectRaw('DATE(sales.dispatched_on) as date, SUM(product_sale.quantity_sent) as sent_quantity')
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        $deliveredData = $product->sales()
            ->whereBetween('sales.delivered_on', [$startDate, $endDate])
            ->selectRaw('DATE(sales.delivered_on) as date, SUM(product_sale.quantity_delivered) as delivered_quantity')
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        $returnData = $product->sales()
            ->whereBetween('sales.returned_on', [$startDate, $endDate])
            ->selectRaw('DATE(sales.returned_on) as date, SUM(product_sale.quantity_returned) as returned_quantity')
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        $receiveData = $product->receives()
            ->whereBetween('product_receives.created_at', [$startDate, $endDate])
            ->selectRaw('DATE(product_receives.created_at) as date, SUM(product_receives.quantity) as received_quantity')
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        $data = [];
        foreach ($sentData as $item) {
            $data[$item->date]['in_transit'] = $item->sent_quantity;
            $data[$item->date]['delivered'] = 0;
            $data[$item->date]['received'] = 0;
            $data[$item->date]['returned'] = 0;
        }
        foreach ($deliveredData as $item) {
            if (!isset($data[$item->date])) {
                $data[$item->date] = ['in_transit' => 0];
            }
            $data[$item->date]['delivered'] = $item->delivered_quantity;
            $data[$item->date]['received'] = 0;
            $data[$item->date]['returned'] = 0;
        }
        foreach ($receiveData as $item) {
            if (!isset($data[$item->date])) {
                $data[$item->date] = ['in_transit' => 0, 'delivered' => 0];
            }
            $data[$item->date]['received'] = (int)$item->received_quantity;
            $data[$item->date]['returned'] = 0;
        }
        foreach ($returnData as $item) {
            if (!isset($data[$item->date])) {
                $data[$item->date] = ['in_transit' => 0, 'delivered' => 0, 'received' => 0];
            }
            $data[$item->date]['returned'] = $item->returned_quantity;
        }

        $received = ProductReceive::whereDate('created_at', '<', $startDate)->where('product_id', $productId)->sum('quantity');
        $sent_count = $product->sales()
            ->selectRaw('DATE(sales.dispatched_on) as date, SUM(product_sale.quantity_sent) as sent_quantity')
            ->whereDate('sales.dispatched_on', '<', $startDate)
            ->sum('product_sale.quantity_sent');

        $delivered_count = $product->sales()
            ->selectRaw('DATE(sales.delivered_on) as date, SUM(product_sale.quantity_delivered) as delivered_quantity')
            ->whereDate('sales.delivered_on', '<', $startDate)
            ->sum('product_sale.quantity_sent');

        $return_count = $product->sales()
            ->selectRaw('DATE(sales.returned_on) as date, SUM(product_sale.quantity_returned) as returned_quantity')
            ->whereDate('sales.returned_on', '<', $startDate)
            ->sum('product_sale.quantity_sent');

        $running_balance = ($received + $return_count) - ($sent_count);
        // $balance = ($received + $return_count) - ($delivered_count + $sent_count)
        $company = Company::first();

        // Generate the PDF using the $data array
        $pdf = PDF::loadView('pdf.products.stock', ['data' => $data, 'product' => $product, 'running_balance' => $running_balance, 'company' => $company]);
        return $pdf->stream('stock_report.pdf');
    }
}
