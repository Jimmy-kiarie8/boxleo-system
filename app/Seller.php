<?php

namespace App;

use App\Models\Company;
use App\Models\Ou;
use App\Models\Product;
use App\Models\ProductSale;
use App\Models\Sale;
use App\Models\Service;
use App\Models\ShipmentRequest;
use App\Models\ShippingReq;
use App\Models\Storedetails;
use App\Models\VendorOu;
use App\Notifications\Seller\SellerResetPassword;
use App\Notifications\Seller\SellerVerifyEmail;
use App\Scopes\SellerScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Lab404\Impersonate\Models\Impersonate;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class Seller extends Authenticatable
{
    use Notifiable, HasApiTokens, HasRoles, SoftDeletes, Impersonate;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'active', 'address', 'phone', 'order_no_start', 'order_no_end', 'autogenerate', 'company_id', 'ou_id', 'portal_active', 'reference', 'order_prefix', 'date', 'sheet_update', 'send_sms', 'telegram_notifications'
    ];

    // public $with = ['company'];
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

    protected $appends = ['is_vendor', 'is_admin', 'is_user'];

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new SellerResetPassword($token));
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
    public function ou()
    {
        return $this->belongsTo(Ou::class, 'company_id');
    }
    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new SellerVerifyEmail);
    }

    public function productsales()
    {
        return $this->hasMany(ProductSale::class);
    }
    public function products()
    {
        return $this->hasMany(Product::class, 'vendor_id');
    }

    public function sales()
    {
        return $this->hasMany(Sale::class, 'seller_id');
    }

    public function storedetail()
    {
        return $this->hasOne(Storedetails::class);
    }
    /**
     * Get all of the shipments for the Seller
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function shipments(): HasMany
    {
        return $this->hasMany(ShipmentRequest::class, 'seller_id');
    }
    public function shipping(): HasMany
    {
        return $this->hasMany(ShippingReq::class, 'seller_id');
    }

    /**
     * The services that belong to the Seller
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'seller_services', 'seller_id', 'service_id');
    }
    public function ous(): BelongsToMany
    {
        return $this->belongsToMany(Ou::class, 'vendor_ous','vendor_id', 'ou_id');
    }

    public function getCreatedAtAttribute($value)
    {
        $timezone = optional(auth()->user())->timezone ?? config('app.timezone');
        return Carbon::parse($value)->timezone($timezone)->format('D d M Y H:i');
    }

    public function getIsAdminAttribute()
    {
        return false;
    }

    public function getIsVendorAttribute()
    {
        return true;
    }

    public function getIsUserAttribute()
    {
        return false;
    }

    public function invoices()
    {
        return $this->hasManyThrough('App\Models\Invoice', 'App\Models\Sale');
    }

    public function myServices($id)
    {
        $seller = Seller::find($id);
        $selle_serv =  $seller->services();
        $ids = [];

        foreach ($selle_serv as $value) {
            $ids[] = $value['id'];
        }

        // $seller_services = Service::whereIn('id', $ids)->get();
        $services = Service::all();

        $services->transform(function($service) use($ids) {
            if (in_array($service->id, $ids)) {
                $service->checked = true;
            } else {
                $service->checked = true;
            }
            return $service;
        });

        return $services;
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($telco) {
            $relationMethods = ['sales', 'productsales', 'products'];

            foreach ($relationMethods as $relationMethod) {
                if ($telco->$relationMethod()->count() > 0) {
                    abort(422, 'The vendor can not be deleted because they have ' . $relationMethod);
                    // return false;
                }
            }
        });

        static::updated(function (Seller $seller) {
            $cacheKey = 'seller_' . $seller->id;
            Cache::forget($cacheKey);


            $cacheKey = 'sellers_' . Auth::user()->ou_id;
            Cache::forget($cacheKey);
            // Log::info('forget' . $cacheKey);
        });    

        static::created(function () {
            $cacheKey = 'sellers_' . Auth::user()->ou_id;
            Cache::forget($cacheKey);
            // Log::info('forget' . $cacheKey);
        });
    }

    public static function booted()
    {
        static::addGlobalScope('name', function (Builder $builder) {
            $builder->orderBy('name', 'ASC');
        });
        static::addGlobalScope(new SellerScope);
    }

    // Polymophic Relationship

    // public function product()
    // {
    //     return $this->morphOne(Product::class, 'productable');
    // }

    // public function product_warehouse()
    // {
    //     return $this->morphMany(Product::class, 'productable');
    // }
}
