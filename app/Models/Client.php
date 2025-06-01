<?php

namespace App\Models;

use App\Scopes\ClientScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Client extends Model
{
    protected $fillable = [
        'name', 'email', 'address', 'phone', 'user_id', 'seller_id', 'alt_phone', 'city', 'ou_id'
    ];
    // protected $appends = ['hasMultipleOrders'];

    /**
     * Get the comments for the blog post.
     */
    public function sales()
    {
        return $this->hasMany(Sale::class, 'client_id');
    }

    // public function getHasMultipleOrdersAttribute()
    // {
    //     return $this->sales->count() > 1;
    // }

    public function getCreatedAtAttribute($value)
    {
        $timezone = optional(auth()->user())->timezone ?? config('app.timezone');
        return Carbon::parse($value)->timezone($timezone)->format('D d M Y H:i');
    }

    public static function booted()
    {
        static::addGlobalScope('name', function (Builder $builder) {
            $builder->orderBy('name', 'ASC');
        });
    }

    // public function setPhoneAttribute($phone)
    // {
    //     // if (Auth::user()) {
    //     //     $user = new User();
    //     //     $user = $user->logged_user();
    //     // $ou_id = array_key_exists('ou_id', $this->attributes) ? $this->attributes['ou_id'] : $user->ou_id;

    //     if ($phone) {
    //         $cleanPhoneNumber = preg_replace('/\s+/', '', $phone); // Remove all empty spaces

    //         $cleanPhoneNumber = preg_replace('/\D/', '', $cleanPhoneNumber);


    //         $client_ou = Ou::find($ou_id);
    //         $phone_code = '';


    //         if ($client_ou) {
    //             $phone_code = $client_ou->phone_code;
    //         }
    //         // Format the phone number with the international code if missing
    //         if (strlen($cleanPhoneNumber) === 9) {
    //             $cleanPhoneNumber = $phone_code . $cleanPhoneNumber;
    //         } elseif (substr($cleanPhoneNumber, 0, 1) === '0') {
    //             $cleanPhoneNumber = $phone_code . substr($cleanPhoneNumber, 1);
    //         }
    //         $this->attributes['phone'] = $cleanPhoneNumber;
    //     }
    //     // }
    // }


    public function clean_phone($phone, $ou_id)
    {
        try {

            if ($phone && $ou_id) {
                $cleanPhoneNumber = preg_replace('/\s+/', '', $phone); // Remove all empty spaces

                $cleanPhoneNumber = preg_replace('/\D/', '', $cleanPhoneNumber);


                $client_ou = Ou::find($ou_id);
                $phone_code = '';


                if ($client_ou) {
                    $phone_code = $client_ou->phone_code;
                }
                // Format the phone number with the international code if missing
                if (strlen($cleanPhoneNumber) === 9) {
                    $cleanPhoneNumber = $phone_code . $cleanPhoneNumber;
                } elseif (substr($cleanPhoneNumber, 0, 1) === '0') {
                    $cleanPhoneNumber = $phone_code . substr($cleanPhoneNumber, 1);
                }
                return $cleanPhoneNumber;
            }
            return $phone;
        } catch (\Throwable $th) {
            // Log::debug($th);
            throw $th;
        }
    }


    public function setAltPhoneAttribute($phone)
    {
        if ($phone) {
            $cleanPhoneNumber = preg_replace('/\s+/', '', $phone); // Remove all empty spaces

            $cleanPhoneNumber = preg_replace('/\D/', '', $cleanPhoneNumber);

            $client_ou = Ou::find($this->attributes['ou_id']);
            $phone_code = '';
            if ($client_ou) {
                $phone_code = $client_ou->phone_code;
            }
            // Format the phone number with the international code if missing
            if (strlen($cleanPhoneNumber) === 9) {
                $cleanPhoneNumber = $phone_code . $cleanPhoneNumber;
            } elseif (substr($cleanPhoneNumber, 0, 1) === '0') {
                $cleanPhoneNumber = $phone_code . substr($cleanPhoneNumber, 1);
            }
            $this->attributes['alt_phone'] = $cleanPhoneNumber;
        }
    }

    public function cleanPhone($phone)
    {
        $cleanPhoneNumber = preg_replace('/\s+/', '', $phone); // Remove all empty spaces

        $cleanPhoneNumber = preg_replace('/\D/', '', $cleanPhoneNumber);

        // Format the phone number with the international code if missing
        if (strlen($cleanPhoneNumber) === 9) {
            $cleanPhoneNumber = '254' . $cleanPhoneNumber;
        } elseif (substr($cleanPhoneNumber, 0, 1) === '0') {
            $cleanPhoneNumber = '254' . substr($cleanPhoneNumber, 1);
        }

        return $cleanPhoneNumber;
    }

    /**
     * Get the client that owns the Client
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function destinationCalls()
    {
        return $this->hasMany(Call::class, 'dialDestinationNumber', 'phone');
    }

    public function callerCalls()
    {
        return $this->hasMany(Call::class, 'callerNumber', 'phone');
    }

    /**
     * Get all of the billing for the Client
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function billing(): HasOne
    {
        return $this->hasOne(Billingaddress::class, 'client_id');
        // return $this->hasMany(Billingaddress::class, 'client_id');
    }

    /**
     * Get all of the shipping for the Client 
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function shipping(): HasOne
    {
        return $this->hasOne(Shippingaddress::class, 'client_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new ClientScope);
        static::deleting(function ($telco) {
            $relationMethods = ['sales'];

            foreach ($relationMethods as $relationMethod) {
                if ($telco->$relationMethod()->count() > 0) {
                    return false;
                }
            }
        });
    }
}
