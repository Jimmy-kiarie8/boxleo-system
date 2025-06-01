<?php

namespace App\Models;

use Carbon\Carbon;
use Exception;
use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Passport\HasApiTokens;
// use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\WelcomeNotification\ReceivesWelcomeNotification;
use Spatie\WelcomeNotification\WelcomeNotification;
use Lab404\Impersonate\Models\Impersonate;
use Illuminate\Support\Facades\Http;

use PragmaRX\Google2FA\Google2FA;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use SoftDeletes, HasRoles, Loggable, ReceivesWelcomeNotification, Impersonate;

    protected $guard_name = 'web';


    // protected $appends = ['is_client', 'is_admin'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'active', 'agent_id', 'call_active', 'on_break', 'can_call', 'can_receive_calls', 'can_receive_orders', 'agent_sip'
    ];

    public $with = ['company', 'roles', 'ou'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'active' => 'boolean',
        'on_break' => 'boolean',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url', 'is_client', 'is_admin'
    ];


    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
    public function ou()
    {
        return $this->belongsTo(Ou::class, 'ou_id');
    }
    /**
     * Get all user permissions.
     *
     * @return bool
     */
    // public function getAllPermissionsAttribute()
    // {
    //     return $this->getAllPermissions();
    // }

    // public function getCreatedAtAttribute($date)
    // {
    //     return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('M-d-Y');
    // }


    /**
     * Get all user permissions.
     *
     * @return bool
     */
    public function getAllPermissionsAttribute()
    {
        return $this->getAllPermissions();
    }

    /**
     * Get all of the assigns for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function assigns(): HasMany
    {
        return $this->hasMany(Sale::class, 'agent_id');
    }

    public function sales()
    {
        return $this->hasMany('App\Models\Sale');
    }

    /**
     * Get all of the calls for the Sale
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function calls(): HasMany
    {
        // return $this->hasMany(Call::class, 'user_id');
        return $this->hasMany(Call::class, 'callerNumber', 'agent_sip');
    }

    public function drawers()
    {
        return $this->hasMany('App\Models\Drawer');
    }
    public function outbounds(): HasMany
    {
        return $this->hasMany(Call::class, 'callerNumber', 'agent_sip');
    }

    public function logged_user()
    {
        $user =  User::first();

        if (Auth::check()) {
            $user =  Auth::user();
            // $user->is_vendor = false;
            // $user->is_user = true;
            // $user->is_admin = false;
        } elseif (Auth::guard('seller')->check()) {
            $user =  Auth::guard('seller')->user();
        } elseif (Auth::guard('rider')->check()) {
            $user =  Auth::guard('rider')->user();
        }
        return $user;
    }

    public function getCreatedAtAttribute($value)
    {
        $timezone = optional(auth()->user())->timezone ?? config('app.timezone');
        return Carbon::parse($value)->timezone($timezone)->format('D d M Y H:i');
    }

    public function getIsAdminAttribute()
    {
        return true;
    }

    public function getIsClientAttribute()
    {
        return false;
    }

    // public function getOnlineAttribute($agent)
    // {
    //     if ($agent->hasRole('Call center')) {
    //         $agent->online = ($agent->on_break || $agent->status == 'offline' || $agent->call_active == true) ? false : true;
    //     }
    // }
    public function getIsUserAttribute()
    {
        return false;
    }
    public static function booted()
    {
        static::addGlobalScope('name', function (Builder $builder) {
            $builder->orderBy('name', 'ASC');
        });
        // static::addGlobalScope(new UserScope);
    }
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($telco) {
            $relationMethods = ['sales'];

            foreach ($relationMethods as $relationMethod) {
                if ($telco->$relationMethod()->count() > 0) {
                    abort(422, 'The user can not be deleted because it contains ' . $relationMethod);
                    // return false;
                }
            }
        });

        static::updated(function (User $user) {
            $cacheKey = 'users_' . $user->id;
            Cache::forget($cacheKey);


            $cacheKey1 = 'agents_' . $user->id;
            Cache::forget($cacheKey1);
        });

        static::created(function (User $user) {
            $cacheKey = 'users_' . $user->id;
            Cache::forget($cacheKey);

            $cacheKey1 = 'agents_' . $user->id;
            Cache::forget($cacheKey1);
        });
    }


    public function user_setup($ou_id)
    {
        // $ou = Ou::first();
        $tenant = tenant();
        $subscriber = tenant()->subscriber;
        // $tenant = $request->tenant;
        $user = User::where('email', $subscriber->email)->first();
        if (!$user) {
            $user = new User();
            $user->name =  $subscriber->name;
            $user->email = $subscriber->email;
            // $user->phone = $tenant['phone'];
            $user->ou_id = $ou_id;
            $company = Company::first();
            $user->company_id = $company->id;
            // $password = Hash::make(str_random(12));
            $user->password = Hash::make(str_random(12));
            // $user->activation_token = str_random(60);

            $user->save();
            $url = 'http://' . tenant('id') . env('CENTRAL_DOMAIN');
            // Mail::to($user->email)->send(new SubscriptionMail($user, $url, $password));

            $user->assignRole('Super admin');
            $this->welcome_mail($user);
        }

        $subscriber->setup = true;
        $subscriber->save();

        // return $user;

        // $tenant = Tenant::where('id', tenant('id'))->first()->subscriber;
        // $tenant->setup = true;
        // $tenant->save();
        return $tenant;
    }

    public function change_password($id, $password)
    {
        User::find($id)->update(['password' => Hash::make($password)]);
    }
    public function welcome_mail($user)
    {
        $expiresAt = now()->addDay();

        $user->sendWelcomeNotification($expiresAt);
        $expiresAt = now()->addDay();
        // $this->notify(new WelcomeNotification($expiresAt));

        Notification::send($user, new WelcomeNotification($expiresAt));
    }

    public function sendWelcomeNotification(\Carbon\Carbon $validUntil)
    {
        // $this->notify(new MyCustomWelcomeNotification($validUntil));
    }

    public function getAgent()
    {
        return  User::role('Call center')->get();
    }

    public function inbounds(): HasMany
    {
        return $this->hasMany(Call::class, 'dialDestinationNumber', 'agent_sip');
    }

    public function agent()
    {
        return  User::setEagerLoads([])->role('Call center')->get();
    }
    /**
     * By default, all users can impersonate anyone
     * this example limits it so only admins can
     * impersonate other users
     */
    public function canImpersonate(): bool
    {
        return $this->hasRole('Super admin');
    }

    /**
     * By default, all users can be impersonated,
     * this limits it to only certain users.
     */
    public function canBeImpersonated(): bool
    {
        return !$this->hasRole('Super admin');
    }

    public function getLToken()
    {
        $url = 'https://webrtc.africastalking.com/capability-token/request';

        $username = env('AFRICASTALKING_USERNAME');
        $apiKey    = env('AFRICASTALKING_KEY');
        $phone    = '+256323200768';

        $user = User::where('email', 'support@logixsaas.com')->first();
        $name = 'Logixsaas';
        try {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '{
                        "clientName": "' . $name . '",
                        "username": "' . $username . '",
                        "phoneNumber": "' . $phone . '"
                    }',
                CURLOPT_HTTPHEADER => array(
                    'apiKey: ' . $apiKey,
                    'Content-Type: application/json',
                    'Accept: application/json'
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            $data = json_decode($response);

            $agent = User::find($user->id);
            $agent->agent_token = $data->token;
            $agent->save();
            // $agent->update(['agent_token', $data->token]);


            // $user->update(['agent_token', $data->token]);
            echo $response;

            // $response = $client->request('POST', $url, [
            //     'headers' => $headers,
            //     'form_params' => $data
            // ]);
        } catch (Exception $e) {
            // Log::debug($e);
            echo "Error: " . $e->getMessage();
        }
    }

    public function getToken($phone, $ou_id)
    {
        // $users = $this->agent();
        $users = User::setEagerLoads([])->where('active', true)->where('can_call', true)->where('ou_id', $ou_id)->where('agent_sip', '!=', null)->get();
        // dd($phone, $ou_id);
        // Log::debug($users);


        $url = 'https://webrtc.africastalking.com/capability-token/request';

        if ($ou_id == 1) {
            // Kenya
            $username = env('AFRICASTALKING_USERNAME');
            $apiKey   = env('AFRICASTALKING_KEY');
            $phone    =  env('AFRICASTALKING_NUMBER');
        } elseif ($ou_id == 2) {
            // Tanzania
            $username = env('TZ_AFRICASTALKING_USERNAME');
            $apiKey   =  env('TZ_AFRICASTALKING_KEY');
            $phone    = env('TZ_AFRICASTALKING_NUMBER');
        } elseif ($ou_id == 3) {
            // Uganda
            $username = env('AFRICASTALKING_USERNAME');
            $apiKey   = env('AFRICASTALKING_KEY');
            $phone    = env('AFRICASTALKING_UG_NUMBER');
        }

        foreach ($users as $key => $user) {
            $name = explode(' ', $user->name);
            $name = $name[0];

            try {
                $response = Http::withHeaders([
                    'apiKey' => $apiKey,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ])->post($url, [
                    'clientName' => $name,
                    'username' => $username,
                    'phoneNumber' => $phone
                ]);

                $data = $response->json();
                echo $response;

                $agent = User::find($user->id);
                $agent->agent_token = $data['token'];
                $agent->save();
            } catch (Exception $e) {
                // Log::debug($e);
                echo "Error: " . $e->getMessage();
            }
        }
    }



    public function generateTwoFactorSecret()
    {
        $google2fa = new Google2FA();

        $this->google2fa_secret = $google2fa->generateSecretKey();
        $this->save();
    }

    public function getTwoFactorQRCodeUrl()
    {
        $google2fa = new Google2FA();

        return $google2fa->getQRCodeUrl(
            config('app.name'),
            $this->email,
            $this->google2fa_secret
        );
    }
}
