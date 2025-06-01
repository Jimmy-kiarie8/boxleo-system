<?php

namespace App\Service;

use App\Http\Controllers\OrderUploadController;
use App\Models\Sheet;
use App\Models\User;
use App\Models\Warehouse\Warehouse;
use App\Seller;
use Illuminate\Http\Request;


class SheetUpload
{
    public function upload($user_id, $id) {
        
        // $tenant = $this->option('tenant');

        $user = User::find($user_id);
        $ou = $user->ou;
        $ou_id = $ou->id;
        $warehouse = Warehouse::where('ou_id', $ou_id)->first();


        $test = new TestUploads;




        if ($id) {
            $sheet = Sheet::where('active', true)->where('id', $id)->with(['vendor'])->first();
            // else {
            //     $sheets = Sheet::where('active', true)->with(['vendor'])->get();
            // }
            // foreach ($sheets as $key => $sheet) {

            if ($sheet) {

                return $test->dispatchJobs($sheet->vendor_id, $sheet, $ou_id, $user);


                $seller = Seller::where('id', $sheet->vendor_id)->first();
                $data = [
                    'client' => $seller->name,
                    'warehouse_id' => ($warehouse) ? $warehouse->id : 1,
                    'ou_id' => ($ou) ? $ou_id : 1,
                    'vendor_id' => $sheet->vendor_id,
                    'sheet_name' => $sheet->sheet_name,
                    'last_order_upload' => $sheet->last_order_upload,
                    'post_spreadsheet_id' => $sheet->post_spreadsheet_id,
                    'sync_all' => $sheet->sync_all,
                    'platform' => 'Google Sheets'
                ];
                $order_upload = new OrderUploadController;
                $orders = $order_upload->get_orders(new Request($data));
                $data['orders'] = $orders;
                $data['user'] = $user;


                $order_upload->salesUpload(new Request($data));

                Sheet::find($sheet->id)->update(['last_order_upload' => now()]);
            }
        }

        // $this->info('Complete');
        return;
    }
}
