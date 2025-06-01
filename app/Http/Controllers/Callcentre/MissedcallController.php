<?php

namespace App\Http\Controllers\Callcentre;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Missedcall;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MissedcallController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $missedcalls = Missedcall::pluck('callerNumber')->toArray();
        $clients = Client::whereIn('phone',str_replace('+', '', $missedcalls))->pluck('id')->toArray();
        $sales = Sale::whereIn('client_id', $clients)->where('agent_id', Auth::id())->pluck('client_id')->toArray();
        $client_ids = Client::whereIn('id', $sales)->pluck('phone')->toArray();
        return  $missedcalls = Missedcall::orderBy('id', 'desc')->whereIn('callerNumber', $client_ids)->paginate(200);
        $missedcalls = Missedcall::with('client.sales')
            ->whereHas('client.sales', function ($query) {
                $query->where('agent_id', Auth::id());
            })
            ->orderBy('id', 'desc')
            ->paginate(100);

        return $missedcalls;
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
     * @param  \App\Models\Missedcall  $missedcall
     * @return \Illuminate\Http\Response
     */
    public function show(Missedcall $missedcall)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Missedcall  $missedcall
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Missedcall $missedcall)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Missedcall  $missedcall
     * @return \Illuminate\Http\Response
     */
    public function destroy(Missedcall $missedcall)
    {
        //
    }

    public function missed_update(Request $request)
    {
        $missed = Missedcall::where('callerNumber', $request->phone)->first();

        if ($missed) {
            $missed->delete();
        }
    }
}
