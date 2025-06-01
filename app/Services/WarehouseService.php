<?php

namespace App\Services;

use App\Models\Transfer;
use App\Models\ReceivingForm;
use App\Models\Shipment;
use App\Models\Dispatch;
use App\Models\Transaction;
use App\Models\StockMovement;
use App\Models\Warehouse\Bin;
use App\Models\Warehouse\ProductBin;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Notification;

class WarehouseService
{
    
    public function createTransfer($data)
    {
        $transfer = Transfer::create($data);
        $this->reduceStock($transfer->from_warehouse_id, $transfer->product_id, $transfer->quantity);
        $this->sendNotification($transfer->to_warehouse_id, 'New transfer request created');
        return $transfer;
    }

    public function notifyReceivingWarehouse($transferId)
    {
        $transfer = Transfer::find($transferId);
        $this->sendNotification($transfer->to_warehouse_id, 'Transfer incoming');
    }

    public function approveReceivingForm($data)
    {
        $receivingForm = ReceivingForm::create($data);
        $this->increaseStock($receivingForm->to_warehouse_id, $receivingForm->product_id, $receivingForm->quantity);
        // Generate PDF and send to Operations officer
        $pdfPath = $this->generatePdf($receivingForm);
        $receivingForm->update(['pdf_path' => $pdfPath]);
        $this->sendNotification($receivingForm->approved_by, 'Receiving form approved');
        return $receivingForm;
    }

    public function approveTransfer($data)
    {
        $transfer = Transfer::find($data['transfer_id']);
        // Approvals by Warehouse Manager, Security Personnel, and Merchant
        $transfer->update(['status' => 'approved']);
        $this->sendNotification($transfer->to_warehouse_id, 'Transfer approved by Merchant');
        $this->sendNotification($transfer->to_warehouse_id, 'Transfer details sent to Operations');
        return $transfer;
    }

    public function approveShipment($data)
    {
        $shipment = Shipment::find($data['shipment_id']);
        // Approval workflow: Warehouse Officer -> Operations Manager -> COO -> Finance -> CFO
        $shipment->update(['status' => 'approved']);
        $this->sendNotification($shipment->operations_manager_id, 'Shipment approved');
        return $shipment;
    }

    public function clearDispatch($data)
    {
        $dispatch = Dispatch::find($data['dispatch_id']);
        $this->reduceStock($dispatch->warehouse_id, $dispatch->product_id, $dispatch->quantity);
        $dispatch->update(['status' => 'cleared']);
        $this->sendNotification($dispatch->warehouse_officer_id, 'Dispatch cleared');
        return $dispatch;
    }

    public function getInventoryNumbers()
    {
        return ProductBin::with('bin.level.bay.row.area.warehouse')->get();
    }

    public function getTransactionReports($startDate, $endDate, $productId = null)
    {
        $query = Transaction::whereBetween('date', [$startDate, $endDate]);
        if ($productId) {
            $query->orWhere('product_id', $productId);
        }
        return $query->get();
    }

    public function getStockMovements()
    {
        return StockMovement::all();
    }

    public function getLowStockNotifications()
    {
        Notification::send();
        // return Notification::where('message', 'LIKE', '%low stock%')->get();
    }

    public function getBinLocationReports()
    {
        return Bin::with('level.bay.row.area.warehouse')->get();
    }

    private function sendNotification($userId, $message)
    {
        Notification::send();
    }

    private function generatePdf($data)
    {
        // Implement PDF generation logic
        $pdf = PDF::loadView('pdf.receiving_form', compact('data'));
        $filePath = storage_path('app/public/pdfs/') . 'receiving_form_' . $data->id . '.pdf';
        $pdf->save($filePath);
        return $filePath;
    }

    private function increaseStock($warehouseId, $productId, $quantity)
    {
        $productBin = ProductBin::where('warehouse_id', $warehouseId)
                                ->where('product_id', $productId)
                                ->first();
        if ($productBin) {
            $productBin->increaseStock($quantity);
        } else {
            ProductBin::create([
                'warehouse_id' => $warehouseId,
                'product_id' => $productId,
                'quantity' => $quantity,
            ]);
        }
    }

    private function reduceStock($warehouseId, $productId, $quantity)
    {
        $productBin = ProductBin::where('warehouse_id', $warehouseId)
                                ->where('product_id', $productId)
                                ->first();
        if ($productBin) {
            $productBin->reduceStock($quantity);
        } else {
            throw new \Exception('Product not found in warehouse');
        }
    }
}
