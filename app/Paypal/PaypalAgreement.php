<?php

namespace App\Paypal;

use App\Models\BillingAgreement;
use App\Models\Log;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Redirect;
use PayPal\Api\Agreement;
use PayPal\Api\Payer;
use PayPal\Api\Plan;
use PayPal\Api\ShippingAddress;
use PayPal\Api\AgreementStateDescriptor;

class PaypalAgreement extends Paypal
{
    public function create($id)
    {
        return redirect($this->agreement($id));
    }

    /**
     * @param $id
     * @return string
     */
    protected function agreement($id): string
    {
        try {
            // dd($id);
            $agreement = new Agreement();
            $agreement->setName('Base Agreement')
                ->setDescription('Basic Agreement')
                ->setStartDate(Carbon::now()->addDay());

            $agreement->setPlan($this->plan($id));

            $agreement->setPayer($this->payer());

            $agreement->setShippingAddress($this->shippingAddress());

            $agreement = $agreement->create($this->apiContext);

            // return redirect($agreement->getApprovalLink());
            // return Redirect::to($agreement->getApprovalLink());
            // redirect()->away($agreement->getApprovalLink());
            // return redirect()->away('www.google.com');

            return ($agreement->getApprovalLink());
        } catch (\Exception $e) {
            dd($e);
            // \Log::error($e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile());
            // return $e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile();
        }
    }

    /**
     * @param $id
     * @return Plan
     */
    protected function plan($id): Plan
    {
        $plan = new Plan();
        $plan->setId($id);
        return $plan;
    }

    /**
     * @return Payer
     */
    protected function payer(): Payer
    {
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');
        return $payer;
    }

    /**
     * @return ShippingAddress
     */
    protected function shippingAddress(): ShippingAddress
    {
        $shippingAddress = new ShippingAddress();
        $shippingAddress->setLine1('111 First Street')
            ->setCity('Saratoga')
            ->setState('CA')
            ->setPostalCode('95070')
            ->setOuCode('US');
        return $shippingAddress;
    }

    public function execute($token)
    {
        $agreement = new Agreement();
        $agreement->execute($token, $this->apiContext);

        $logs = json_decode($agreement);
        // dd($logs->plan->payment_definitions[0]->amount->value);
        $billing = new BillingAgreement();
        $billing->billing($logs);

        dd($agreement);
    }

    public function agreement_details($agreement)
    {
        // $agreement = "I-XM06WLA1VV4E";
        // $agreement = "I-3LP90C2H4915";

        try {
            return $agreement = Agreement::get($agreement, $this->apiContext);
        } catch (\Exception $e) {
            dd($e);
            // \Log::error($e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile());
            // return $e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile();
        }

        // ResultPrinter::printResult("Retrieved an Agreement", "Agreement", $agreement->getId(), $createdAgreement->getId(), $agreement);

        return $agreement;
    }

    public function suspend($id)
    {
        //Create an Agreement State Descriptor, explaining the reason to suspend.
        $agreementStateDescriptor = new AgreementStateDescriptor();
        $agreementStateDescriptor->setNote("Suspending the agreement");

        /** @var Agreement $createdAgreement */
        $createdAgreement = $this->agreement_details($id);; // Replace this with your fetched agreement object

        try {
            $createdAgreement->suspend($agreementStateDescriptor, $this->apiContext);
            $agreement = Agreement::get($id, $this->apiContext);

            return($agreement);
        } catch (\Exception $e) {
            dd($e);
        }
        // return $agreement;
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
                'form_data' => [
                    'grant_type' => 'client_credentials'
                ]
            ]);

            return $response = $response->getBody()->getContents();
            dd($response);
        } catch (\Exception $e) {
            dd($e);
        }
    }
}
