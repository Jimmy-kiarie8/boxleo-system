<?php

namespace App\Models;

use App\Scopes\ZoneScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Zone extends Model
{
    use HasFactory, SoftDeletes;

    public $fillable = ['name','ou_id', 'code', 'default_zone', 'manager', 'latitude', 'longitude'];

    /**
     * Get the service associated with the Zone
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function service(): HasOne
    {
        return $this->hasOne(Service::class, 'zone_to');
    }

    /**
     * Get all of the sales for the Zone
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sales(): BelongsToMany
    {
        return $this->belongsToMany(Sale::class, 'sale_zones')->withPivot('zone_to');
    }


    public function default_zone($zone_code, $ou)
    {
        // dd($zone_code, $ou);
        $zones = Zone::where('ou_id', $ou)->where('default_zone', true);

        if(count($zones->get()) > 0) {
            $zones->update(['default_zone' => false]);
        }

        // dd(Zone::where('code', $zone_code)->first());
        Zone::where('ou_id', $ou)->where('code', $zone_code)->first()->update(['default_zone' => true]);
    }

    public static function booted()
    {
        static::addGlobalScope(new ZoneScope);
    }

    public function getCreatedAtAttribute($value)
    {
        $timezone = optional(auth()->user())->timezone ?? config('app.timezone');
        return Carbon::parse($value)->timezone($timezone)->format('D d M Y');
    }
}
