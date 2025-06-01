<?php
namespace App\Paypal;

use App\Models\Token;
use GuzzleHttp\Client;
use PayPal\Api\Amount;
use PayPal\Api\Details;

class Paypal
{
    protected $apiContext;

    public function __construct()
    {
        $this->apiContext = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                config('services.paypal.id'), // client id
                config('services.paypal.secret')
            )
        );
    }

    /**
     * @return Details
     */
    protected function details(): Details
    {
        $details = new Details();
        $details->setShipping(1.2)
            ->setTax(1.3)
            ->setSubtotal(17.50);
        return $details;
    }

    /**
     * @return Amount
     */
    protected function amount(): Amount
    {
        $amount = new Amount();
        $amount->setCurrency('USD');
        $amount->setTotal(20);
        $amount->setDetails($this->details());
        return $amount;
    }
 
    public function token()
    {
        try {
            $client = new Client();
            $token = 'Basic QWRyZFhBQjdPTHBaSlhEQm5nZ2c0NkdqWUxCV2Q5elA3Uy1QWFlYYlVFR1JaVG1EUE9OeVpIaDd4TndWdHVrczJTUEV2bGt2c3Q1OUQwM2Y6RUZBT0VkSlJ1T0lyRTNIY0xLMl9fMU1CRkN1N19XWS1OZ3NCeTdFVTd0N2FlZERXNFNWcWp6TFB5ZlBKWE56VV9nUjdGVUtONnpPOXRiX3U=';
            $url = 'https://api-m.sandbox.paypal.com/v1/oauth2/token';
            $response = $client->request('POST', $url, [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Authorization' => $token
                ],
                'form_params' => [
                    'grant_type' => 'client_credentials'
                ]
            ]);

            return $response = $response->getBody()->getContents();
            $token = new Token();
            $token->token_type = $response['token_type'];
            $token->access_token = $response['access_token'];
            $token->expires_in = $response['expires_in'];
            $token->platform = 'Paypal';
            $token->save();

            dd($response);
        } catch (\Exception $e) {
            dd($e);
        }
    }
}
