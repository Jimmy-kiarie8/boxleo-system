<?php

namespace App\Http\Controllers\Callcentre;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use AfricasTalking\SDK\AfricasTalking;
use App\Models\Call;
use App\Models\Lead;
use App\Models\Missedcall;
use App\Models\Ou;
use App\Models\Sale;
use App\Models\User;
use Carbon\Carbon;
use DateTimeZone;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class VoiceController extends Controller
{
    public function voice(Request $request)
    {
        // $this->outbound($request->callerNumber);
        $inhouse = false;

        $agents = [];
        // $user = new User;
        // $users = $user->agent(); 


        $cacheKey = 'agent_sip';
        // $users = Cache::remember($cacheKey, now()->addHours(10), function () {
        $users = User::where('agent_sip', '!=', null)->get('agent_sip');
        // });


        foreach ($users as $key => $user) {
            $agents[] = $user->agent_sip;
        }

        // return;
        if ($request->callSessionState == 'Ringing') {
            if (in_array($request->clientDialedNumber, $agents) && in_array($request->callerNumber, $agents)) {
                $inhouse = true;
                $this->outgoing($request->clientDialedNumber);
            } elseif (in_array($request->callerNumber, $agents)) {
                $this->outgoing($request->clientDialedNumber);
            } else {
                $callerCountryCode = $request->callerCountryCode;
                $this->incomming_call($request->callerNumber, $callerCountryCode);
            }

            // return;
            // $sessionId = $request->sessionId;
            // $isActive  = $request->isActive;
            $calls = new Call();
            $call_data = $request->all();

            if (in_array($request->callerNumber, $agents)) {
                $call_data['direction'] = 'Outbound';
                // $call_data['user_id'] = Auth::id();
            }
            $calls->create_call($call_data);


            // if (!$inhouse) {
            //     $lead = new Lead();
            //     $data['phone'] = $request->callerNumber;
            //     $lead->lead($data);
            // }
        }
    }

    public function event(Request $request)
    {
        // return;

        $agents = [];
        $cacheKey = 'agent_sip';
        $users = Cache::remember($cacheKey, now()->addHours(10), function () {
            return User::where('agent_sip', '!=', null)->get('agent_sip');
        });
        foreach ($users as $key => $user) {
            $agents[] = $user->agent_sip;
        }

        if (in_array($request->callerNumber, $agents)) {
            $call_data['direction'] = 'Outbound';
            $call_data['user_id'] = Auth::id();
        }
        $calls = new Call();
        $calls->create_call($request->all());
    }


    public function make_call()
    {
        $username = env('AFRICASTALKING_USERNAME');
        $apikey   = env('AFRICASTALKING_KEY');
        $AT = new AfricasTalking($username, $apikey);
        $voice    = $AT->voice();
        $from     = env('AFRICASTALKING_NUMBER');
        $to       = "+254743895505";
        try {
            $results = $voice->call([
                'from' => $from,
                'to'   => $to
            ]);

            echo ($results);
        } catch (Exception $e) {
            dd($e->getMessage());
        }

        return;

        // ... (existing code)

        // if ($isActive == 1) {
        // ... (existing code)

        // Make a POST request using Laravel HTTP client
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/x-www-form-urlencoded',
            'apiKey' => env('AFRICASTALKING_KEY'),
        ])->post('https://voice.africastalking.com/call', [
            'username' => 'BoxleoKenya',
            'to' => json_encode(['+254743895505']),
            'from' => '+254711082056',
        ]);

        // You can process the response as needed
        // For example, you might want to log the response
        // Log::info($response->body());

        // Return the response as XML
        return response($response->body())->header('Content-Type', 'application/json');
    }

    public function outgoing($phone)
    {
        // $phone = '254743895505';
        $response  = '<?xml version="1.0" encoding="UTF-8"?>';
        $response .= '<Response>';
        $response .= '<Dial record="true" sequential="true" phoneNumbers="' . $phone . '"/>';
        $response .= '</Response>';
        echo $response;
    }

    public function place_call($caller_no, $ou_id)
    {

        


        $call = new Call;
        $sale = $call->searchLeads($caller_no);

        if ($sale) {
            $agent_online = null;
            if ($sale->delivery_status === 'Dispatched') {
                // Route to Operations
                $agent_online = User::setEagerLoads([])->where('ou_id', $ou_id)->where('on_break', false)->where('call_active', false)->where('can_receive_calls', true)->where('agent_sip', '!=', null)->where('status', 'online')->where('active', true)->first();
            } elseif ($sale->agent) {
                $agent = $sale->agent;
                $agent_online = User::setEagerLoads([])->where('ou_id', $ou_id)->where('id', $agent->id)->where('on_break', false)->where('call_active', false)->where('can_receive_calls', true)->where('agent_sip', '!=', null)->where('status', 'online')->where('active', true)->first();
            };
            if ($agent_online) {
                return $agent_online;
            } else {
                # Say agents unavailable
                return 'not_available';
            }
        }
        $agents = User::withCount(['calls' => function (Builder $query) {
            $query->whereDate('created_at', Carbon::today());
        }])->where('ou_id', $ou_id)->where('can_receive_calls', true)->where('on_break', false)->inRandomOrder()->where('call_active', false)->where('active', true)->where('agent_sip', '!=', null)->where('status', 'online')->get();
        $agents = $agents->each->setAppends([]);

        if (count($agents) < 1) {
            if (User::where('status', 'online')->where('ou_id', $ou_id)->where('active', true)->count() > 0) {
                # Queue call
                return 'queue';
            } else {
                # Say agents unavailable
                return 'not_available';
            }
        } else {

            // $agents->transform(function ($user) {
            //     $user->calls_count = (int)$user->inbounds_count + (int)$user->outbounds_count;
            //     return $user;
            // });
            $user = collect($agents)->first();
            return $user;
        }
    }

    public function incomming_call($caller_no, $callerCountryCode)
    {

/*
        $response  = '<?xml version="1.0" encoding="UTF-8"?>';
        $response .= '<Response>';
        $response .= '<Say voice="woman" playBeep="false">Welcome to Boxleo Courier and Fulfillment Services Limited. Please wait while we transfer your call to the next available agent.This call may be recorded for internal training and quality purposes.</Say>';
        $response .= '</Response>';
         echo $response;*/


        // $this->queue();
        $missedcall = new Missedcall();

        $timezone = 'Africa/Nairobi'; // e.g., 'America/New_York'
        $currentTime = Carbon::now(new DateTimeZone($timezone));
        $targetTime = Carbon::createFromTimeString('20:00', $timezone);


        if ($currentTime->greaterThan($targetTime)) {
            // Current time is greater than 20:00
            $missedcall->missed($caller_no);
            return;
        }

        

        $ou_id = 1;
        if ($callerCountryCode) {
            $ou = Ou::where('phone_code', (int)$callerCountryCode)->first();
            $ou_id = ($ou) ? $ou->id : 1;
        } else {
        }



        $agent = $this->place_call($caller_no, $ou_id);



        if ($agent == 'queue') {
            //    $this->queue();

            /* $music = env('APP_URL') . '/audio/hold.mp3';

            $response  = '<?xml version="1.0" encoding="UTF-8"?>';
            $response .= '<Response>';
            $response .= '<Enqueue name="Boxleo" holdMusic="' . $music . '" />';
            $response .= '</Response>';
            */

            $response  = '<?xml version="1.0" encoding="UTF-8"?>';
            $response .= '<Response>';
            $response .= '<Say voice="woman" playBeep="false" >Welcome to Boxleo Courier and Fulfillment Services Limited. All our customer service representatives are currently busy, please hold and we will attend to you shortly</Say>';
            $response .= '</Response>';

            // Queue call
            $missedcall->missed($caller_no);
        } elseif ($agent == 'not_available') {
            $response  = '<?xml version="1.0" encoding="UTF-8"?>';
            $response .= '<Response>';
            $response .= '<Say voice="woman" playBeep="false" >Welcome to Boxleo Courier and Fulfillment Services Limited. All our customer service representatives are currently not available, please calls us later.</Say>';
            $response .= '</Response>';
            $missedcall->missed($caller_no);
        } else {

            $agent_sip = $agent->agent_sip;

            // $agent = env('AFRICASTALKING_NUMBER');
            // $agent = 'Shippatech.jimkiarie';
            // $agent = 'Shippatech.jimmy';
            // $agent = 'agent.Shippatech@ke.sip.africastalking.com';
            $response  = '<?xml version="1.0" encoding="UTF-8"?>';
            $response .= '<Response>';
            $response .= '<Dial record="true" sequential="true" phoneNumbers="' . $agent_sip . '"/>';
            $response .= '</Response>';
        }
        echo $response;
    }

    public function queue()
    {
        $music = env('APP_URL') . '/audio/hold.mp3';

        $response  = '<?xml version="1.0" encoding="UTF-8"?>';
        $response .= '<Response>';
        $response .= '<Enqueue name="Boxleo" holdMusic="' . $music . '" />';
        $response .= '</Response>';
        echo $response;

        /*
        $username = env('AFRICASTALKING_USERNAME');
        $apikey = env('AFRICASTALKING_KEY');
        $AT = new AfricasTalking($username, $apikey);

        // Get the voice service
        $voice    = $AT->voice();

        // Set your Africa's Talking phone number in international format
        $phoneNumber = env('AFRICASTALKING_NUMBER');

        try {
            // Fetch the queue
            $results = $voice->fetchQueuedCalls([
                "phoneNumber" => $phoneNumber
            ]);
            $entries = $results['data']->entries;
            return $entries;

        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
        */
    }

    public function dequeue()
    {
        // $queue = $this->queue();

        $phoneNumber = env('AFRICASTALKING_NUMBER');
        // $phoneNumber = $queue[0]->queueEntries[0]->callerNumber;

        $response  = '<?xml version="1.0" encoding="UTF-8"?>';
        $response .= '<Response>';
        $response .= ' <Dequeue name="Boxleo" phoneNumber="' . $phoneNumber . '" />';
        $response .= '</Response>';

        echo $response;
    }


    public function queue_call()
    {
        $queue = $this->queue();

        $phoneNumber = env('AFRICASTALKING_NUMBER');
        // $phoneNumber = $queue[0]->queueEntries[0]->callerNumber;

        $response  = '<?xml version="1.0" encoding="UTF-8"?>';
        $response .= '<Response>';
        $response .= ' <Dequeue phoneNumber="' . $phoneNumber . '" />';
        $response .= '</Response>';

        echo $response;
    }
    public function transfer(Request $request)
    {

        $username = env('AFRICASTALKING_USERNAME');
        $apiKey    = env('AFRICASTALKING_KEY');

        $call_session = Call::latest()->where('isActive', true)->where('callerNumber', Auth::user()->agent_sip)->first('sessionId');
        $data['sessionId'] = $call_session->sessionId;
        $data['username'] = $username;
        $data['phoneNumber'] = $request->transfer_to;
        // $data['callLeg'] = $request->callLeg;
        $data['holdMusicUrl'] = env('APP_URL') . '/audio/hold.mp3';
        $url = 'https://voice.africastalking.com/callTransfer';
        try {
            $client  = new Client();

            $response = $client->request('POST', $url, [
                'headers' => [
                    'apikey' => $apiKey,
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Accept' => 'application/json'
                ],
                'form_params' => $data
            ]);
            return;
            // echo $response;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function af_token()
    {
        if (Auth::user()->email == 'support@logixsaas.com' || Auth::user()->email == 'john.boxleo@gmail.com' || Auth::user()->email == 'itservices@boxleocourier.com') {
            $user = new User();
            $ous = Ou::where('phone', '!=', null)->get();


            // $ou_id = 3;
            // $user->getToken('+256312319968', $ou_id);

            // return;
            foreach ($ous as $key => $ou) {
                $ou_id = $ou->id;
                // $ou_id = 3;
                $user->getToken($ou->phone, $ou_id);
            }
        }
    }

    public function user_online()
    {
        return;
    }
}
