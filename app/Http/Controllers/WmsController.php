<?php

namespace App\Http\Controllers;

use App\Models\Transfer;
use App\Services\DataTransformService;
use App\Services\WarehouseService;
use Illuminate\Http\Request;

class WmsController extends Controller
{
    protected $warehouseService;

    public function __construct(WarehouseService $warehouseService)
    {
        $this->warehouseService = $warehouseService;
    }

    public function index()
    {

        $jsonFile = public_path('data/transfer.json'); // Get the full path to the JSON file

        $trans = new DataTransformService;
        $jsonData = $trans->data_transform($jsonFile);


        // $headers = [];
        // $headers[] = ['title' => 'Created At', 'key' => 'created_at'];


        // foreach ($jsonData as $item) {
        //     if ($item['table_display']) {
        //         $headers[] = [
        //             'title' => $item['label'],
        //             'key' => $item['model']
        //         ];
        //     }
        // }
        $headers[] = ['title' => 'Actions', 'key' => 'actions'];


        return  $jsonData;
    }

    public function createTransfer(Request $request)
    {
        $transfer = $this->warehouseService->createTransfer($request->all());
        return response()->json($transfer);
    }

    public function notifyReceivingWarehouse($transferId)
    {
        $this->warehouseService->notifyReceivingWarehouse($transferId);
        return response()->json(['message' => 'Notification sent']);
    }

    public function approveReceivingForm(Request $request)
    {
        $receivingForm = $this->warehouseService->approveReceivingForm($request->all());
        return response()->json($receivingForm);
    }




    public function approveTransfer(Request $request)
    {
        $transfer = $this->warehouseService->approveTransfer($request->all());
        return response()->json($transfer);
    }


    public function approveShipment(Request $request)
    {
        $shipment = $this->warehouseService->approveShipment($request->all());
        return response()->json($shipment);
    }



    public function clearDispatch(Request $request)
    {
        $dispatch = $this->warehouseService->clearDispatch($request->all());
        return response()->json($dispatch);
    }



    // Reports

    public function getInventoryNumbers()
    {
        $inventoryNumbers = $this->warehouseService->getInventoryNumbers();
        return response()->json($inventoryNumbers);
    }

    public function getTransactionReports(Request $request)
    {
        $transactions = $this->warehouseService->getTransactionReports($request->start_date, $request->end_date, $request->product_id);
        return response()->json($transactions);
    }

    public function getStockMovements()
    {
        $stockMovements = $this->warehouseService->getStockMovements();
        return response()->json($stockMovements);
    }

    public function getLowStockNotifications()
    {
        $lowStockNotifications = $this->warehouseService->getLowStockNotifications();
        return response()->json($lowStockNotifications);
    }

    public function getBinLocationReports()
    {
        $binLocations = $this->warehouseService->getBinLocationReports();
        return response()->json($binLocations);
}
}
