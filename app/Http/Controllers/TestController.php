<?php

namespace App\Http\Controllers;

use AfricasTalking\SDK\AfricasTalking;
use App\Events\SaleEvent;
use App\Jobs\OrderUpload;
use App\Models\Client as ModelsClient;
use App\Models\DailyStats;
use App\Models\Missedcall;
use App\Models\OrderHistory;
use App\Models\Ou;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Setting;
use App\Models\Sheet;
use App\Models\Sms;
use App\Models\Status;
use App\Models\User;
use App\Paypal\CreatePlan;
use App\Seller;
use App\Service\TestUploads;
use App\Services\GeocodingService;
use App\Services\RoutingService;
use App\Subscription\Restriction;
use Carbon\Carbon;
use DefStudio\Telegraph\Models\TelegraphChat;
use Exception;
use Google\Client;
use Google\Service\Sheets;
use Google\Service\Sheets\BatchUpdateValuesRequest;
use Google\Service\Sheets\ValueRange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class TestController extends Controller
{

    protected $routingService, $geocodingService;

    public function __construct(RoutingService $routingService, GeocodingService $geocodingService)
    {
        $this->routingService = $routingService;
        $this->geocodingService = $geocodingService;
    }


    public function getRoute($address)
    {
        // $address = $address . ' Kenya';
        $route = $this->routingService->getRoute($address); // Pass single address
    
        return response()->json($route);
    }
    

    public function sheet($id)
    {
        $test = new TestUploads;

        $ou_id = 1;
        $user = Auth::user();
        $sheet = Sheet::find($id);
        $vendor_id = $sheet->vendor_id;
        return $test->dispatchJobs($vendor_id, $sheet, $ou_id, $user);
    }
    public function telegram($address)
    {

        $ous = Ou::where('active', true)->get();

        $statuses = ['Delivered', 'Returned', 'In Transit'];

        foreach ($ous as $ou) {
            $sales = Sale::setEagerLoads([])->with('client')->select('id', 'client_id', 'order_no', 'ou_id')->where('geocoded', false)->whereIn('delivery_status', $statuses)->where('ou_id', $ou->id)->take(3)->get();
            try {
                foreach ($sales as $key => $sale) {
                    // Log::warning($sale->order_no);
                    $address = $sale->client->address;
                    $start_lat = -1.3532974533079083;
                    $start_lng = 36.907174189609535;

                    // $address = 'KICC Room 2211';

                    // ?steps=true&geometries=geojson&access_token=${mapboxgl.accessToken}`;

                    $coord_url = 'https://api.mapbox.com/geocoding/v5/mapbox.places/' . $address . '.json';
                    $response = Http::get($coord_url, [
                        'access_token' => env('MAPBOX_TOKEN'),
                        'country' => $ou->ou_code
                    ]);

                    // You can then access the response body and status code
                    $responseData = $response->json();

                    if (!empty($responseData['features'])) {

                        $center = $responseData['features'][0]['center'];

                        $lng = $center[0];
                        $lat = $center[1];

                        $distance_url = 'https://api.mapbox.com/directions/v5/mapbox/driving/' . $start_lng . ',' . $start_lat . ';' . $lng . ',' . $lat . '?steps=false&geometries=geojson';

                        $response = Http::get($distance_url, [
                            'access_token' => env('MAPBOX_TOKEN'),
                        ]);

                        $responseData = $response->json();

                        $distance = $responseData['routes'][0]['distance'] / 1000;
                        $duration = $responseData['routes'][0]['duration'] / 3600;

                        $sales_update = Sale::find($sale->id)->update(['longitude' => $lng, 'latitude' => $lat, 'distance' => $distance, 'geocoded' => true]);
                    } else {
                        $sales_update = Sale::find($sale->id)->update(['geocoded' => true]);
                    }
                }
            } catch (\Throwable $th) {
                throw $th;
            }
        }


        // $chat = TelegraphChat::where('ou_id', 2)->first();
        // $chat->markdown('Test 123')->send();
        // $missed = new Missedcall();

        // return $missed->searchPhone($phone);
    }

    public function counts($id)
    {
        $stats = new DailyStats();
        return $stats->getProductQuantity($id, 1);
    }

    public function batchUpdateWorks()
    {
        $spreadsheetId = '1CkvFqA9WeRMYveb7lIRgZCmAH8-QhT3JASZllsPX5d8';

        $client = new Client();
        $client->setAuthConfig(env('GOOGLE_SERVICE_ACCOUNT_JSON_LOCATION'));
        $client->addScope(Sheets::SPREADSHEETS);
        $service = new Sheets($client);
        $values = [
            [
                'Sheet1!L2', 'New Status1', '2023-06-26', 'New Notes1',
            ],
            [
                'Sheet1!L3', 'New Status2', '2023-06-26', 'New Notes2',
            ],
            // Add more rows as needed
        ];

        $valueInputOption = 'RAW';

        $data = [];
        try {
            foreach ($values as $row) {
                $data[] = new ValueRange([
                    'range' => $row[0],
                    'values' => [array_slice($row, 1)],
                ]);
            }
            // return $data;

            $body = new BatchUpdateValuesRequest([
                'valueInputOption' => $valueInputOption,
                'data' => $data,
            ]);
            $result = $service->spreadsheets_values->batchUpdate($spreadsheetId, $body);
            printf("%d cells updated.", $result->getTotalUpdatedCells());
            return;
        } catch (Exception $e) {
            // TODO(developer) - handle error appropriately
            echo 'Message: ' . $e->getMessage();
        }
    }

    public function batchUpdate()
    {
        $client = new Client();
        $client->setAuthConfig(env('GOOGLE_SERVICE_ACCOUNT_JSON_LOCATION'));
        $client->addScope(Sheets::SPREADSHEETS);
        $service = new Sheets($client);
        $valueInputOption = 'RAW';
        $spreadsheetId = '1CkvFqA9WeRMYveb7lIRgZCmAH8-QhT3JASZllsPX5d8';

        $data = [];

        $orders = Sale::take(100)->latest()->setEagerLoads([])->get(['delivery_status', 'delivery_date', 'customer_notes', 'order_no']);
        try {
            foreach ($orders as $order) {
                $orderNumber = $order['order_no'];
                $delivery_date = ($order['delivery_date']) ? $order['delivery_date'] : '';
                $delivery_status = ($order['delivery_status']) ? $order['delivery_status'] : '';
                $customer_notes = ($order['customer_notes']) ? $order['customer_notes'] : '';
                $updatedData = [
                    $delivery_status,
                    $delivery_date,
                    $customer_notes
                ];

                // Retrieve the range based on the order number
                $range = $this->getRangeByOrderNumber($service, $spreadsheetId, $orderNumber);

                if ($range) {
                    $data[] = new ValueRange([
                        'range' => $range,
                        'values' => [$updatedData],
                    ]);
                }
            }
            // return $data;

            $body = new BatchUpdateValuesRequest([
                'valueInputOption' => $valueInputOption,
                'data' => $data,
            ]);
            $result = $service->spreadsheets_values->batchUpdate($spreadsheetId, $body);
            printf("%d cells updated.", $result->getTotalUpdatedCells());
            return;
        } catch (Exception $e) {
            // TODO(developer) - handle error appropriately
            echo 'Message: ' . $e->getMessage();
        }
    }

    public function getRangeByOrderNumber($service, $spreadsheetId, $orderNumber)
    {
        $range = null;
        $response = $service->spreadsheets_values->get($spreadsheetId, 'Sheet1!B:B', ['valueRenderOption' => 'UNFORMATTED_VALUE']);
        $values = $response->getValues();
        if (!empty($values)) {
            foreach ($values as $rowIndex => $row) {
                // Skip the first row (header row)
                if ($rowIndex === 0) {
                    continue;
                }
                if ($row[0] == $orderNumber) {
                    // Offset row index by 1 to match Google Sheets index (1-based)
                    $range = 'Sheet1!L' . ($rowIndex + 1);
                    break;
                }
            }
        }
        return $range;
    }

    public function assign_orders()
    {


        $users = User::setEagerLoads([])->where('agent_sip', '!=', null)->role('Call center')->get();
        $orders = Sale::whereBetween('created_at', [Carbon::now()->subDays(7), Carbon::tomorrow()])
            ->where('delivery_status', 'New')
            ->where('agent_id', null)
            ->inRandomOrder()
            ->take(100)
            ->get();

        // $users = User::setEagerLoads([])->role('Call center')->get();
        // $orders = Sale::whereBetween('created_at', [Carbon::now()->subDays(7), Carbon::tomorrow()])
        //     ->where('delivery_status', 'New')
        //     ->where('agent_id', null)
        //     ->take(100)
        //     ->get();

        // Retrieve orders and users from your data source

        $orderCount = count($orders);
        $userCount = count($users);
        $ordersPerUser = ceil($orderCount / $userCount);

        // Distribute orders to users
        $userIndex = 0;
        foreach ($orders as $order) {
            $user = $users[$userIndex];
            // Assign the order to the current user

            // Example: Update the order with the user ID
            $order->agent_id = $user->id;
            $order->save();

            // Move to the next user in a round-robin fashion
            $userIndex = ($userIndex + 1) % $userCount;
        }
    }

    public function assign_orders_()
    {
        $users =  User::setEagerLoads([])->role('Call center')->get();

        // Step 1: Retrieve users and orders
        $orders = Sale::whereBetween('created_at', [Carbon::now()->subDays(7), Carbon::tomorrow()])
            ->where('delivery_status', 'New')
            ->where('agent_id', null)
            ->get();

        // Step 2: Remove duplicates from orders and keep only one copy
        $uniqueOrders = $orders->unique('order_no');

        // Step 3: Get the duplicate order IDs to remove
        $duplicateOrderIds = $orders->pluck('id')->diff($uniqueOrders->pluck('id'));

        // Step 4: Delete the duplicate orders except for one copy
        foreach ($duplicateOrderIds as $orderId) {
            // Log::debug('*****Dublicates*********');
            // Log::debug($orderId);
            // Log::debug('*****Dublicates*********');
            Sale::where('id', $orderId)->orderBy('id', 'desc')->skip(1)->delete();
        }

        // Step 5: Calculate the total number of users and orders
        $totalUsers = $users->count();
        $totalOrders = $uniqueOrders->count();

        // Rest of the code remains unchanged
        // ...



        // Step 2: Calculate the total number of users and orders
        // $totalUsers = $users->count();
        // $totalOrders = $orders->count();

        // Step 3: Calculate the number of orders each user should receive
        $ordersPerUser = $totalOrders / $totalUsers;

        // Step 4: Distribute orders to users in a round-robin fashion
        $orderIndex = 0;
        foreach ($users as $user) {
            // Determine the range of orders for the current user
            $start = $orderIndex;
            $end = $orderIndex + $ordersPerUser;

            // Assign orders to the user
            for ($i = $start; $i < $end; $i++) {
                $order = $orders[$i % $totalOrders];
                $order->agent_id = $user->id;
                $order->save();
            }

            // Update the order index for the next user
            $orderIndex = $end;
        }
    }




    public function africas_talking()
    {
        $phone = '+254743895505';
        $message = 'This is a test';
        try {
            $settings = Setting::first();
            $africas_talkig_username = $settings->africas_talkig_username;
            $africas_talkig_api_key = $settings->africas_talkig_api_key;


            $username = 'BoxleoKenya'; // use 'sandbox' for development in the test environment
            $apiKey   = '0fbf70babd408769207a740119b305da1c5505f72c9e91dce8031f7515a94255';

            $AT       = new AfricasTalking($username, $apiKey);
            // Get one of the services
            $sms      = $AT->sms();
            // Use the service
            $result   = $sms->send([
                'enqueue' => true,
                'from'    => $username,
                'to'      => $phone,
                'message' => $message
            ]);
            // $this->sms_count();
            return $result;
        } catch (Exception $e) {
            // return;
            echo $e;
        }
        // print_r($result);
    }










    public function batchUpdate_1()
    {
        $client = new Client();
        $client->setAuthConfig(env('GOOGLE_SERVICE_ACCOUNT_JSON_LOCATION'));
        $client->addScope(Sheets::SPREADSHEETS);

        $service = new Sheets($client);

        $spreadsheetId = '1CkvFqA9WeRMYveb7lIRgZCmAH8-QhT3JASZllsPX5d8';

        $values = [
            [
                'Sheet1!L1', 'New Status1', 'New Delivery Date1', 'New Notes1',
            ],
            [
                'Sheet1!L2', 'New Status2', 'New Delivery Date2', 'New Notes2',
            ],
            // Add more rows as needed
        ];
        // $data = [];
        // foreach ($values as $row) {
        //     $data[] = new ValueRange([
        //         'range' => $row['range'],
        //         'values' => [$row['values']],
        //     ]);
        $data = [];
        // foreach ($values as $row) {
        //     $data[] = new ValueRange([
        //         'range' => $row['values'][0][0],
        //         'values' => [array_slice($row['values'][0], 1)],
        //     ]);
        // }

        // return $data;


        $body = new BatchUpdateValuesRequest([
            'valueInputOption' => 'RAW',
            'data' => $data,
        ]);

        $result = $service->spreadsheets_values->batchUpdate($spreadsheetId, $body);

        // Handle the response as needed

        return 'Batch update completed.';
    }



    private function getClient()
    {
        $client = new Client();
        $client->setApplicationName('Your Application Name');
        $client->setScopes([
            Sheets::SPREADSHEETS
        ]);
        $client->setAccessType('offline');
        $client->setAuthConfig(env('GOOGLE_APPLICATION_CREDENTIALS'));

        // Check if the access token is expired and refresh if necessary
        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            $accessToken = $client->getAccessToken();
            $client->setAccessToken($accessToken);
        }

        return $client;
    }

    private function getService()
    {
        $client = $this->getClient();
        return new Sheets($client);
    }

    public function getSheetValues()
    {
        $spreadsheetId = '1NoocE3LsnSP2PUpgqA1Muu7P4zN_ZHJv_FPDJaG2_7s';
        $range = 'Sheet1!B1:C10';
        try {
            $service = $this->getService();
            $response = $service->spreadsheets_values->get($spreadsheetId, $range);
            return $response->getValues();
        } catch (\Exception $e) {
            return $e;
            // Handle error appropriately
            return [];
        }
    }

    public function writeSheetValues($spreadsheetId, $range, $values)
    {
        try {
            $service = $this->getService();
            $body = new ValueRange([
                'values' => $values
            ]);
            $params = [
                'valueInputOption' => 'RAW'
            ];
            $response = $service->spreadsheets_values->update($spreadsheetId, $range, $body, $params);
            return $response->getUpdatedCells();
        } catch (\Exception $e) {
            // Handle error appropriately
            return 0;
        }
    }



































    public function status_update($id)
    {
        $test = new Status();
        $test->status_update(new Request([]), 34413);
    }

    public function event_test()
    {
        $user = Auth::user();
        $sale = Sale::latest()->first();
        broadcast(new SaleEvent($user, $sale))->toOthers();
    }
    public function list_plans()
    {
        $plans = new CreatePlan;
        return $plans->list_plans();
    }
    // public function test()
    // {
    //     $api_ = DB::table('api_test')->get();

    //     $api_->transform(function($api) {
    //         $data = unserialize($api->item);
    //         return $data;
    //     });



    //     // return $data->toArray();
    //     return $api_;
    //     return json_decode(json_encode($data), false);


    // }


    public function settings()
    {
        return Setting::first();
    }
    public function sms()
    {
        $message = 'this is a test';
        $phone = '0743895505';

        $sms = new Sms();
        return $sms->advanta($phone, $message);


        try {
            $settings = $this->settings();

            $partnerID = $settings->celcomafrica_partner_id;
            $apikey = $settings->celcomafrica_api_key;
            $shortcode = $settings->celcomafrica_short_code;

            // $partnerID = "3310";
            // $apikey = "d6c04ebbe08e8b708dd528bb1ff8829a";
            // $shortcode = "DerriconLtd";

            $finalURL = "https://mysms.celcomafrica.com/api/services/sendsms/?apikey=" . urlencode($apikey) . "&partnerID=" . urlencode($partnerID) . "&message=" . urlencode($message) . "&shortcode=$shortcode&mobile=$phone";
            $ch = curl_init();
            \curl_setopt($ch, CURLOPT_URL, $finalURL);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);
            // $this->sms_count();

            echo ($response);
        } catch (Exception $e) {
            dd($e);
        }
    }


    public function test()
    {
        $product_av = Product::setEagerLoads([])->where('vendor_id', 1)->get('product_name');

        $pro_arr = [];

        foreach ($product_av as $pro) {
            $pro_arr[] = $pro->product_name;
        }

        return $pro_arr;



        $s1 = 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Aliquam et est ab doloremque aliquid, praesentium sit provident ipsam, beatae harum eum totam, qui ipsa autem ad hic commodi itaque accusantium.';
        $s2 = 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Nisi, ratione sequi nemo ducimus odio accusantium quos voluptates. Nemo deserunt modi suscipit quod aliquam ducimus necessitatibus, corrupti libero earum amet illo!';

        //one is empty, so no result
        if (strlen($s1) == 0 || strlen($s2) == 0) {
            return 0;
        }

        //replace none alphanumeric charactors
        //i left - in case its used to combine words
        $s1clean = preg_replace("/[^A-Za-z0-9-]/", ' ', $s1);
        $s2clean = preg_replace("/[^A-Za-z0-9-]/", ' ', $s2);

        //remove double spaces
        while (strpos($s1clean, "  ") !== false) {
            $s1clean = str_replace("  ", " ", $s1clean);
        }
        while (strpos($s2clean, "  ") !== false) {
            $s2clean = str_replace("  ", " ", $s2clean);
        }

        //create arrays
        $ar1 = explode(" ", $s1clean);
        $ar2 = explode(" ", $s2clean);
        $l1 = count($ar1);
        $l2 = count($ar2);

        //flip the arrays if needed so ar1 is always largest.
        if ($l2 > $l1) {
            $t = $ar2;
            $ar2 = $ar1;
            $ar1 = $t;
        }

        //flip array 2, to make the words the keys
        $ar2 = array_flip($ar2);


        $maxwords = max($l1, $l2);
        $matches = 0;

        //find matching words
        foreach ($ar1 as $word) {
            if (array_key_exists($word, $ar2))
                $matches++;
        }

        return ($matches / $maxwords) * 100;
    }


    public function test_fields()
    {
        $history = OrderHistory::orderBy('id', 'Desc')->first()->updated_fields;
        unset($history['updated_at']);
        $original_arr = array_keys($history['original']);
        $update_arr = array_keys($history['updated']);
        $new_array = array();

        foreach ($update_arr as $k) {
            if ($k != 'updated_at') {

                $new_array[$k] = $history['original'][$k];
            }
        }
        return $new_array;

        // $newArray = array();
        // foreach ($history['original'] as $entry) {
        //     dd($entry);
        //     if (array_key_exists($entry)) {

        //     }
        // }
        // // return $newArray;
        // foreach ($original_arr as $key => $value) {
        // }
    }


    public function restrict()
    {
        $restrict = new Restriction;
        return $restrict->orders();
    }
}
