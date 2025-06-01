<?php

namespace App\Mobile;

use App\Http\Controllers\StatusController;
use App\Models\Optimo;
use App\Models\Sale;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class Status
{

    public function update_orders()
    {
        $opt = Optimo::latest()->first();
        // $after_tag = '' ;
        $after_tag = ($opt) ? $opt->tag : '';
        $url = 'https://api.optimoroute.com/v1/get_events?key=' . env('OPTIMO_API_KEY') . '&after_tag=';
        try {
            $client = new Client();
            $response = $client->request('GET', $url);

            $response = $response->getBody()->getContents();
            $data = json_decode($response, true);

            if (count($data) > 0) {

                $tag = $data['tag'];

                $events = $data['events'];

                // return $data['events'];
                $status_update = new StatusController;

                foreach ($events as $event) {
                    // return $event['event'];
                    if ($event['event'] == 'success' || $event['event'] == 'failed') {
                        $order_no = $event['orderNo'];
                        $order = Sale::where('order_no', $order_no);

                        if ($event['event'] == 'success') {
                            $order = $order->where('delivery_status', '!=', 'Delivered');
                        } elseif ($event['event'] == 'failed') {
                            $order = $order->where('delivery_status', '!=', 'Returned');
                        }
                        $order = $order->first();
                        if ($order) {
                            if ($event['event'] == 'success') {
                                $order->delivery_status = 'Delivered';
                            } elseif ($event['event'] == 'failed') {
                                $order->delivery_status = 'Returned';
                            }
                            $status_update->status_update(new Request($order->toArray()), $order->id);
                        }
                    }
                }

                $optimo = new Optimo();
                $optimo->tag = $tag;
                $optimo->save();
            }

            // return ($data);
        } catch (\Exception $e) {
            // Log::debug($e);
        }
    }
}
