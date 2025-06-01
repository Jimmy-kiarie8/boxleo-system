<?php

namespace App\Http\Controllers\Rider;

use App\Http\Controllers\Controller;
use App\Mail\RiderMail;
use App\Models\RiderSale;
use App\Models\Sale;
use App\Models\Sms;
use App\Rider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class RiderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if (Auth::check()) {
            $cacheKey = 'riders_' . Auth::user()->ou_id;
            return Cache::remember($cacheKey, now()->addHours(10), function () {
                return Rider::all();
            });
        }
        return Rider::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        return Rider::create([
            'name' => $data['name'],
            'address' => $data['address'],
            'zone_id' => $data['zone_id'],
            'phone' => $data['phone'],
            'ou_id' => Auth::user()->ou_id,
            'rate_per_delivery' => $data['rate_per_delivery'],
            'email' => $data['email'],
            'password' => bcrypt('password'),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Rider  $rider
     * @return \Illuminate\Http\Response
     */
    public function show(Rider $rider)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Rider  $rider
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $data = $request->all();
        return Rider::find($id)->update([
            'name' => $data['name'],
            'address' => $data['address'],
            'zone_id' => $data['zone_id'],
            'rate_per_delivery' => $data['rate_per_delivery'],
            'phone' => $data['phone'],
            'email' => $data['email']
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Rider  $rider
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Rider::find($id)->delete();
        $rider = Rider::find($id);
        $rider->active = false;
        $rider->save();
    }

    public function rider_update(Request $request, $id)
    {
        // return $request->all();
        $product_ = [];
        $product_arr = [];
        foreach ($request->products as  $product) {
            $product_ = [];
            if ($product) {

                $product_['product_id'] = $product['id'];
                $product_['quantity_sent'] = $product['pivot']['quantity_sent'];
                $product_['quantity_returned'] = $product['pivot']['quantity_returned'];
                $product_['quantity_delivered'] = $product['pivot']['quantity_delivered'];
                // $product_['quantity_received'] = 0;
            }

            $product_arr[] = $product_;
        }
        // return $product_arr;
        $branch = RiderSale::updateOrCreate(
            [
                'sale_id' => $request->id
            ],
            [
                'rider_id' => $request->rider_id,
                'delivery_status' => 'Pending',
                'receive_status' => 'pending',
                // 'sent' => '$request->sent',
                // 'received' => $request->received,
                'comment' => $request->comment,
                'products' => $product_arr
            ]
        );
        // $sale = Sale::find($id);
        // $sale->charge = 600;
        // $sale->save();
    }


    public function rider_password(Request $request, $id)
    {
        $this->Validate($request, [
            'password' => 'required|string|min:6',
        ]);
        $rider = Rider::find($id);
        $rider->password = Hash::make($request->password);
        $rider->save();
        return $rider;
    }

    public function mobile_account(Request $request, $id)
    {
         $rider = Rider::find($id);
        $password = 'S5E6mBRp!';
        // $password = Str::random(8);
        $rider->password = Hash::make($password);
        $rider->save();
        // return;

        $app_url = 'https://play.google.com/store/apps/details?id=com.boxleo.app';


        // $rider = Rider::find($id);
        $message = "Hello " . $rider->name . ", 
you've been added to our delivery system! To access your account, please download our delivery app from 
Play Store: " . $app_url . ". Once downloaded, use the following login details to sign in: 
Domain: " . tenant('id') . " 
Username: " . $rider->email . " 
Password: " . $password;
        // $sms = new Sms();

        // $phone = [$rider->phone];
        // $phone = ['+254743895505'];

        // $sms->sms($phone, $message);

        Mail::to('support@boxleocourier.com')->send(new RiderMail($rider, $password));
    }
}
