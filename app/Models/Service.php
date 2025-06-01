<?php

namespace App\Models;

use App\Scopes\ServiceScope;
use App\Seller;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Service extends Model
{
    use HasFactory;

    public $fillable = ['name', 'service_from', 'service_to', 'charges', 'charges_type', 'city_from', 'city_to', 'zone_to', 'zone_from', 'conditions', 'charge_on', 'charge_frequency', 'charged_on', 'ou_id'];

    // public $with = ['zone'];

    protected $casts = ['zone_from' => 'integer'];

    public function setConditionsAttribute($value)
    {
        $this->attributes['conditions'] = serialize($value);
    }
    public function getConditionsAttribute($value)
    {
        return unserialize($value);
    }
    public function getZoneToAttribute($value)
    {
        return unserialize($value);
    }
    public function setZoneToAttribute($value)
    {
        $this->attributes['zone_to'] = serialize($value);
    }

    /**
     * The sales that belong to the Service
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function sales(): BelongsToMany
    {
        return $this->belongsToMany(Sale::class, 'shipcharges', 'sale_id', 'service_id')->withPivot('amount');
    }
    /**
     * The sellers that belong to the Service
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function sellers(): BelongsToMany
    {
        return $this->belongsToMany(Seller::class,  'seller_services', 'seller_id', 'service_id')->withPivot('amount');
    }

    /**
     * Get the zone that owns the Service
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    // public function zone(): BelongsTo
    // {
    //     return $this->belongsTo(Zone::class, 'zone_to');
    // }

    public function getCreatedAtAttribute($value)
    {
        $timezone = optional(auth()->user())->timezone ?? config('app.timezone');
        return Carbon::parse($value)->timezone($timezone)->format('D d M Y');
    }

    public function charge_service($id, $old_status, $vendor_id)
    {
        $order = Sale::setEagerLoads([])->with(['services' => function ($query) {
            //    $query->select(['name']);
        }, 'zones'])->where('id', $id)->first();

        if ($order) {


            // $seller_service = SellerService::where('seller_id', $vendor_id)->get('service_id');
            $ids = SellerService::where('seller_id', $vendor_id)->get()->pluck('service_id');
            if (count($ids) < 1) {
                return;
            }
            // $ids = [];
            // foreach ($seller_service as $serv) {
            //     $ids[] = $serv['service_id'];
            // }

            $services = Service::whereIn('id', $ids)->get();

            $services->transform(function ($service) {
                $zones_arr = [];
                if ($service->zone_to) {
                    $zones = Zone::setEagerLoads([])->whereIn('id', $service->zone_to)->get('name');
                    if (count($zones)) {
                        foreach ($zones as $key => $value) {
                            $zones_arr[] = Str::lower($value->name);
                        }
                    }
                }


                $service->zone = $zones_arr;
                return $service;
            });



            foreach ($services as $service) {
                $zone_id = [];
                foreach ($order->zones as $value) {
                    $zone_id[] = $value->pivot->zone_to;
                }
                $zones = (count($order->zones) > 0) ? Zone::select(['id', 'name'])->whereIn('id', $zone_id)->get() : null;
                if ($zones) {
                    if (count($zones) > 0) {

                        foreach ($zones as $key => $zone) {
                            $zone_name = Str::lower($zone->name);
                            if ($service->service_from == 'Zone' && ($service->service_to == 'Zone' && in_array($zone_name, $service->zone))) {
                                $this->query_loop($service, $id, $order, $old_status, $vendor_id);
                            } elseif ($service->service_from == 'Zone' && $service->service_to == 'Anywhere') {
                                $this->query_loop($service, $id, $order, $old_status, $vendor_id);
                            } elseif ($service->service_from == 'Anywhere' && ($service->service_to == 'Zone' && in_array($zone_name, $service->zone))) {
                                $this->query_loop($service, $id, $order, $old_status, $vendor_id);
                            } elseif ($service->service_from == 'Anywhere' && $service->service_to == 'Anywhere') {
                                $this->query_loop($service, $id, $order, $old_status, $vendor_id);
                            } else {
                            }
                        }
                    }
                } else {
                    // Log::debug("Error 2: ");
                }
            }
            return $order;
        } else {
        }
    }

    public function query_loop($service, $id, $order, $old_status, $vendor_id)
    {

        $seller_service = SellerService::where('service_id', $service->id)->where('seller_id', $vendor_id)->first('amount');

        $charge_amount = $seller_service->amount;

        // DB::enableQueryLog(); // Enable query log

        $sale_order = Sale::setEagerLoads([])->where('id', $id);

        $conditions = $service->conditions;
        $sale_order = $sale_order->where(function ($query) use ($conditions, $old_status) {
            foreach ($conditions as $condition) {
                if ($condition['condition'] == 'is updated to') {
                    if ($condition['row']['Type'] == 'tinyint(1)') {
                        $condition['value'] = ($condition['value'] == 'true') ? true : false;
                    }
                    if ($condition['operator'] == 'When' || $condition['operator'] == 'AND') {
                        $query->where($condition['row']['Field'], $condition['value']);
                    } else {
                        $query->orWhere($condition['row']['Field'], $condition['value']);
                    }
                } elseif ($condition['condition'] == 'is updated from' && $old_status == $condition['value']) {
                } else {
                    $query->where('id', 0);
                }
            }
        });
        // dd(DB::getQueryLog()); // Show results of log

        if ($sale_order->exists()) {
            // Log::debug("Log 5: ");

            $charge_exists = Shipcharge::where('sale_id', $id)->where('service_id', $service->id)->first();
            // $charge_exists = DB::table('shipcharges')->where('sale_id', $id)->where('service_id', $service->id)->first();

            if ($service->charge_frequency == 'Every time' && $charge_exists) {
   
                $charges = new Shipcharge();

                if ($service->charges_type == 'Fixed') {
                    $amount = (int)$charge_amount;
                } elseif ($service->charges_type == 'Percentage') {
                    $amount = ($order->total_price * $charge_amount) / 100;
                }
                $amount = $charges->amount + $amount;

                $charges->amount =  $amount;
                $charges->sale_id =  $id;
                $charges->service_id =  $service->id;
                $charges->save();
            } elseif (!$charge_exists) {
                // Log::debug("Log 7: Once");
                $charges = new Shipcharge();

                if ($service->charges_type == 'Fixed') {
                    $amount = (int)$charge_amount;
                } elseif ($service->charges_type == 'Percentage') {
                    $amount = ($order->total_price * $charge_amount) / 100;
                }

                $charges->amount =  $amount;
                $charges->sale_id =  $id;
                $charges->service_id =  $service->id;
                $charges->save();
            }
        }
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new ServiceScope);
    }
}
