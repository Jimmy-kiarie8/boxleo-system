<?php

namespace App\Http\Controllers;

use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ZoneController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::check()) {
            $cacheKey = 'zones_' . Auth::user()->ou_id;
            return Cache::remember($cacheKey, now()->addHours(10), function () {
                return Zone::all();
            });
        }
        return Zone::all();

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:zones'
        ]);
        $default = Zone::where('ou_id', $request->ou_id)->where('default_zone', 1)->first();
        $data = [
            'name' => $request->name,
            'ou_id' => $request->ou_id, 
            'code' => $request->code, 
            'default_zone' => ($default) ? false : true
        ];

        return Zone::create($data);


    }

    public function zones_store(Request $request)
    {
        // return $request->all();
        $request->validate([
            'name' => 'required|unique:zones'
        ]);
        return Zone::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Zone  $zone
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Zone::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Zone  $zone
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return Zone::find($id)->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Zone  $zone
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Zone::find($id)->delete();
    }

    public function zones_default($id)
    {
        $zone = Zone::find($id);
        $zone->default_zone = true;
        $zone->save();
        return $zone;
    }
}
