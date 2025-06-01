<?php

namespace App\Traits;

use App\Models\ShipmentProduct;
use App\Models\ShipmentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait ShipmentTrait
{
    public function createShipment(array $shipmentData)
    {
        DB::beginTransaction();

        try {
            // Create a shipment request
            $shipment = ShipmentRequest::create([
                'seller_id' => $shipmentData['seller_id'],
                'arrival_date' => $shipmentData['arrival_date'],
                'warehouse_id' => $shipmentData['warehouse_id'],
                'description' => $shipmentData['description'],
            ]);

            // Add requested products to the shipment
            // foreach ($shipmentData['products'] as $productData) {
            //     ShipmentProduct::create([
            //         'shipment_id' => $shipment->id,
            //         'product_id' => $productData['id'],
            //         'quantity' => $productData['quantity'],
            //     ]);
            // }

            DB::commit();
            return $shipment;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
    public function createShipment_(array $shipmentData)
    {
        DB::beginTransaction();

        try {
            // Create a shipment request
            $shipment = ShipmentRequest::create([
                'seller_id' => $shipmentData['seller_id'],
                'arrival_date' => $shipmentData['arrival_date'],
                'warehouse_id' => $shipmentData['warehouse_id'],
            ]);

            // Add requested products to the shipment
            foreach ($shipmentData['products'] as $productData) {
                ShipmentProduct::create([
                    'shipment_id' => $shipment->id,
                    'product_id' => $productData['id'],
                    'quantity' => $productData['quantity'],
                ]);
            }

            DB::commit();
            return $shipment;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function receiveShipment(Request $request)
    {
        // Update the status of the request to "Received"
        $request->update(['status' => 'Received']);

        // Update warehouse inventory
        foreach ($request->requestedProducts as $requestedProduct) {
            $product = $requestedProduct->product;
            // $warehouseInventory = WarehouseInventory::where('product_id', $product->id)->first();

            // if ($warehouseInventory) {
            //     // Update existing inventory
            //     $warehouseInventory->increment('quantity', $requestedProduct->quantity);
            // } else {
            //     // Create new inventory record
            //     WarehouseInventory::create([
            //         'product_id' => $product->id,
            //         'quantity' => $requestedProduct->quantity,
            //     ]);
            // }
        }
    }
}
