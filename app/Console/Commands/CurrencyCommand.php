<?php

namespace App\Console\Commands;

use App\Models\Admin\Currency;
use Illuminate\Console\Command;

class CurrencyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currency:rates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get currency convertion rates';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $url = 'http://data.fixer.io/api/latest?access_key=' . env('CURRENCY_KEY');
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

            $currency = Currency::latest()->first();
            if (!$currency) {
                $currency = new Currency;
            }
            $currency->rates = $currency_arr;
            $currency->base = $response->base;
            $currency->save();
            // return ($currency_arr);
            $this->info('Currency rates updated');
        } catch (\Exception $e) {
            //throw $th;
            // dd($e);
            $this->error('Something went wrong!');

            // Log::error($e);
            return $e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile();
        }
    }
}
