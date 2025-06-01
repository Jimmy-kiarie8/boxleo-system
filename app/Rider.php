<?php

namespace App;

use App\Models\Sale;
use App\Notifications\Rider\RiderResetPassword;
use App\Notifications\Rider\RiderVerifyEmail;
use App\Scopes\RiderScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class Rider extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'address', 'phone', 'ou_id', 'zone_id', 'rate_per_delivery'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new RiderResetPassword($token));
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new RiderVerifyEmail);
    }
    
    public static function booted()
    {
        static::addGlobalScope('name', function (Builder $builder) {
            $builder->orderBy('name', 'ASC');
        });
        static::addGlobalScope(new RiderScope);
    }

     /**
     * Specifies the user's FCM tokens
     *
     * @return string|array
     */
    // public function routeNotificationForFcm()
    // {
    //     return $this->getDeviceTokens();
    // }


    public function getCreatedAtAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', Carbon::parse($date))->format('D d M Y H:i');
    }

    /**
     * The sales that belong to the Rider
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function sales(): BelongsToMany
    {
        return $this->belongsToMany(Sale::class, 'rider_sales', 'rider_id', 'sale_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::updated(function () {
            $cacheKey = 'riders_' . Auth::user()->ou_id;
            Cache::forget($cacheKey);
        });    

        static::created(function () {
            $cacheKey = 'riders_' . Auth::user()->ou_id;
            Cache::forget($cacheKey);
        });
    }
}
