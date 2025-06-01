<?php

namespace App\Http\Controllers;

use App\Events\OrderUploadEvent;
use App\Jobs\OrderUpload;
use App\Models\api\Order;
use App\Models\Client;
use App\Models\Product;
use App\Models\ProductSale;
use App\Models\Sale;
use App\Models\Setting;
use App\Models\Settings;
use App\Models\Sku;
use App\Models\Status;
use App\Notifications\OrderUploadNotification;
use App\Seller;
use App\Models\User;
use Carbon\Carbon;
use Google\Spreadsheet\DefaultServiceRequest;
use Google\Spreadsheet\ServiceRequestFactory;
use Google\Spreadsheet\SpreadsheetService;
use Illuminate\Http\Request;
use Google_Client;
use Google_Service_Sheets;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class GoogledriveController extends Controller
{
    public function logged_user()
    {
        $user = new User();
        return  $user->logged_user();
    }

    public function google_sheets(Request $request)
    {

        // return $request->all();
        $settings = Settings::where('file_path', '!=', null)->first('file_path');



        // $users = User::all();
        // $message = 'New orders uploaded by ' . '<b style="color: red">' . Auth::user()->name . '</b>';
        // // foreach ($users as  $user) {
        // broadcast(new OrderUploadEvent(Auth::user(), $message))->toOthers();
        // Notification::send($users, new OrderUploadNotification(Auth::user(), $message));
        // return ;
        // $user->notify(new OrderUploadNotification(Auth::user(), $message));
        // }
        // return ($request->all());
        $start_date = Carbon::parse($request->start_date)->addDay(1)->format('Y-m-d');
        $end_date = Carbon::parse($request->end_date)->addDay(1)->format('Y-m-d');
        $seller_id = $request->vendor_id;
        $client_details = Seller::find($seller_id);
        // dd($client_details);
        $sheet_name = $request->sheet_name;
        $work_sheet = $request->work_sheet;
        $path = $settings->file_path;
        // $path = 'storage/google/sandbox.json'; // sandbox


        // $company_file = Setting::where('vendor_id', $request->vendor_id)->first();

        // dd($company_file->file_path);

        // $path = $company_file->file_path; // sandbox
        // $path = public_path($company_file->file_path); // sandbox

        // dd($path);

        $work_sheet = $request->work_sheet;
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
        // dd($work_sheet, $sheet_name);
        // Get our spreadsheet
        $spreadsheet = (new SpreadsheetService)
            ->getSpreadsheetFeed()
            ->getByTitle($sheet_name);

        // Get the first worksheet (tab)
        // $worksheets = $spreadsheet->getWorksheetFeed()->getEntries();
        $worksheet = $spreadsheet->getWorksheetFeed()->getByTitle($work_sheet);
        // dd($worksheet);
        // foreach ($worksheets as $worksheet) {
        // }
        // dd($worksheets);
        // $worksheets = $worksheets->getByTitle('Sheet1');

        // if ($sheet_no > count($worksheets)) {
        //     abort(404, "The sheet number doesn't exist");
        // }
        // $worksheet = $worksheets[$sheet_no];
        // dd($worksheet);

        if ($request->end_date) {
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
            $product_model = new Product;
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
        $order_transform = new Order;
        $sales = $order_transform->google_transform($representative);
        // return $representative;
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

    public function check_product($product_name)
    {
        $perc = 0;
        $products = Product::setEagerLoads([])->get('id');
        // $products = Product::where('client_id', $client_id)->get();
        $text_per = 0;
        foreach ($products as $product) {
            $text_per = similar_text(strtolower($product->product_name), strtolower($product_name), $percent);
            // var_dump($text_per);
            // var_dump('*******');
            // var_dump($product->product_name);

            // var_dump('*******');

            // var_dump( $perc);
            if ($text_per > $perc) {
                $perc = similar_text(strtolower($product->product_name), strtolower($product_name), $percent);
                $product_id = $product;
            }
        }
        if ($perc > 5) {
            // dd($product_id);
            // return $product;
        } else {
            // dd($perc);
            $product = new Product();
            $product->id = null;
            return $product;
        }
    }

    public function check_status($status)
    {
        $perc = 0;
        $statuses = Status::setEagerLoads([])->get('status');
        $text_per = 0;
        foreach ($statuses as $status_var) {
            $text_per = similar_text(strtolower($status), strtolower($status_var->status), $percent);
            if ($text_per > $perc) {
                $perc = $text_per;
                $status_ = $status_var->status;
            }
            // var_dump($text_per, $perc, $status_, $status_var);
        }
        // dd($perc, $status_);
        if ($perc > 5) {

            // var_dump('*******************************************');
            // var_dump($status_);
            // var_dump('*******************************************');
            return $status_;
        } else {
            // var_dump($status);
            return $status;
        }
    }

    public function googleUpload(Request $request)
    {
        // return $this->logged_user();
        $this->dispatch(new OrderUpload($this->logged_user(), $request->all()));
        return;
    }

    public function get_json()
    {
        $file = Setting::where('file_path', '!=', 'null')->first('file_path');

        if ($file) {
            $path = $file->file_path;
            return  json_decode(file_get_contents($path), true);
        } else {
            return '';
        }
    }
}
