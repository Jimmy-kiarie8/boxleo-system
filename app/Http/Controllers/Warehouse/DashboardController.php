<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Sku;
use App\Models\Warehouse\Bin;
use App\Models\Warehouse\ProductBin;
use App\Models\Warehouse\Warehouse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function lowStock()
    {
        // return 3;
        $products = Sku::with(['product' => function ($query) {
            $query->select('id')->setEagerLoads([]);
        }])->get();
        $total = 0;
        // foreach ($products as  $product) {
        //     if ($product->product) {
        //         $count = ProductBin::where('product_id', $product->product['id'])->sum('onhand');
        //         if ($count < $product->reorder_point) {
        //             $total += 1;
        //         }
        //     }
        // }
        return  $total;
    }
    public function stock_count()
    {
        return ProductBin::sum('onhand');
    }
    public function bins_count()
    {
        return Bin::count();
    }
    public function commited_count()
    {
        return ProductBin::sum('commited');
    }
    public function available_count()
    {
        return ProductBin::sum('available_for_sale');
    }
    public function dispatch_count()
    {
        return ProductBin::sum('commited');
    }
    public function product_count()
    {
        return Product::count();
    }
    public function onhand_count()
    {
        return ProductBin::sum('onhand');
    }
    public function warehouse_count()
    {
        return Warehouse::setEagerLoads([])->count();
    }
    public function received_count()
    {
        return 0;
    }
    public function dispatched_products()
    {
        return 0;
    }
    public function random_products()
    {
        //    return 0;

        $products = Product::with(['inventory' => function ($query) {
            $query->setEagerLoads([]);
        }, 'warehouses' => function ($query) {
            $query->setEagerLoads([]);
        }, 'seller' => function ($query) {
            $query->setEagerLoads([]);
        }, 'bins' => function ($query) {
            $query->setEagerLoads([]);
        }])->inRandomOrder()->take(3)->get();
        $product_t = new Product();
        return $product_t->transform_display_product($products);
    }

    public function dashboard_data()
    {
        $data = [];
        $data['lowStock'] = $this->lowStock();
        $data['stock_count'] = $this->stock_count();
        $data['bins_count'] = $this->bins_count();
        $data['commited_count'] = $this->commited_count();
        $data['available_count'] = $this->available_count();
        $data['dispatch_count'] = $this->dispatch_count();
        $data['product_count'] = $this->product_count();
        $data['onhand_count'] = $this->onhand_count();
        $data['warehouse_count'] = $this->warehouse_count();
        $data['received_count'] = $this->received_count();
        $data['dispatched_products'] = $this->dispatched_products();
        $data['random_products'] = $this->random_products();

        return $data;
    }
}
