<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [ 'phone', 'name', 'email', 'address', 'city', 'status',  'agent_sip',  'reference_no', 'platform', 'notes', 'recall_on', 'closed_on', 'closed', 'agent_id'];

    public function lead($data)
    {
        $lead = Lead::where('phone', $data['phone'])->first();

        if (!$lead) {
            // dd($data['name'] );
            // dd((array_key_exists('status', $data)) ? $data['status'] : null);
            $lead = Lead::create(
                [
                    'phone' => $data['phone'],
                    'name' => (array_key_exists('name', $data)) ? $data['name'] : null,
                    'email' => (array_key_exists('email', $data)) ? $data['email'] : null,
                    'address' => (array_key_exists('address', $data)) ? $data['address'] : null,
                    'status' => (array_key_exists('status', $data)) ? $data['status'] : null,
                    'agent_sip' => (Auth::check()) ? Auth::user()->agent_sip : null,
                    'agent_id' => (Auth::check()) ? Auth::id() : null,
                    'reference_no' => (array_key_exists('reference_no', $data)) ? $data['reference_no'] : make_reference_id('LD-', 5),
                    'platform' => (array_key_exists('platform', $data)) ? $data['platform'] : null,
                    'notes' => (array_key_exists('notes', $data)) ? $data['notes'] : null,
                    'recall_on' => (array_key_exists('recall_on', $data)) ? $data['recall_on'] : null,
                    'closed_on' => (array_key_exists('closed_on', $data)) ? $data['closed_on'] : null,
                    'closed' => (array_key_exists('closed', $data)) ? $data['closed'] : false,
                ]
            );
        }
        return $lead;
    }


    public function lead_upload($data)
    {
        $lead = Lead::whereDate('created_at', today())->where('phone', $data['phone'])->first();

        $number = Lead::max('id') + 1;
        $reference = make_reference_id('LC', $number);
        if (array_key_exists('reference_no', $data)) {
            if ($data['reference_no']) {
                $reference = ($data['reference_no']);
            }
        }

        if (!$lead) {
            $lead = Lead::create(
                [
                    'phone' => $data['phone'],
                    'name' => (array_key_exists('name', $data)) ? $data['name'] : null,
                    'email' => (array_key_exists('email', $data)) ? $data['email'] : null,
                    'address' => (array_key_exists('address', $data)) ? $data['address'] : null,
                    'status' => (array_key_exists('status', $data)) ? $data['status'] : null,
                    'agent_sip' => (array_key_exists('agent_no', $data)) ? $data['agent_no'] : null,
                    'reference_no' => $reference,
                    'platform' => (array_key_exists('platform', $data)) ? $data['platform'] : null,
                    'notes' => (array_key_exists('notes', $data)) ? $data['notes'] : null,
                    'recall_on' => (array_key_exists('recall_on', $data)) ? $data['recall_on'] : null,
                    'closed_on' => (array_key_exists('closed_on', $data)) ? $data['closed_on'] : null,
                    'closed' => (array_key_exists('closed', $data)) ? $data['closed'] : false
                ]
            );
        }
        return $lead;
    }

    /**
     * Get all of the comments for the Lead
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }


    public function getCreatedAtAttribute($date)
    {
        $timezone = optional(auth()->user())->timezone ?? config('app.timezone');
        return Carbon::parse($date)->timezone($timezone)->format('D d M Y H:i');
    }

    /**
     * Get all of the calls for the Lead
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function outbounds(): HasMany
    {
        return $this->hasMany(Call::class, 'callerNumber', 'phone');
    }

    public function inbounds(): HasMany
    {
        return $this->hasMany(Call::class, 'dialDestinationNumber', 'phone');
    }

    /**
     * Get the agent that owns the Lead
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    // protected $appends = array('calls');

    public function getCallsAttribute($value)
    {
        // There two calls return collections
        // as defined in relations.
        $outbound = $this->outbounds;
        $inbound = $this->inbounds;

        // Merge collections and return single collection.
        return ($outbound->merge($inbound));
    }

    public static function booted()
    {
        static::addGlobalScope('created_at', function (Builder $builder) {
            $builder->orderBy('id', 'DESC');
            // $builder->where('agent_sip', Auth::user()->agent_sip)->orderBy('id', 'DESC');
        });
    }

    public function file_transform($leads)
    {
        $data = [];
        foreach ($leads as $key => $lead) {
            $data_arr = [];
            $data_arr['name'] = (array_key_exists('name', $lead)) ? $lead['name'] : null;
            $data_arr['email'] = (array_key_exists('email', $lead)) ? $lead['email'] : null;
            $data_arr['phone'] = (array_key_exists('phone', $lead)) ? $lead['phone'] : null;
            $data_arr['status'] = (array_key_exists('status', $lead)) ? $lead['status'] : null;
            $data_arr['reference_no'] = (array_key_exists('reference', $lead)) ? $lead['reference'] : null;
            $data_arr['platform'] = (array_key_exists('platform', $lead)) ? $lead['platform'] : null;
            $data_arr['notes'] = (array_key_exists('notes', $lead)) ? $lead['notes'] : null;
            $data_arr['address'] = (array_key_exists('address', $lead)) ? $lead['address'] : null;
            $data_arr['city'] = (array_key_exists('city', $lead)) ? $lead['city'] : null;

            $data[] = $data_arr;
        }
        return $data;
    }
}
