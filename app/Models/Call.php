<?php

namespace App\Models;

use App\Scopes\OrderScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Call extends Model
{
    use HasFactory;


    protected $fillable = [
        'callSessionState',
        'direction',
        'callerCountryCode',
        'durationInSeconds',
        'amount',
        'callerNumber',
        'destinationNumber',
        'callerCarrierName',
        'status',
        'sessionId',
        'isActive',
        'currencyCode',
        'dialDestinationNumber',
        'dialDurationInSeconds',
        'call_status',
        'recordingUrl',
        'dialStartTime',
        'callStartTime',
        'lastBridgeHangupCause',
        'user_id',
        'sale_id',
        'downloaded',
        'recordUrl',
    ];

    public function create_call($data)
    {
        $call_status = 'No answer';
        if (array_key_exists('durationInSeconds', $data)) {
            if ($data['durationInSeconds'] > 0) {
                $call_status = 'Answered';
            } else {
                $sms_ = new Missedcall();
                // $sms_->unreachabe($data['clientDialedNumber']);
            }
            if ($data['direction'] == 'Outbound') {
                $call_charge = $data['durationInSeconds'];
            } else {
                $call_charge = $data['durationInSeconds'];
            }
        }
        $amount = (array_key_exists('amount', $data)) ? $data['amount'] : 0;
        // $amount = (array_key_exists('amount', $data)) ? $data['amount'] + $call_charge : 0;
        $charged_amount = (array_key_exists('amount', $data)) ? $data['amount']  : 0;

        $agent_no = null;
        $order = null;
        if (array_key_exists('callerNumber', $data)) {
            if (Str::contains($data['callerNumber'], "BoxleoKenya")) {
                // The caller number is an agent

                if (array_key_exists('clientDialedNumber', $data)) {
                    $order = $this->searchPhone($data['clientDialedNumber']);
                    $agent_no = $data['callerNumber'];
                }
            } else {
                // Incomming calls
                $order = $this->searchPhone($data['callerNumber']);
                // $agent_no = $data['callerNumber'];
            }
        }


        // $charged_amount = (array_key_exists('durationInSeconds', $data)) ? $data['durationInSeconds'] * (4/60)  : 0;
        Call::updateOrCreate(
            ['sessionId' => $data['sessionId']],
            [
                'callSessionState' => (array_key_exists('callSessionState', $data)) ? $data['callSessionState'] : null,
                'direction' => (array_key_exists('direction', $data)) ? $data['direction'] : null,
                'callerCountryCode' => (array_key_exists('callerCountryCode', $data)) ? $data['callerCountryCode'] : null,
                'durationInSeconds' => (array_key_exists('durationInSeconds', $data)) ? $data['durationInSeconds'] : 0,
                'amount' => $amount,
                'charged_amount' => $charged_amount,
                'callerNumber' => (array_key_exists('callerNumber', $data)) ? $data['callerNumber'] : null,
                'destinationNumber' => (array_key_exists('destinationNumber', $data)) ? $data['destinationNumber'] : null,
                'callerCarrierName' => (array_key_exists('callerCarrierName', $data)) ? $data['callerCarrierName'] : null,
                'status' => (array_key_exists('status', $data)) ? $data['status'] : null,
                'sessionId' => (array_key_exists('sessionId', $data)) ? $data['sessionId'] : null,
                'isActive' => (array_key_exists('isActive', $data)) ? $data['isActive'] : 0,
                'currencyCode' => (array_key_exists('currencyCode', $data)) ? $data['currencyCode'] : null,
                'dialDestinationNumber' => (array_key_exists('dialDestinationNumber', $data)) ? $data['dialDestinationNumber'] : null,
                'dialDurationInSeconds' => (array_key_exists('dialDurationInSeconds', $data)) ? $data['dialDurationInSeconds'] : null,
                'call_status' => $call_status,
                'recordingUrl' => (array_key_exists('recordingUrl', $data)) ? $data['recordingUrl'] : null,
                'dialStartTime' => (array_key_exists('dialStartTime', $data)) ? $data['dialStartTime'] : null,
                'user_id' => $agent_no,
                'callStartTime' => (array_key_exists('callStartTime', $data)) ? $data['callStartTime'] : null,
                'lastBridgeHangupCause' => (array_key_exists('lastBridgeHangupCause', $data)) ? $data['lastBridgeHangupCause'] : null,
                'sale_id' => ($order) ? $order->id : null
            ]
        );
    }

    public function getCreatedAtAttribute($date)
    {
        $timezone = optional(auth()->user())->timezone ?? config('app.timezone');
        return Carbon::parse($date)->timezone($timezone)->format('D d M Y H:i');
    }

    public function getDurationInSecondsAttribute($data)
    {
        return $data / 60;
    }

    public function destinationClient()
    {
        return $this->belongsTo(Client::class, 'dialDestinationNumber', 'phone');
    }

    public function callerClient()
    {
        return $this->belongsTo(Client::class, 'callerNumber', 'phone');
    }

    /**
     * Get the sale that owns the Call
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }
    /**
     * Get the lead that owns the lead
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lead(): BelongsTo
    {
        return $this->belongsTo(Call::class, 'phone');
    }

    /**
     * Get the users that owns the Call
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class, 'callerNumber');
    }



    public function searchPhone($phone)
    {
        // Remove any non-digit characters from the phone number
        $cleanPhoneNumber = preg_replace('/\s+/', '', $phone); // Remove all empty spaces

        $cleanPhoneNumber = preg_replace('/\D/', '', $cleanPhoneNumber);

        // Format the phone number with the international code if missing
        if (strlen($cleanPhoneNumber) === 9) {
            $cleanPhoneNumber = '254' . $cleanPhoneNumber;
        }
        // Call::where()->get();

        $client = Client::latest()->where('phone', $cleanPhoneNumber)->first();

        return Sale::setEagerLoads([])->when($client, function ($q) use ($client) {
            return $q->where('client_id', $client->id);
        })->latest()->first('id');
    }


    public function searchLeads($phone)
    {
        // Remove any non-digit characters from the phone number
        $cleanPhoneNumber = preg_replace('/\D/', '', $phone);

        // Format the phone number with the international code if missing
        if (strlen($cleanPhoneNumber) === 9) {
            $cleanPhoneNumber = '254' . $cleanPhoneNumber;
        }
        // Call::where()->get();

        $client = Client::with(['sales' => function ($q) {
            $q->withoutGlobalScope(OrderScope::class)->setEagerLoads([])->with(['agent' => function($query) {
                $query->setEagerLoads([]);
            }]);
        }])->where('phone', $cleanPhoneNumber)->first();

        if ($client) {
            return Sale::where('client_id', $client->id)->latest()->first();
        }
        return false;

        // return (!empty($client->sales) > 0) ? $client->sales[0] : null;
    }
}
