<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ClientController extends Controller
{
    public function logged_user()
    {
        $user = new User();
        return  $user->logged_user();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Client::orderBy('id', 'DESC')->paginate(500);
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
        $request->validate([
            'name' => 'required'
        ]);
        $client = new Client();
        $client->user_id = $this->logged_user()->id;
        $client->name = $request->name;
        $client->email = $request->email;
        $client->alt_phone = $request->alt_phone;
        $client->phone = $request->phone;
        $client->address = $request->address;
        $client->gender = $request->gender;
        $client->payment_type = $request->payment_type;
        $client->save();
        return $client;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Client::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $client = Client::find($id);
        $client->name = $request->name;
        $client->email = $request->email;
        $client->alt_phone = $request->alt_phone;
        $client->city = $request->city;
        $client->address = $request->address;
        $client->phone = $request->phone;
        $client->payment_type = $request->payment_type;
        $client->save();
        return $client;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Client::find($id)->delete();
    }

    public function client_search($search)
    {
        return Client::where('name', 'LIKE', "%{$search}%")
            ->orWhere('phone', 'LIKE', "%{$search}%")
            ->orWhere('email', 'LIKE', "%{$search}%")
            ->paginate(100);
    }


    public function client_orders($id)
    {
        return $client = Client::with(['sales'])->find($id);
    }

    

}
