<?php

namespace App\Services;

use App\Models\Transfer;
use App\Models\Warehouse\Bin;
use App\Models\Warehouse\ProductBin;
use App\Scopes\BinScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransferService
{
    public function stock_transfer($data)
    {
        DB::beginTransaction();
        try {
            $product = $data['product'];

            $product_id = $product['id'];
            $transfer_type = $data['transfer_type'];
            $warehouseFrom = $data['warehouseFrom'];
            $warehouse = $data['warehouse'];

            // $from_bin_id = $data['bin_id'];
            // $to_bin_id = $data['bin_to'];
            $qty_transfer = $product['transferQuantity'];





            // Create a transfer record
            $transfer = new Transfer();
            $transfer->product_id = $product_id;
            $transfer->to_warehouse_id = $warehouse;
            $transfer->from_warehouse_id = $warehouseFrom;
            // $transfer->to_bin_id = $to_bin_id;
            $transfer->quantity = $qty_transfer;
            $transfer->status = 'initiated';
            $transfer->transfer_type = $transfer_type;
            $transfer->save();

            // Get the source bin
            $from_bin = ProductBin::setEagerLoads([])->where('product_id', $product_id)->where('warehouse_id', $warehouseFrom)->first();

            if ($transfer_type == 'merchant') {
                // For merchant transfers, reduce stock immediately
                $from_bin->reduceStock($qty_transfer);

                // Update transfer status
                $transfer->status = 'completed';
                $transfer->save();

                // Handle the receiving bin
                $to_bin = ProductBin::setEagerLoads([])->where('product_id', $product_id)->where('warehouse_id', $warehouse)->first();
                $bin_to = Bin::withoutGlobalScope(BinScope::class)->setEagerLoads([])->select('id', 'warehouse_id')->where('id', $warehouse)->first();

                if (!$to_bin) {
                    $bin_model = new ProductBin();
                    $bin_model->createBin($bin_to->id, $product_id, $qty_transfer, $bin_to->warehouse_id);
                } else {
                    $to_bin->increaseStock($qty_transfer);
                }
            } else {
                // For warehouse to warehouse transfers, only reduce stock in the source warehouse
                $from_bin->transferSend($product_id, $qty_transfer);

                // Update transfer status
                $transfer->status = 'in_transit';
                $transfer->save();
            }
            DB::commit();

            return response()->json(['message' => 'Transfer initiated successfully', 'transfer_id' => $transfer->id]);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function receive_transfer($transferData, $data)
    {
        $transfer = Transfer::find($transferData['id']);
        DB::beginTransaction();
        try {

            if ($transfer->status != 'in_transit') {
                return response()->json(['message' => 'Invalid transfer status'], 400);
            }
            $to_bin = ProductBin::setEagerLoads([])->where('product_id', $transfer->product_id)->where('bin_id', $data['to_bin_id'])->first();
            $bin_to = Bin::withoutGlobalScope(BinScope::class)->setEagerLoads([])->select('id', 'warehouse_id')->where('id', $data['to_bin_id'])->first();


            if (!$to_bin) {
                $bin_model = new ProductBin();
                $bin_model->createBin($bin_to->id, $transfer->product_id, $transfer->quantity, $bin_to->warehouse_id);
            } else {
                $to_bin->transferReceive($transfer->product_id, $transfer->quantity);
            }


            // Update transfer status
            $transfer->faulty = $transferData['faulty'];
            $transfer->received = $transferData['quantity_received'];
            $transfer->status = 'completed';
            $transfer->save();
            DB::commit();

            return response()->json(['message' => 'Transfer received successfully']);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
