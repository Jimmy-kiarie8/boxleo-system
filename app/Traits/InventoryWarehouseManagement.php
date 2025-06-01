<?php

namespace App\Traits;

use App\Meta;
use App\Models\DailyStats;
use App\Models\ProductSale;
use App\Models\Warehouse\Product_warehouse;
use App\Models\Warehouse\ProductBin;

trait InventoryWarehouseManagement
{

    // public function updateInventory($quantity)
    // {
    //     // Logic to update the product inventory
    //     $this->product_inventory += $quantity;
    //     $this->save();
    // }

    public function addToWarehouse($warehouseId, $binId)
    {
        // Logic to add the product to the specified warehouse and bin
        $productBin = new ProductBin();
        $productBin->product_id = $this->id;
        $productBin->warehouse_id = $warehouseId;
        $productBin->bin_id = $binId;
        $productBin->save();
    }


    public function updateInventories($product, $warehouseId, $quantity, $status, $oldStatus)
    {
        $productSale = ProductSale::find($product['pivot']['id']);
        $productWarehouse = new Product_warehouse();
        $sellerId = $product['pivot']['seller_id'];
        $productId = $product['pivot']['product_id'];
        $dailyStats = new DailyStats();

        if ($status == 'Delivered' && !$product['pivot']['delivered']) {
            if (!$productSale->delivered) {
                $productWarehouse->deliveredItems($warehouseId, $sellerId, $productId, $quantity, $status, $oldStatus);
            }
            $productSale->sent = true;
            $productSale->delivered = true;
            $productSale->quantity_delivered = $quantity;
            $productSale->save();

            $dailyStats->deliveredCount($quantity, $productId);
        } elseif (!$product['pivot']['sent']) {
            if ($status == 'Dispatched') {
                $productSale->quantity_sent = $quantity;
                $dailyStats->dispatchedCount($quantity, $productId);
            }
            if ($status == 'Scheduled') {
                // $dailyStats->scheduledCount($quantity, $productId);
            }
            if (!$productSale->sent) {
                $productWarehouse->reduceItems($warehouseId, $sellerId, $productId, $quantity, $status, $oldStatus);

                $productSale->sent = true;
                $productSale->save();
            }
        }
    }

    public function warehouse($sale, $saleUpdate, $warehouseId, $status, $oldStatus, $partialDelivery = false)
    {
        // foreach ($saleUpdate['products'] as $product) {
        //     $quantity = ($partialDelivery) ? $product['pivot']['quantity_delivered'] : $product['pivot']['quantity_tobe_delivered'] ?: $product['pivot']['quantity'];

        //     $this->updateInventory($product, $warehouseId, $quantity, $status, $oldStatus);
        // }
    }
}
