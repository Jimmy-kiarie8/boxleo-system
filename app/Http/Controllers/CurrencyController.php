<?php

namespace App\Http\Controllers;

use App\Mail\subscription\SubscriptionMail;
use App\Models\Admin\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Currency::latest()->first();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $currency = new Currency;
        $currency->rate = $request->rate;
        $currency->currency_code = $request->currency_code;
        $currency->currency = $request->currency;
        $currency->save();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $currency = Currency::find($id);
        $currency->rate = $request->rate;
        $currency->currency_code = $request->currency_code;
        $currency->currency = $request->currency;
        $currency->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Currency::find($id)->delete();
    }


    public function rates()
    {
        Mail::send(new SubscriptionMail('test@test.com', 'Jane', 'pass', 'http://lux.local'));

        return ;
        // $currency = Currency::latest()->first();
        // $base_kes = $currency->rates->KES;
        // $base_eur = $currency->rates->EUR;

        // $base = $base_eur / $base_kes;

        // $currency_arr = [];

        // foreach($currency->rates as $key => $rate) {
        //     // return $key;
        //     $currency_arr[$key] = $rate * $base;
        // }

        // return $currency_arr;




        // return;
        try {
            $url = 'http://data.fixer.io/api/latest?access_key=c147bfc10e48851b4d79be14bebd1ebc';
            $client = new \GuzzleHttp\Client;
            // $client = new Client();
            // $token = 'Bearer A21AAIqXMqHnEsIB0YnluAPQ_1YWu6JKqSI_-eiXvPDatcHGfGzdYQtfkFgfefzxH6z2h9R18l0nh5JKpN7H2YQ3cHKHviCrg';
            $response = $client->request('GET', $url);

            $response = json_decode($response->getBody()->getContents());


            $base_kes = $response->rates->KES;
            $base_eur = $response->rates->EUR;

            $base = $base_eur / $base_kes;

            $currency_arr = [];

            foreach ($response->rates as $key => $rate) {
                // return $key;
                $currency_arr[$key] = $rate * $base;
            }

            // return $currency_arr;

            $currency = new Currency();
            $currency->rates = $currency_arr;
            $currency->base = $response->base;
            $currency->save();
            return ($currency_arr);
        } catch (\Exception $e) {
            //throw $th;
            dd($e);
            // Log::error($e);
            return $e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile();
        }
    }
}
