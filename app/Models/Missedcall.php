<?php

namespace App\Models;

use App\Scopes\OrderScope;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Missedcall extends Model
{
    use HasFactory;
    protected $fillable = ['callerNumber', 'sessionId', 'direction', 'agent_id'];

    public function missed($phone)
    {
        // $message = 'Thank you for calling Boxleo courier, Our agents are currently not available. We will call you as soon as possible.';
        // $this->celcomafrica($phone, $message);
        $phone = str_replace('+', '', $phone);

        // $agent_id = $this->searchPhone($phone);
        $client = Client::where('phone', $phone)->select('id', 'phone')->first();
        $agent_id = 0;
        if ($client) {
            $sale = Sale::withoutGlobalScope(OrderScope::class)->where('client_id', $client->id)->first('agent_id');

            if ($sale) {
                $agent_id = $sale->agent_id;
            }
        }
        return Missedcall::updateOrCreate(
            [
                'callerNumber' => $phone
            ],
            [
                'agent_id' => $agent_id
            ]
        );
    }

    public function setPhoneAttribute($phone)
    {
        $cleanedPhoneNumber = preg_replace('/\D/', '', $phone);
        // $cleanedPhoneNumber = str_replace([' ', '(', ')', '-', '+'], '', $phone);
        $this->attributes['callerNumber'] = $cleanedPhoneNumber;
        
    }
    
    public function client()
    {
        return $this->belongsTo(Client::class, 'callerNumber', 'phone');
    }


    public function searchPhone($phone)
    {
        
        // Remove any non-digit characters from the phone number
        $cleanPhoneNumber = preg_replace('/\D/', '', $phone);

        // Format the phone number with the international code if missing
        if (strlen($cleanPhoneNumber) === 9) {
            $cleanPhoneNumber = '254' . $cleanPhoneNumber;
        }
        $client = Client::where('phone', $cleanPhoneNumber)->select('id', 'phone')->first();

        if ($client) {
            return $sale = Sale::where('client_id', $client->id)->first('agent_id');
            return ($sale) ? $sale->agent_id : '';
        } else {
            return;
        }
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

        $clients = Client::with(['sales' => function ($q) {
            $q->withoutGlobalScope(OrderScope::class)->setEagerLoads([])->with(['products' => function ($query) {
                $query->setEagerLoads([]);
            }]);
        }])->where('phone', $cleanPhoneNumber)->first();

        if ($clients) {
            $clients->sales->transform(function ($order) {
                $product_name = '';
                // $qty = 0;
                foreach ($order->products as $key => $product) {
                    $qty = $product->pivot->quantity;
                    if ($key == 0) {
                        $product_name = $product->product_name . '(Qty: ' . $qty .  ') ';
                    } else {
                        $product_name = $product_name . '(Qty: ' . $qty . ') ' . '|' . $product->product_name;
                    }
                }

                $order->product_name = $product_name;
                // $order->qty  = $qty;
                return $order;
            });
        }

        return $clients;
    }

    public function unreachabe($data)
    {
        return;
        $message = '';

        $exists = Sms::where('phone', $data)->exists();
        if (!$exists) {
            $sms = new Sms;
            $sms->phone = $data;
            $sms->save();
        }
    }

    public function getCreatedAtAttribute($date)
    {
        $timezone = optional(auth()->user())->timezone ?? config('app.timezone');
        return Carbon::parse($date)->timezone($timezone)->format('D d M Y H:i');
    }

    public function celcomafrica($phone, $message)
    {
    }
}
