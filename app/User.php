<?php

namespace App;

use App\Models\Company;
use App\Models\Ou;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, SoftDeletes, HasApiTokens, HasRoles;
    protected $appends = ['is_client', 'is_admin'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'active'
    ];

    public $with = ['company', 'roles'];

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
        'drawer_open' => 'boolean',
        'active' => 'boolean'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
    public function ou()
    {
        return $this->belongsTo(Ou::class, 'company_id');
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

    // public function getOnlineAttribute($agent)
    // {
    //     if ($agent->hasRole('Call center')) {
    //         $agent->online = ($agent->on_break || $agent->status == 'offline' || $agent->call_active == true) ? false : true;
    //     }
    // }

    public function sales()
    {
        return $this->hasMany('App\Models\Sale');
    }
    public function drawers()
    {
        return $this->hasMany('App\Models\Drawer');
    }

    public function logged_user()
    {
        if (Auth::check()) {
            $user =  Auth::user();
            // $user->is_vendor = false;
            // $user->is_user = true;
            // $user->is_admin = false;
        } elseif (Auth::guard('seller')->check()) {
            $user =  Auth::guard('seller')->user();
            // $user->is_vendor = true;
            // $user->is_user = false;
            // $user->is_admin = false;
        }
        //  elseif (Auth::guard('admin')->check()) {
        //     $user =  Auth::guard('admin')->user();
        //     // $user->is_vendor = false;
        //     // $user->is_user = false;
        //     // $user->is_admin = true;
        // }
        return $user;
    }


    public function getCreatedAtAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', Carbon::parse($date))->format('D d M Y H:i');
    }

    public function getIsAdminAttribute()
    {
        return true;
    }

    public function getIsClientAttribute()
    {
        return false;
    }

    public function getIsUserAttribute()
    {
        return false;
    }
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($telco) {
            $relationMethods = ['sales'];

            foreach ($relationMethods as $relationMethod) {
                if ($telco->$relationMethod()->count() > 0) {
                    abort(422, 'The User cannot be deleted because it contains ' . $relationMethod);
                    // return false;
                }
            }
        });
    }




    // Polymophic Relationship

    // public function product()
    // {
    //     return $this->morphMany(Product::class, 'productable');
    // }

    // public function product_warehouse()
    // {
    //     return $this->morphMany(Product::class, 'productable');
    // }
}
