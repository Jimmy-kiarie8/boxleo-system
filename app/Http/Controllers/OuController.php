<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Ou;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class OuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $cacheKey = 'ous';

        // Log if the cache key exists
        // $cacheExists = Cache::has($cacheKey);
        // Log::info("Cache key '{$cacheKey}' exists: " . ($cacheExists ? 'Yes' : 'No'));



        return Cache::remember($cacheKey, now()->addWeek(), function () use($cacheKey) {
            // Log::info("Cache miss for key '{$cacheKey}'. Fetching from database.");
            return Ou::all();
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
        $data = $request->validate([
            'ou' => 'required',
            'currency_code' => 'required',
            'ou_code' => 'required',
        ]);
        $company = Company::first();
        if (!$company) {
            abort(422, "We cant find an organisation");
        }
        $data['company_id'] = $company->id;
        $data['address'] = $request->address;
        $data['currency_code'] = $request->currency_code;
        $data['ou'] = $request->ou;
        $data['ou_code'] = $request->ou_code;
        $data['ou_phone'] = $request->ou_phone;
        $data['phone'] = $request->phone;
        $data['phone_code'] = $request->phone_code;
        $data['waybill_terms'] = $request->waybill_terms;
        $ou = Ou::where('ou', $request->ou)->orWhere('ou_code', $request->ou_code)->first();

        if (!$ou) {
            $ou = Ou::create($data);
        } else {
            abort(422, "Operating Unit with code " . $data['ou_code'] . " already exists");
        }
        return $ou;
        // return $request->all();
        //    return Ou::updateOrCreate([
        //         'ou' => $request->ou
        //     ],
        //     [
        //         'currency_code' => $request->currency_code,
        //         'ou_code' => $request->ou_code
        //     ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ou\Ou  $ou
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ou\Ou  $ou
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ou\Ou  $ou
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return Ou::find($id)->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ou\Ou  $ou
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function ou_switch(Request $request)
    {
        $data = $request->validate([
            'ou' => 'required',
        ]);

        if (Auth::check()) {
            if (Auth::user()->hasRole('Super admin') || Auth::user()->hasPermissionTo('Order filter by OU')) {
                $user = User::find(Auth::id());
                $user->ou_id = $request->ou;
                $user->save();
            } else {
                abort(422, 'Not allowed');
            }
        }
    }
}
