<?php

namespace App\Uploads;

use App\Imports\OrderSheetImport;
use App\Imports\SalesImport;
use App\Models\api\Woocommerce;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Setting;
use App\Models\Settings;
use App\Seller;
use Carbon\Carbon;
use Google\Spreadsheet\DefaultServiceRequest;
use Google\Spreadsheet\ServiceRequestFactory;
use Google\Spreadsheet\SpreadsheetService;
use Google_Client;
use GuzzleHttp\Client;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Revolution\Google\Sheets\Facades\Sheets;
use Illuminate\Support\Str;

class OrderUpload
{
    public function google_($data)
    {
        // Log::debug($data);
        // return $data->all();
        $settings = Settings::where('file_path', '!=', null)->first('file_path');

        $start_date = Carbon::parse($data->start_date)->addDay(1)->format('Y-m-d');
        $end_date = Carbon::parse($data->end_date)->addDay(1)->format('Y-m-d');
        $seller_id = $data->vendor_id;
        $client_details = Seller::find($seller_id);
        $sheet_name = $data->sheet_name;
        $work_sheet = $data->work_sheet;
        $path = $settings->file_path;
        // dd($sheet_name);

        $work_sheet = $data->work_sheet;
        putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $path);
        $client = new Google_Client();
        $client->useApplicationDefaultCredentials();

        $client->setApplicationName("Google Sheet Orders");
        $client->setScopes(['https://www.googleapis.com/auth/drive', 'https://spreadsheets.google.com/feeds']);

        if ($client->isAccessTokenExpired()) {
            $client->refreshTokenWithAssertion();
        }

        $accessToken = $client->fetchAccessTokenWithAssertion()["access_token"];
        ServiceRequestFactory::setInstance(
            new DefaultServiceRequest($accessToken)
        );

        // dd($accessToken);

        // Get our spreadsheet
        $spreadsheet = (new SpreadsheetService)
            ->getSpreadsheetFeed()
            ->getByTitle($sheet_name);

        // Get the first worksheet (tab)
        $worksheet = $spreadsheet->getWorksheetFeed()->getByTitle($work_sheet);

        if ($data->end_date) {
            $listFeed = $worksheet->getListFeed(["sq" => "orderdate >= {$start_date} && orderdate <= {$end_date}", "reverse" => "true"]);
        } else {
            $listFeed = $worksheet->getListFeed(["sq" => "orderdate >= {$start_date}", "reverse" => "true"]);
        }

        // dd($listFeed);
        $representative = [];
        $real_orders = [];
        $order_arr = [];

        // return $listFeed->getEntries();


        /** @var ListEntry */
        foreach ($listFeed->getEntries() as $key => $entry) {
            // return ($entry->getValues()['productname']);
            $product_name = $entry->getValues()['productname'];
            // $product = Product::setEagerLoads([])->where('product_name', 'LIKE', "%{$product_name}%")->first('id');
            // $data_items['product'] = ;
            // if (!$product) {
            $product_model = new Product();
            $product_id = $product_model->check($product_name, $seller_id);
            $real_orders = $entry->getValues();
            $real_orders['products'][$key]['id'] = $product_id;
            $real_orders['products'][$key]['product_name'] = $product_name;
            $real_orders['products'][$key]['quantity'] = $entry->getValues()['quantity'];
            // $order_arr['products'][$key]['quantity'] = $order['quantity'];
            // }
            // var_dump($entry->getValues()['status']);
            // $status = ($entry->getValues()['status']) ? $this->check_status($entry->getValues()['status']) : '';
            // dd($status);
            // $product_arr['product'] = $product;
            // $entry->getValues()['status'] = $status;
            // dd($entry->getValues()['status'], $status);
            // $product_arr['entry'] = $entry->getValues();
            $order_arr[] = $entry->getValues()['orderid'];
            // return $product;
            $representative[] = $real_orders;
        }
        $order_transform = new OrderTransform;
        $sales = $order_transform->google_transform($representative);
        // return $representative;
        $exists_row = Sale::setEagerLoads([])->with(['client'])->whereIn('order_no', $order_arr)->get('order_no');
        foreach ($sales as $index => $rep) {
            foreach ($exists_row as $key => $value) {
                // return $rep['order_no'];
                if ($rep['order_no'] == $value['order_no']) {
                    Arr::forget($sales, $index);
                }
            }
        }
        $sales = array_values($sales->toArray());

        // return $order_arr;
        return response()->json([
            'sales' => $sales,
            'exist_orders' => $exists_row,
        ], 200);
        // return $representative;
    }

    public function shopify($shopify_data)
    {

        // dd(env("SHOPIFY_SECRET"));
        // $yesterday = Carbon::tomorrow();
        // return $shopify_data->all();
        $yesterday = Carbon::yesterday();
        $d = $yesterday->toDateTimeLocalString();




        // dd(json_encode($yesterday), '2014-04-25T16:15:47:04:00');
        // 2020-07/orders.json
        // $url = env("SHOPIFY_URL") . '2020-07/orders.json';
        $vendor_id = (Auth::guard("seller")->check()) ? Auth::guard('seller')->id() : $shopify_data->vendor_id;
        // $vendor_id = 1;
        $settings = Setting::where('vendor_id', $vendor_id)->first();
        if (!$settings->shopify_url) {
            abort(422, 'Vendor shop is not connected to the app');
        }
        $url = 'https://' . $settings->shopify_key . ':' . $settings->shopify_secret . '@' . $settings->shopify_url . '/admin/api/2020-07/orders.json';
        // $url = $settings->shopify_url . '2020-07/orders.json';
        // return $url;


        // $url = env("SHOPIFY_URL") . '2020-07/orders.json?created_at_min=' . $d;
        try {
            $client = new Client();
            $shopify_data = $client->request('GET', $url);
            $response = $shopify_data->getBody()->getContents();

            // return gettype($data);
            // $client_name = $data['']



            $data = json_decode($response);
            // dd($data);

            $orders = $data->orders;
            // dd($orders);
            $order_transform = new OrderTransform;
            $sales = $order_transform->shopify_transform($orders, $vendor_id);


            $order_arr = [];
            foreach ($sales as $order) {
                if ($order['order_no'] !== null) {
                    $real_orders[] = $order;
                    $order_arr[] = $order['order_no'];
                }
            }
            $exists_row = Sale::setEagerLoads([])->whereIn('order_no', $order_arr)->get('order_no');
            foreach ($sales as $index => $rep) {
                foreach ($exists_row as $key => $value) {
                    // return $rep['order_no'];
                    if ($rep['order_no'] == $value['order_no']) {
                        Arr::forget($sales, $index);
                    }
                }
            }
            $sales = array_values($sales);

            // return $order_arr;
            return response()->json([
                'sales' => $sales,
                'exist_orders' => $exists_row,
            ], 200);
        } catch (\Exception $e) {
            // dd($e);
            // Log::error($e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile());
            return $e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile();
        }
    }

    public function woocommerce($data)
    {
        // return $data->all();
        $start_date = $data->start_date;
        $end_date = $data->end_date;
        $vendor_id = $data->vendor_id;
        $woocommerce = new Woocommerce;
        $orders = $woocommerce->orders($start_date, $end_date, $vendor_id);
        $order = new OrderTransform;
        $sales = $order->woocommerce_transform($orders, $vendor_id);

        $order_arr = [];
        foreach ($sales as $order) {
            if ($order['order_no'] !== null) {
                $real_orders[] = $order;
                $order_arr[] = $order['order_no'];
            }
        }
        $exists_row = Sale::setEagerLoads([])->whereIn('order_no', $order_arr)->get('order_no');
        foreach ($sales as $index => $rep) {
            foreach ($exists_row as $key => $value) {
                // return $rep['order_no'];
                if ($rep['order_no'] == $value['order_no']) {
                    Arr::forget($sales, $index);
                }
            }
        }
        $sales = array_values($sales);

        // return $order_arr;
        return response()->json([
            'sales' => $sales,
            'exist_orders' => $exists_row,
        ], 200);
    }
    public function excel($file, $data)
    {
        // $order_id = new AutoGenerate;
        $orders = Excel::toArray(new OrderSheetImport, request()->file('file'));
        $arr = $orders[0];


        // $orders = Excel::toArray(new OrderSheetImport, request()->file('file'));
        // $arr = $data['orders'];

        $transform = new OrderTransform;
        $vendor_id = $data['vendor_id'];
        // $vendor_id = 1;
        $sales = $transform->excel_transform($arr, $vendor_id);

        $order_arr = [];
        foreach ($sales as $order) {
            if ($order['order_no'] !== null) {
                $real_orders[] = $order;
                $order_arr[] = $order['order_no'];
            }
        }
        $exists_row = Sale::setEagerLoads([])->whereIn('order_no', $order_arr)->get('order_no');
        foreach ($sales as $index => $rep) {
            foreach ($exists_row as $key => $value) {
                // return $rep['order_no'];
                if ($rep['order_no'] == $value['order_no']) {
                    Arr::forget($sales, $index);
                }
            }
        }
        $sales = array_values($sales);

        // return $order_arr;
        return response()->json([
            'sales' => $sales,
            'exist_orders' => $exists_row,
        ], 200);
    }

    public function excel_2($data)
    {

        // return ($request->all());
        // $order_id = new AutoGenerate;
        $orders = Excel::toArray(new SalesImport, request()->file('file'));
        // dd($orders);

        $sales = $orders['Orders'];
        $products = $orders['Products'];

        // $arr = $orders[0];
        $real_orders = [];
        $sales = collect($sales);
        $vendor_id = $data['vendor_id'];
        // $vendor_id = 1;
        $status_check = new OrderTransform;

        $sales->transform(function ($sale) use ($products, $vendor_id, $status_check) {
            if ($sale['order_id'] != null) {
                $order_product = [];
                foreach ($products as $key => $product) {
                    if ($product['order_id'] == $sale['order_id']) {
                        $product_check = new Product;
                        $product['id'] = $product_check->check($product['product_name'], $vendor_id);
                        $order_product[] = $product;
                    }
                }
                $sale['products'] = $order_product;
                $sale['order_no'] = $sale['order_id'];
                $sale['status'] = $status_check->check_status($sale['status']);
                $sale['notes'] = $sale['special_instructions'];
                $sale['cod_amount'] = $sale['total_amount'];
                $sale['waybill_no'] = (array_key_exists('waybill_number', $sale)) ? $sale['waybill_number'] : null;
                $sale['delivery_date'] = ($sale['delivery_date'] != null) ? date('Y-m-d', Date::excelToTimestamp($sale['delivery_date'])) : '';
                $sale['platform'] = 'Upload';
            }
            return $sale;
        });



        $order_arr = [];
        foreach ($sales as $order) {
            if ($order['order_no'] !== null) {
                $real_orders[] = $order;
                $order_arr[] = $order['order_no'];
            }
        }
        $exists_row = Sale::setEagerLoads([])->whereIn('order_no', $order_arr)->get('order_no');
        foreach ($sales as $index => $rep) {
            foreach ($exists_row as $key => $value) {
                // return $rep['order_no'];
                if ($rep['order_no'] == $value['order_no']) {
                    Arr::forget($sales, $index);
                }
            }
        }
        $sales = array_values($sales->toArray());

        // return $order_arr;
        return response()->json([
            'sales' => $sales,
            'exist_orders' => $exists_row,
        ], 200);
        // return $representative;

    }


    public function google($data)
    {
        try {
            $vendor_id = $data->vendor_id;
            $last_order_upload = $data->last_order_upload;
            $ou_id = $data->ou_id;
            $sheet_name = $data->sheet_name;
            $post_spreadsheet_id = $data->post_spreadsheet_id;
            $sync_all = $data->sync_all;
            $sheets = Sheets::spreadsheet($post_spreadsheet_id)
                ->sheet($sheet_name)
                ->get();

            $header = $sheets->pull(0);
            $headers = [];
            foreach ($header as $key => $value) {
                $value = Str::lower($value);
                $headers[] = Str::snake($value);
            }


            $orders = $sheets->reverse()->take(600);
            // $orders = $sheets->take(2000);
            $orders = Sheets::collection(header: $headers, rows: $orders);
            // $timestamp = '2023-10-26 00:00:00'; // Replace with the timestamp from your database
            $filteredRows = [];
            // Iterate through rows and filter based on timestamp


            if ($vendor_id == 197) {

                foreach ($orders as $row) {
                    $rowTimestamp = $row['timestamp']; // Replace with the actual column name
                    // $rowTimestamp = (array_key_exists('timestamp', $row)) ?  $row['timestamp'] : Carbon::yesterday(); // Replace with the actual column name
                    if (strtotime($rowTimestamp) > strtotime($last_order_upload)) {
                        $filteredRows[] = $row;
                    }
                }
                $filteredRows = collect($filteredRows);

                // Log::info(count($filteredRows));
            } else {
                $filteredRows = $orders;
            }
            

            $google = new OrderTransform;
            $data = $google->google_transform($filteredRows->toArray(), $vendor_id, $ou_id, $sync_all);
            return $data;
        } catch (\Throwable $th) {
            // Log::debug($th);
            throw $th;
        }
    }
}
