<?php

namespace App\Models;

use AfricasTalking\SDK\AfricasTalking;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class Sms extends Model
{
    use HasFactory;

    public function sms($phone, $message, $ou_id = 1)
    {

        $settings = $this->settings();

        Log::info($message);

        if (!$settings) {
            return;
        }

        if ($ou_id === 2) {
            $this->fasthub($phone, $message);
        } elseif ($ou_id === 3) {
            $this->africas_talking($phone, $message);
        } else {

            if (!$settings->sms_default) {
                return;
            } elseif ($settings->sms_default == 'africas_talking') {
                if (env('AFRICASTALKING_ENV') == 'sandbox') {
                    $this->africas_talking_sandbox($phone, $message);
                } else {
                    $this->africas_talking($phone, $message);
                }
            } elseif ($settings->sms_default == 'twilio') {
                $this->twilio($phone, $message);
            } elseif ($settings->sms_default == 'advanta') {
                $this->advanta($phone, $message);
            } elseif ($settings->sms_default == 'celcomafrica') {
                $this->celcomafrica($phone, $message);
            } else {
                abort(422, 'Sms is not configured for this app');
            }
        }
    }

    public function settings()
    {
        return Setting::first();
    }

    public function twilio($phone, $message)
    {
        $settings = $this->settings();

        $twilio_sid = $settings->twilio_sid;
        $twilio_token = $settings->twilio_auth_token;
        $twilio_number = $settings->twilio_number;


        // $account_sid = getenv("TWILIO_SID");
        // $twilio_token = getenv("TWILIO_twilio_token");
        // $twilio_number = getenv("TWILIO_NUMBER");
        $client = new Client($twilio_sid, $twilio_token);
        $client->messages->create(
            $phone,
            ['from' => $twilio_number, 'body' => $message]
        );
    }

    public function africas_talking($phone, $message)
    {


        try {
            // $settings = $this->settings();
            // $username = $settings->africas_talkig_username;
            $apiKey = env('AFRICASTALKING_KEY');
            $username = env('AFRICASTALKING_USERNAME');

            $AT       = new AfricasTalking($username, $apiKey);
            // Get one of the services
            $sms      = $AT->sms();
            // Use the service
            $result   = $sms->send([
                'enqueue' => true,
                // 'from'    => $username,
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
    public function fasthub($phone, $message)
    {


        $provider = SmsProvider::where('provider', 'fasthub')->first();

        if (!$provider) {
            return;
        }
        try {
            $response = Http::post('https://bulksms.fasthub.co.tz/api/sms/send', [
                'auth' => [
                    'clientId' => $provider->client_id,
                    'clientSecret' => $provider->client_secret
                ],
                'messages' => [
                    [
                        'text' => $message,
                        'msisdn' => $phone,
                        'source' => $provider->username,
                        'coding' => 'GSM7'
                    ]
                ]
            ]);

            return $response->body();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function advanta($phone, $message)
    {


        $settings = $this->settings();

        $apiEndpoint = 'https://quicksms.advantasms.com/api/services/sendsms/';
        $apiKey = $settings->advanta_apikey;
        $partnerID = $settings->advanta_partnerid;
        $shortcode = $settings->advanta_shortcode;
        $recipientNumber = $phone;
        $message = $message;

        try {
            $response = Http::post($apiEndpoint, [
                'apikey' => $apiKey,
                'partnerID' => $partnerID,
                'message' => $message,
                'shortcode' => $shortcode,
                'mobile' => $recipientNumber,
            ]);

            if ($response->successful()) {
                // SMS sent successfully
                return response()->json(['message' => 'SMS sent successfully']);
            } else {
                // Error sending SMS

                return response()->json(['error' => 'Failed to send SMS'], 500);
            }
        } catch (Exception $e) {
            // Handle any exceptions
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function africas_talking_sandbox($phone, $message)
    {
        try {
            $phone = env('AFRICASTALKING_SANDBOX_PHONE');
            $username = env('AFRICASTALKING_SANDBOX_USERNAME'); // use 'sandbox' for development in the test environment
            $apiKey   = env('AFRICASTALKING_SANDBOX_KEY'); // use your sandbox app API key for development in the test environment

            $AT = new AfricasTalking($username, $apiKey);
            // Get one of the services
            $sms = $AT->sms();
            // Use the service
            $result   = $sms->send([
                'enqueue' => true,
                // 'from'    => 'logixsaas',
                'to'      => $phone,
                'message' => $message
            ]);
            return $result;
        } catch (Exception $e) {
            return;
        }
        // print_r($result);
    }

    public function celcomafrica($phone, $message)
    {
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
            $this->sms_count();

            echo ($response);
        } catch (Exception $e) {
            dd($e);
        }
    }

    public function sms_count()
    {
        $usage = Usage::latest()->first();

        if ($usage) {
            $date = Carbon::parse($usage->created_at);
            $now = Carbon::now();
            $diff = $date->diffInDays($now);

            if ($diff <= 30) {
                $usage->increment('sms');
            } else {
                $usage = new Usage();
                $usage->sms = 1;
                $usage->save();
            }
        } else {
            $usage = new Usage();
            $usage->sms = 1;
            $usage->save();
        }
        return $usage;
    }
}
