<?php

namespace App\Http\Controllers;

use App\Models\Addon;
use App\Models\Subscriber;
use Illuminate\Http\Request;

class AddonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $addons = Addon::all();
        $addons->transform(function ($addon) {
        });
    }

    public function addon($domain)
    {

        $Subscriber = Subscriber::where('tenant_id', $domain)->first();
        $sub_addons = $Subscriber->addons;
        $addons = Addon::all();
        $addons->transform(function ($addon) use ($sub_addons) {
            // dd($addon->id, $sub_addons);
            if ($sub_addons) {
                foreach ($sub_addons as $value) {
                    if ($value['id'] == $addon->id) {
                        // dd($value['id']);
                        $addon->count = (int)$value['count'];
                    } else {
                        $addon->count = 0;
                    }
                }
            } else {
                $addon->count = 0;
            }

            return $addon;
        });
        return $addons;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Addon  $addon
     * @return \Illuminate\Http\Response
     */
    public function show(Addon $addon)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Addon  $addon
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Addon $addon)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Addon  $addon
     * @return \Illuminate\Http\Response
     */
    public function destroy(Addon $addon)
    {
        //
    }
}
