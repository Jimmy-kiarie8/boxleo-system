<?php

namespace App\Console\Commands;

use App\Http\Controllers\OrderUploadController;
use App\Jobs\GoogleUpload;
use App\Models\Sheet;
use App\Models\User;
use App\Models\Warehouse\Warehouse;
use App\Seller;
use App\Service\SheetUpload;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GoogleSheetCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'upload:google {id?} {--user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upload orders from Google sheets';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // $tenant = $this->option('tenant');
        $user_id = $this->option('user');

        $user = User::find($user_id);
        $ou = $user->ou;
        $ou_id = $ou->id;
        $warehouse = Warehouse::where('ou_id', $ou_id)->first();

        if ($this->argument('id')) {
            $sheet = Sheet::where('active', true)->where('id', $this->argument('id'))->with(['vendor'])->first();

            if ($sheet) {
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

                if ($sheet->vendor_id != 197) {

                    $exclude_statuses = ['Delivered', 'Returned', 'Awaiting Dispatch', 'In Transit', 'Dispatched', 'awaiting return','Undispatched','Awaiting Return'];
                    $filtered_orders = array_filter($orders, function ($order) use ($exclude_statuses) {
                        return !in_array($order['status'], $exclude_statuses);
                    });

                    $data['orders'] = array_values($filtered_orders); // Reset array keys
                } else {
                    $data['orders'] = $orders;
                }

                $data['user'] = $user;


                $order_upload->salesUpload(new Request($data));

                Sheet::find($sheet->id)->update(['last_order_upload' => now()]);
            }
        }
        $this->info('Complete');
        return;
        $user_id = $this->option('user');
        $id = $this->argument('id');

        $service = new SheetUpload;
        $service->upload($user_id, $id);
    }
}
