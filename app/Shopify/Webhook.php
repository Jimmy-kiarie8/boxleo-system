<?php

namespace App\Shopify;

use App\Models\Shopify;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Webhook
{

  public function webhook($id)
  {
    $shop = Shopify::find($id);
    $token = $shop->shopify_secret;
    $vendor_id = $shop->vendor_id;
    $url = 'https://' .  $shop->shopify_url . '/admin/api/2023-07/webhooks.json';
    $address = env('APP_URL') . '/api/shopify/webhook/' . $vendor_id;

    try {
      $response = Http::withHeaders([
        'Accept' => 'application/json',
        'X-Shopify-Access-Token' => $token,
        'Content-Type' => 'application/json',
      ])
        ->post($url, [
          'webhook' => [
            'address' => $address,
            'topic' => 'orders/create',
            'format' => 'json',
          ]
        ]);


      // Check if the response is successful (status code 200)
      if ($response->successful()) {
        // Log::debug('Success');
        $webhook = $response->json();

        // Ensure the webhook data contains the ID
        if (isset($webhook['webhook']['id'])) {
          // Log::debug($webhook['webhook']['id']);
          $shopify = Shopify::find($id);
          $shopify->webhook_id = $webhook['webhook']['id'];
          $shopify->save();

          return $webhook;
        } else {
          // Handle the case where the webhook ID is not present in the response
          // Log::error('Webhook ID not found in the response.');
        }
        return $webhook;

      } else {
        // Handle the case where the request was not successful
        // Log::error('Failed to create a Shopify webhook: ' . $response->status());
      }
      // $webhook = $response->json();
      // Log::debug($webhook);


      // $shopify = Shopify::find($id);
      // $shopify->webhook_id = $webhook['webhook']['id'];
      // $shopify->save();

      // return $webhook;
    } catch (\Throwable $e) {
      //throw $th;
      // Log::debug($e);
    }
    // You can access the response using $response->body() or $response->json()

  }
  public function webhook_curl($id)
  {
    // return $request->all();
    $shop = Shopify::find($id);
    $vendor_id = $shop->vendor_id;
    $url = 'https://' .  $shop->shopify_url . '/admin/api/2023-07/webhooks.json';

    $address = env('APP_URL') . '/api/shopify/webhook/' . $vendor_id;
    $token = $shop->shopify_secret;

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => '{
          "webhook": {
            "topic": "orders/create",
            "address": " ' . $address .  ' ",
            "format": "json"
          }
        }
        ',
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',
        'X-Shopify-Access-Token: ' .  $token,
      ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    echo $response;

    $webhook = json_decode($response);

    $shop->webhook_id = $webhook->webhook->id;
    $shop->save();

    return $webhook;
  }


  public function webhook_deactivate($id)
  {
    // return $request->all();
    $shop = Shopify::find($id);

    $webhook_id = $shop->webhook_id;
    $url = 'https://' .  $shop->shopify_url . '/admin/api/2023-07/webhooks/' . $webhook_id . ' .json';

    // $address = 'https://' . tenant('id') . env('CENTRAL_DOMAIN', '.logixsaas.com') . '/api/shopify/webhook/' . $vendor_id;
    $token = $shop->shopify_secret;

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'DELETE',
      CURLOPT_POSTFIELDS => '{
          "webhook": {
            "topic": "orders/create",
            "address": " ' . ' $address ' .  ' ",
            "format": "json"
          }
        }
        ',
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',
        'X-Shopify-Access-Token: ' .  $token,
      ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    echo $response;
  }


  public function get_webhooks($id)
  {
    // return $request->all();
    $shop = Shopify::find($id);

    $url = 'https://' .  $shop->shopify_url . '/admin/api/2023-07/webhooks.json';

    // $address = 'https://' . tenant('id') . env('CENTRAL_DOMAIN', '.logixsaas.com') . '/api/shopify/webhook/' . $vendor_id;
    $token = $shop->shopify_secret;

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'DELETE',
      CURLOPT_POSTFIELDS => '{
          "webhook": {
            "topic": "orders/create",
            "address": " ' . ' $address ' .  ' ",
            "format": "json"
          }
        }
        ',
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',
        'X-Shopify-Access-Token: ' .  $token,
      ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    echo $response;
  }
}
