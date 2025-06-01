<?php

namespace App\Http\Controllers;

use App\Models\Charge;
use App\Models\Sale;
use App\Models\SellerService;
use App\Models\Service;
use App\Models\Shipcharge;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ServiceController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cacheKey = 'services_' . Auth::user()->ou_id;
        return Cache::remember($cacheKey, now()->addHours(10), function () {
            return Service::all();
        });
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request->all();
        // $service_query = '';
        $zone_to = collect($request->zone_to)->pluck('id')->toArray();
        $data = [
            'name' => $request->name,
            'service_from' => $request->service_from,
            'service_to' => $request->service_to,
            'charges' => $request->charges,
            'charges_type' => $request->charges_type,
            'city_from' => $request->city_from,
            'city_to' => $request->city_to,
            'zone_to' => $request->zone_to,
            'zone_from' => $request->zone_from,
            'conditions' => $request->conditions,
            'charge_on' => $request->charge_on,
            'charge_frequency' => $request->charge_frequency,
            'ou_id' => Auth::user()->ou_id
        ];
        return Service::create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Service::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // return $request->all();
        $data = [
            'name' => $request->name,
            'service_from' => $request->service_from,
            'service_to' => $request->service_to,
            'charges' => $request->charges,
            'charges_type' => $request->charges_type,
            'city_from' => $request->city_from,
            'city_to' => $request->city_to,
            'zone_to' => $request->zone_to,
            'zone_from' => $request->zone_from,
            'conditions' => $request->conditions,
            'charge_on' => $request->charge_on,
            'charge_frequency' => $request->charge_frequency
        ];
        return Service::find($id)->update($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Service::find($id)->delete();
    }

    public function charge_service($id)
    {
        // $status = 'Delivered';

        $order = Sale::setEagerLoads([])->with(['services' => function ($query) {
            //    $query->select(['name']);
        }])->where('id', $id)->first();
        $services = Service::with('zone')->get();

        $services->transform(function ($service) {
            $service->zone_name = $service->zone['name'];
            return $service;
        });
        // return $services;
        $charges = 0;
        foreach ($services as $service) {
            // dd(count($order->branches));
            $branch = (count($order->branches) > 0) ? Str::lower($order->branches[0]['name']) : null;
            // $branch = Str::lower($branch);
            // dd($branch, $service->zone_name);

            if ($service->service_from == 'Zone' && ($service->service_to == 'Zone' && $branch == Str::lower($service->zone_name))) {
                $sale_order = $this->query_loop($service, $id, $order);
            } elseif ($service->service_from == 'Zone' && $service->service_to == 'Anywhere') {
                $sale_order = $this->query_loop($service, $id, $order);
            } elseif ($service->service_from == 'Anywhere' && ($service->service_to == 'Zone' && $branch == Str::lower($service->zone_name))) {
                $sale_order = $this->query_loop($service, $id, $order);
            } elseif ($service->service_from == 'Anywhere' && $service->service_to == 'Anywhere') {
                $sale_order = $this->query_loop($service, $id, $order);
            }

            // $order_item = ($sale_order->first());
            // dd(DB::getQueryLog()); // Show results of log
        }

        // $order->history_comment = 'Order charges updated';
        // $order->save();
        return $order;
    }

    public function query_loop($service, $id, $order)
    {
        $sale_order = Sale::setEagerLoads([]);
        foreach ($service->conditions as $key => $condition) {
            if ($key == 0) {
                $sale_order = $sale_order->where('id', $id);
            }
            if ($condition['row']['Type'] == 'tinyint(1)') {
                if ($condition['value'] == 'true') {
                    $condition['value'] = true;
                } elseif ($condition['value'] == 'false') {
                    $condition['value'] = false;
                }
            }

            // $charge_exists = Charge::where('sale_id', $id)->where('service_id', $service->id)->first();
            if ($condition['operator'] == "When") {
                $sale_order = $sale_order->where($condition['row']['Field'], $condition['value']);
            } elseif ($condition['operator'] == "AND") {
                $sale_order = $sale_order->where($condition['row']['Field'], $condition['value']);
            } elseif ($condition['operator'] == "OR") {
                $sale_order = $sale_order->orWhere($condition['row']['Field'], $condition['value']);
            }
        }


        if ($sale_order->exists()) {
            $charge_exists = Charge::where('sale_id', $id)->where('service_id', $service->id)->first();

            if ($service->charge_frequency == 'Every time' && $charge_exists) {
                $charges = $charge_exists;
                if ($service->charges_type == 'Fixed') {
                    $amount = (int)$service->charges;
                } elseif ($service->charges_type == 'Percentage') {
                    $amount = $order->total_price * (int)$service->charges / 100;
                }
                $charges->amount +=  $amount;
                $charges->save();
            } elseif (!$charge_exists) {
                $charges = new Charge;

                if ($service->charges_type == 'Fixed') {
                    $amount = (int)$service->charges;
                } elseif ($service->charges_type == 'Percentage') {
                    $amount = $order->total_price * (int)$service->charges / 100;
                }
                $charges->amount =  $amount;
                $charges->sale_id =  $id;
                $charges->service_id =  $service->id;
                $charges->save();
            }
        }
    }


    public function charges_apply(Request $request)
    {
        // return $request->all();
        $sales = $request->data;
        $services_id = $request->services_id;


        $services = Service::setEagerLoads([])->whereIn('id', $services_id)->get();

        foreach ($sales as $key => $order) {
            DB::beginTransaction();
            try {
                DB::commit();

                $id = $order['id'];
                $order = Sale::setEagerLoads([])->with(['zones'])->find($id);
                $vendor_id = $order->seller_id;

                foreach ($services as $service) {
                    $zone_id = [];
                    foreach ($order->zones as $value) {
                        $zone_id[] = $value->pivot->zone_to;
                    }
                    $zones = (count($order->zones) > 0) ? Zone::select(['id', 'name'])->whereIn('id', $zone_id)->get() :  Zone::select(['id', 'name'])->where('id', 2)->get();
                    if ($zones) {

                        foreach ($zones as $key => $zone) {
                            $zone_name = Str::lower($zone->name);
                            $zone_id = Str::lower($zone->id);
                            if ($service->service_from == 'Zone' && ($service->service_to == 'Zone' && in_array($zone_id, $service->zone_to))) {
                                $this->query_service($service, $id, $order, $vendor_id);
                            } elseif ($service->service_from == 'Zone' && $service->service_to == 'Anywhere') {
                                $this->query_service($service, $id, $order, $vendor_id);
                            } elseif ($service->service_from == 'Anywhere' && ($service->service_to == 'Zone' && in_array($zone_id, $service->zone_to))) {
                                $this->query_service($service, $id, $order, $vendor_id);
                            } elseif ($service->service_from == 'Anywhere' && $service->service_to == 'Anywhere') {
                                $this->query_service($service, $id, $order, $vendor_id);
                            } else {
                            }
                        }
                    } else {
                        // dd(1);
                        Log::alert($order['order_no']);
                    }
                }
            } catch (\Throwable $th) {
                DB::rollBack();
                throw $th;
            }
        }
    }


    public function query_service($service, $id, $order, $vendor_id)
    {
        if ($order) {
            $charge_exists = Shipcharge::where('sale_id', $id)->where('service_id', $service->id)->first();

            if ($service->charge_frequency == 'Every time' && $charge_exists) {
                $charges = $charge_exists;
                if ($service->charges_type == 'Fixed') {
                    $amount = (int)$service->charges;
                } elseif ($service->charges_type == 'Percentage') {
                    $amount = $order->total_price * (int)$service->charges / 100;
                }
                $charges->amount +=  $amount;
                $charges->save();
            } elseif (!$charge_exists) {
                $charges = new Shipcharge;

                if ($service->charges_type == 'Fixed') {
                    $amount = (int)$service->charges;
                } elseif ($service->charges_type == 'Percentage') {
                    $amount = $order->total_price * (int)$service->charges / 100;
                }
                $charges->amount =  $amount;
                $charges->sale_id =  $id;
                $charges->service_id =  $service->id;
                $charges->save();
            }
        } else {
        }
    }

    public function vendor_services(Request $request)
    {
        $services = $request->services;
        $vendors = $request->vendors;
        $selected = $request->selected;
        foreach ($vendors as $key => $vendor) {
            SellerService::whereNotIn('service_id', $selected)->where('seller_id', $vendor['id'])->delete();
            foreach ($services as $service) {
                $service_create = new SellerService();
                $service_create->create($vendor['id'], $service['id'], $service['charges']);
            }
        }
    }
}
