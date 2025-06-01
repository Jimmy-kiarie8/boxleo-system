<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Mpesa;
use App\Models\Sale;
use App\Models\Sms;
use App\Models\Status;
use App\Models\Stk;
use App\Models\Transaction;
use App\Models\Warehouse\Warehouse;
use App\Scopes\OrderScope;
use Carbon\Carbon;
use DefStudio\Telegraph\Models\TelegraphChat;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Psr7\Request as Psr7Request;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Stmt\TryCatch;

class MpesaController extends Controller
{
    protected $mpesa;

    public function mpesa()
    {
        return Mpesa::first();
    }



    // public function __construct()
    // {
    //     $this->mpesa = Mpesa::first();
    // }

    public function index()
    {
        $transactions = Transaction::orderBy('id', 'DESC')->paginate(200);
        $transactions->transform(function ($transaction) {
            $transaction->name = $transaction->FirstName . ' ' . $transaction->MiddleName . ' ' . $transaction->LastName;
            return $transaction;
        });
        return $transactions;
    }
    public function store(Request $request)
    {
        $mpesa = Mpesa::first();

        if ($mpesa->registered) {
            abort(422, 'This app has already been registered!');
        }

        if (!$mpesa) {
            $mpesa = new Mpesa;
        }


        $mpesa->customer_key = $request->customer_key;
        $mpesa->customer_secret = $request->customer_secret;
        $mpesa->shortcode = $request->shortcode;
        $mpesa->confirmation_url = 'https://' . tenant('id') . env('CENTRAL_DOMAIN', '.logixsaas.com') . "/api/confirmation";
        $mpesa->validation_url = 'https://' . tenant('id') . env('CENTRAL_DOMAIN', '.logixsaas.com') . "/api/validation";
        $mpesa->environment = 'production';
        $mpesa->save();

        return $mpesa;
    }

    public function confirmation(Request $request)
    {
        $response = $request->all();
        $transaction = new Transaction();

        if ($response['TransactionType'] === 'Pay Bill') {
            $transaction->TransactionType = $response['TransactionType'];
            $transaction->TransID = $response['TransID'];
            $transaction->TransTime = $response['TransTime'];
            $transaction->TransAmount = $response['TransAmount'];
            $transaction->BusinessShortCode = $response['BusinessShortCode'];
            $transaction->BillRefNumber = $response['BillRefNumber'];
            $transaction->InvoiceNumber = $response['InvoiceNumber'];
            $transaction->OrgAccountBalance = $response['OrgAccountBalance'];
            $transaction->ThirdPartyTransID = $response['ThirdPartyTransID'];
            $transaction->MSISDN = $response['MSISDN'];
            $transaction->FirstName = $response['FirstName'];
            $transaction->save();
            $this->order_deliver($transaction->BillRefNumber, $transaction->TransAmount, $transaction->TransID);
        } else {
            // $transaction = new \Safaricom\Mpesa\Mpesa();
            // $callbackData = $transaction->getDataFromCallback();
            // $callbackData = json_decode($callbackData);
            // $callbackData = $callbackData->Body->stkCallback->CallbackMetadata->Item;

            // $transaction->transaction = serialize($response);
            $transaction->TransactionType = (array_key_exists('TransactionType', $response)) ? $response['TransactionType'] : null;
            $transaction->TransID = (array_key_exists('TransID', $response)) ? $response['TransID'] : null;
            $transaction->TransTime = (array_key_exists('TransTime', $response)) ? $response['TransTime'] : null;
            $transaction->TransAmount = (array_key_exists('TransAmount', $response)) ? $response['TransAmount'] : null;
            $transaction->BusinessShortCode = (array_key_exists('BusinessShortCode', $response)) ? $response['BusinessShortCode'] : null;
            $transaction->InvoiceNumber = (array_key_exists('InvoiceNumber', $response)) ? $response['InvoiceNumber'] : null;
            $transaction->OrgAccountBalance = (array_key_exists('OrgAccountBalance', $response)) ? $response['OrgAccountBalance'] : null;
            $transaction->ThirdPartyTransID = (array_key_exists('ThirdPartyTransID', $response)) ? $response['ThirdPartyTransID'] : null;
            $transaction->MSISDN = (array_key_exists('MSISDN', $response)) ? $response['MSISDN'] : null;
            $transaction->FirstName = (array_key_exists('FirstName', $response)) ? $response['FirstName'] : null;
            $transaction->MiddleName = (array_key_exists('MiddleName', $response)) ? $response['MiddleName'] : null;
            $transaction->LastName = (array_key_exists('LastName', $response)) ? $response['LastName'] : null;
            $transaction->BillRefNumber = (array_key_exists('BillRefNumber', $response)) ? $response['BillRefNumber'] : null;
            $transaction->save();
        }

        return true;
    }

    public function order_deliver($order_no, $amount, $mpesa_code)
    {
        // $order = Sale::where('order_no', $order_no)->first();
        $order = Sale::whereRaw('LOWER(order_no) = ?', strtolower($order_no))->first();

        if ($order) {
            // $transaction = Transaction::where('TransID', $mpesa_code)->first();
            // $transaction->code_used = true;
            // $transaction->save();
            $status_update = new Status();
            $data = ['delivery_status' => 'Delivered', 'mpesa_code' => $mpesa_code, 'paid' => 1, 'products' => $order->products, 'payment_method' => 'Mpesa', 'warehouse_id' => $order->warehouse_id];

            $total_price = (int) preg_replace("/[^0-9.]/", "", $order->total_price);

            if ($amount < $total_price) {
                $message = '*' . $order_no . '* Amount paid is less than order amount. Amount paid: *' . $amount . '* Order Amount: *' . $total_price . '*';
                $chat = TelegraphChat::first();

                if ($chat) {
                    $chat->markdown($message)->send();
                }

                // phone
                // $phone = '254791960533';
                // $sms = new Sms();
                // $sms->sms([$phone], $message);

                $paid_amount = Transaction::where('BillRefNumber', $order_no)->sum('TransAmount');

                if ($paid_amount >= $order->total_price) {
                    return $status_update->status_update(new Request($data), $order->id);
                }
                return;
                // $data['delivery_status'] = 'Partially Delivered';
            }

            return $status_update->status_update(new Request($data), $order->id);
        }
        return;
    }
    // public function order_update()
    public function order_update($phone, $amount, $mpesa_code)
    {
        // $phone = '254 711 346 309';
        // $amount = 8997;
        // $mpesa_code = 'PGL3LE5VQL';

        $phone = $this->clean($phone);

        /* 
        if (strlen($phone) == 12) {
            $phone = substr($phone, 3, -3);
        } elseif (strlen($phone) == 10) {
            $phone = substr($phone, 1, -1);
        }
        */
        // dd($phone);
        // DB::enableQueryLog(); // Enable query log
        // $client = Client::whereRaw("REPLACE(`phone`, ' ', '') = ? ", $phone)->where('phone', 'LIKE', "%{$phone}%")->first();
        $client = Client::whereRaw("REPLACE(`phone`, ' ' ,'') LIKE ?", ['%' . str_replace(' ', '', $phone) . '%'])->first();
        // dd(DB::getQueryLog()); // Show results of log
        if (!$client) {
            return;
        }

        // return $client->sales;

        $order = Sale::where('mpesa_code', null)->where('delivery_status', '!=', 'Delivered')->where('client_id', $client->id);

        if ($order->count() > 1) {
            $order = $order->whereBetween('total_price', [($amount - 5), ($amount + 5)])->first();
            if (!$order) {
                return;
            }
        } elseif ($order->count() == 0) {
            return;
        } else {
            $order = $order->first();
            $transaction = Transaction::where('TransID', $mpesa_code)->first();
            $transaction->code_used = true;
            $transaction->save();
        }
        // return $order;
        // $order = Sale::whereBetween('total_price', [($amount-2), ($amount+2)])->where('client_id', $client->id)->first();


        $status_update = new Status();

        $data = ['delivery_status' => 'Delivered', 'mpesa_code' => $mpesa_code, 'paid' => 1];

        return $status_update->status_update(new Request($data), $order->id);
    }

    public function mpesa_update()
    {
        $transactions = Transaction::all();
        // $transaction 
        foreach ($transactions as  $transaction) {
            $this->order_update($transaction->MSISDN, $transaction->TransAmount, $transaction->TransID);
        }
    }

    public function base_64()
    {
        $mpesa = $this->mpesa();
        $key = $mpesa->customer_key;
        $secret = $mpesa->customer_secret;
        $base64 = base64_encode($key . ':' . $secret);
        return $base64;
    }



    public function token()
    {
        // return  $this->base_64();
        $mpesa = $this->mpesa();
        $url = ($mpesa->environment == 'production') ? env('MPESA_URL') : env('MPESA_SANDBOX_URL');

        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . $this->base_64(),
        ])->get($url . '/oauth/v1/generate?grant_type=client_credentials');

        // $token = $response->json()['access_token'];
        $data =  json_decode($response->getBody()->getContents());

        return $data->access_token;
    }

    public function token_1()
    {
        $mpesa = $this->mpesa();
        $url = ($mpesa->environment == 'production') ? env('MPESA_URL') : env('MPESA_SANDBOX_URL');

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url . '/oauth/v1/generate?grant_type=client_credentials',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic ' . $this->base_64(),
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        // echo $response;

        $token = json_decode($response)->access_token;

        // dd($token);
        return $token;
    }

    public function url_register(Request $request)
    {
        // return $request->all();
        // return 'https://' . tenant('id') . env('CENTRAL_DOMAIN', '.logixsaas.com') . "/api/confirmation";
        $token = $this->token();
        $mpesa = Mpesa::first();

        if (!$mpesa) {
            abort(422, 'M-pesa data not available');
        }
        $shortcode = $mpesa->shortcode;


        if ($mpesa) {
            $curl_post_data = array(
                "ShortCode" => $shortcode,
                "ResponseType" => "Completed",
                "ConfirmationURL" => 'https://' . tenant('id') . env('CENTRAL_DOMAIN', '.logixsaas.com') . "/api/confirmation",
                "ValidationURL" => 'https://' . tenant('id') . env('CENTRAL_DOMAIN', '.logixsaas.com') . "/api/validation",
            );

            // dd($curl_post_data);
            $data_string = json_encode($curl_post_data);

            $ch = curl_init('https://api.safaricom.co.ke/mpesa/c2b/v2/registerurl');
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
                'Content-Type: application/json'
            ]);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $response = curl_exec($ch);
            curl_close($ch);
            // dd($response);

            $mpesa->registered = true;
            $mpesa->save();
        } else {
            abort(422, 'No Data');
        }
    }
    public function validation_url()
    {
        return true;
    }

    public function password($string)
    {
        $encodedString = base64_encode($string);
        return $encodedString;
    }


    public function clean($phone)
    {
        $string = str_replace(' ', '', $phone);
        $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
        $p_length = strlen($string);
        $str = (int)mb_substr($string, 0, 1);
        // dd($p_length == 12 && $str == 2);

        if ($p_length == 9 && $str == 7) {
            $phone_no = '254' . $string;
        } elseif ($p_length == 10 && $str == 0) {
            $str = substr($string, 1);
            $phone_no = '254' . $str;
        } else {
            $phone_no = $string;
        }

        return $phone_no;
    }

    public function transaction_search($search)
    {
        $transactions = Transaction::where(function ($query) use ($search) {
            $query->where('MSISDN', 'LIKE', '%' . $search . '%');
            $query->orWhere('BillRefNumber', 'LIKE', '%' . $search . '%');
            $query->orWhere('TransID', 'LIKE', '%' . $search . '%');
            $query->orWhere('FirstName', 'LIKE', '%' . $search . '%');
            $query->orWhere('MiddleName', 'LIKE', '%' . $search . '%');
            $query->orWhere('LastName', 'LIKE', '%' . $search . '%');
        })->paginate(200);

        $transactions->transform(function ($transaction) {
            $transaction->name = $transaction->FirstName . ' ' . $transaction->MiddleName . ' ' . $transaction->LastName;
            return $transaction;
        });
        return $transactions;
    }

    public function transaction_order($code)
    {
        return Sale::setEagerLoads([])->with(['client', 'products'])->where('mpesa_code', $code)->first();
    }

    public function stk_push($id, $phone)
    {

        // Log::info($phone);
        $sale = Sale::withoutGlobalScope(OrderScope::class)->setEagerLoads([])->latest()->find($id);
        $mpesa = $this->mpesa();
        $amount = (int) preg_replace("/[^0-9.]/", "", $sale->total_price);
        $url = ($mpesa->environment == 'production') ? env('MPESA_URL') : env('MPESA_SANDBOX_URL');
        $api_url = 'https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
        $shortcode = ($mpesa->environment == 'production') ? $mpesa->shortcode : 174379;
        $passkey = $mpesa->passkey;
        $shortcode = $mpesa->shortcode;
        $timestamp = date('YmdHis', strtotime('now'));
        $phone = $this->clean($phone);
        $password = $this->password($shortcode . $passkey . $timestamp);
        $app_url = env('APP_URL') . '/api/stk_response/' . $sale->order_no;

        $token = $this->token();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json',
        ])->post($api_url, [
            "BusinessShortCode" => $shortcode,
            "Password" => $password,
            "Timestamp" => $timestamp,
            "TransactionType" => "CustomerPayBillOnline",
            "Amount" => $amount,
            "PartyA" => $phone,
            "PartyB" => $shortcode,
            "PhoneNumber" => $phone,
            "CallBackURL" => $app_url,
            "AccountReference" => $sale->order_no,
            "TransactionDesc" => "Order payment"
        ]);

        echo $response->body();
    }

    public function stk_response(Request $request, $order_no)
    {

        // $resultBody = json_decode($request->getContent());


        // if ($resultBody->Body->stkCallback) {
        //     $data  = $resultBody->Body->stkCallback->CallbackMetadata->Item;
        //     $amount = $data[0]->Value;
        //     $MpesaReceiptNumber = $data[1]->Value;
        //     $this->order_deliver($order_no, $amount, $MpesaReceiptNumber);
        // }
        // return;

        $resultBody = json_decode($request->getContent());

        // Log the incoming JSON payload for debugging
        // Log::info('Received STK Response: ' . json_encode($resultBody));

        if (isset($resultBody->Body->stkCallback)) {
            $stkCallback = $resultBody->Body->stkCallback;

            if (isset($stkCallback->CallbackMetadata->Item)) {
                $data = $stkCallback->CallbackMetadata->Item;

                // Ensure array has expected elements before accessing
                if (
                    isset($data[0]) && isset($data[0]->Value) &&
                    isset($data[1]) && isset($data[1]->Value)
                ) {

                    $amount = $data[0]->Value;
                    $MpesaReceiptNumber = $data[1]->Value;

                    // Log the parsed data for debugging
                    // Log::info("Order No: $order_no, Amount: $amount, Mpesa Receipt Number: $MpesaReceiptNumber");

                    $this->order_deliver($order_no, $amount, $MpesaReceiptNumber);
                } else {
                    // Log::error('CallbackMetadata Item does not contain expected data.');
                }
            } else {
                // Log::error('CallbackMetadata or Item is missing in stkCallback.');
            }
        } else {
            // Log::error('stkCallback is missing in the response Body.');
        }
        return;
    }

    public function transactions_filter(Request $request)
    {
        $date = $request->date;
        $transactions = Transaction::when(!empty($date), function ($q) use ($date) {

            if ($date[0] == $date[1]) {
                return $q->whereDate('TransTime', $date);
            } else {
                return $q->whereBetween('TransTime', $date);
            }
        })->orderBy('id', 'DESC')->paginate(500);

        $transactions->transform(function ($transaction) {
            $transaction->name = $transaction->FirstName . ' ' . $transaction->MiddleName . ' ' . $transaction->LastName;
            return $transaction;
        });
        return $transactions;
    }
}
