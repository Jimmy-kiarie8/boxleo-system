<?php

namespace App\Paypal;

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class CreatePlan extends Paypal
{
    public $provider;

    public function __construct()
    {
        $this->provider = new PayPalClient;
    }

    public function access_token()
    {
        return $this->provider->getAccessToken();
    }

    public function execute()
    {

        $apiContext = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                'AdrdXAB7OLpZJXDBnggg46GjYLBWd9zP7S-PXYXbUEGRZTmDPONyZHh7xNwVtuks2SPEvlkvst59D03f',     // ClientID
                'EFAOEdJRuOIrE3HcLK2__1MBFCu7_WY-NgsBy7EU7t7aedDW4SVqjzLPyfPJXNzU_gR7FUKN6zO9tb_u'      // ClientSecret
            )
        );
        $paymentId = request('paymentId');
        $payment = Payment::get($paymentId, $apiContext);

        $execution = new PaymentExecution();
        $execution->setPayerId($_GET['PayerID']);

        $transaction = new Transaction();
        $amount = new Amount();
        $details = new Details();


        $details->setShipping(2.2)
            ->setTax(1.3)
            ->setSubtotal(17.50);

        $amount->setCurrency('USD');
        $amount->setTotal(21);
        $amount->setDetails($details);
        $transaction->setAmount($amount);

        $execution->addTransaction($transaction);

        return $payment;
    }

    public function create_plan()
    {

        $data = json_decode('{
            "product_id": "PROD-XXCD1234QWER65782",
            "name": "Startup Plan",
            "description": "Startup basic plan",
            "status": "ACTIVE",
            "billing_cycles": [
                {
                    "frequency": {
                        "interval_unit": "MONTH",
                        "interval_count": 1
                    },
                    "tenure_type": "TRIAL",
                    "sequence": 1,
                    "total_cycles": 1,
                    "pricing_scheme": {
                        "fixed_price": {
                            "value": "0",
                            "currency_code": "USD"
                        }
                    }
                },
                {
                    "frequency": {
                        "interval_unit": "MONTH",
                        "interval_count": 1
                    },
                    "tenure_type": "REGULAR",
                    "sequence": 2,
                    "total_cycles": 999,
                    "pricing_scheme": {
                        "fixed_price": {
                            "value": "2",
                            "currency_code": "USD"
                        }
                    }
                }
            ],
            "payment_preferences": {
                "auto_bill_outstanding": true,
                "setup_fee": {
                    "value": "2",
                    "currency_code": "USD"
                },
                "setup_fee_failure_action": "CONTINUE",
                "payment_failure_threshold": 3
            },
            "taxes": {
                "percentage": "1",
                "inclusive": false
            }
        }', true);
        $request_id = 'create-plan-' . time();

        return $plan = $this->provider->createPlan($data, $request_id);
    }

    public function update_plan()
    {

        $data = json_decode('[
                    {
                    "op": "replace",
                    "path": "/payment_preferences/payment_failure_threshold",
                    "value": 7
                    }
                ]', true);

        $plan_id = 'P-7GL4271244454362WXNWU5NQ';

        return $plan = $this->provider->updatePlan($plan_id, $data);
    }

    public function update_plan_price()
    {
        $pricing = json_decode('{
        "pricing_schemes": [
          {
            "billing_cycle_sequence": 2,
            "pricing_scheme": {
              "fixed_price": {
                "value": "5",
                "currency_code": "USD"
              }
            }
          }
        ]
      }', true);

        $plan_id = 'P-7GL4271244454362WXNWU5NQ';

        return $plan = $this->provider->updatePlanPricing($plan_id, $pricing);
    }

    public function list_plans()
    {
        $fields = ['id', 'product_id', 'name', 'description'];
        return $plans = $this->provider->listPlans(1, 30, true, $fields);
    }

    public function activate_plans()
    {
        $plan_id = 'P-7GL4271244454362WXNWU5NQ';

        return $plan = $this->provider->activatePlan($plan_id);
    }
    public function deactivate_plans()
    {
        $plan_id = 'P-7GL4271244454362WXNWU5NQ';

        return $plan = $this->provider->deactivatePlan($plan_id);
    }

    public function invoice()
    {
        return $invoice_no = $this->provider->generateInvoiceNumber();
    }
    public function invoice_list()
    {
        return $invoice_no = $this->provider->generateInvoiceNumber();
    }
    public function invoice_delete()
    {
        $invoice_no = 'INV2-Z56S-5LLA-Q52L-CPZ5';
        return $status = $this->provider->deleteInvoice($invoice_no);
    }
    public function invoice_details()
    {
        $invoice_no = 'INV2-Z56S-5LLA-Q52L-CPZ5';

        return $invoice = $this->provider->showInvoiceDetails($invoice_no);
    }

    public function invoice_qrcode()
    {
        $invoice_no = 'INV2-Z56S-5LLA-Q52L-CPZ5';
        return $status = $this->provider->generateQRCodeInvoice($invoice_no);
    }
    public function invoice_payment()
    {
        $payment_method = 'BANK_TRANSFER';
        
        $payment_date = '2018-05-21';
        
        $amount = 10;
        
        $invoice_no = 'INV2-Z56S-5LLA-Q52L-CPZ5';
        
        return $status = $this->provider->registerPaymentInvoice($invoice_no, $payment_date, $payment_method, $amount);
    }


    public function subscribe()
    {
        $data = json_decode('{
            "plan_id": "P-5ML4271244454362WXNWU5NQ",
            "start_time": "2022-07-21T00:00:00Z",
            "quantity": "20",
            "shipping_amount": {
              "currency_code": "USD",
              "value": "10.00"
            },
            "subscriber": {
              "name": {
                "given_name": "John",
                "surname": "Doe"
              },
              "email_address": "customer@example.com",
              "shipping_address": {
                "name": {
                  "full_name": "John Doe"
                },
                "address": {
                  "address_line_1": "2211 N First Street",
                  "address_line_2": "Building 17",
                  "admin_area_2": "San Jose",
                  "admin_area_1": "CA",
                  "postal_code": "95131",
                  "country_code": "US"
                }
              }
            },
            "application_context": {
              "brand_name": "walmart",
              "locale": "en-US",
              "shipping_preference": "SET_PROVIDED_ADDRESS",
              "user_action": "SUBSCRIBE_NOW",
              "payment_method": {
                "payer_selected": "PAYPAL",
                "payee_preferred": "IMMEDIATE_PAYMENT_REQUIRED"
              },
              "return_url": '. env('APP_URL') ."/returnUrl" . ',
              "cancel_url": '. env('APP_URL') ."/cancelUrl" . ',
            }
          }', true);
          
        return $subscription = $this->provider->createSubscription($data);
    }

    public function update_subscription()
    {
        $data = json_decode('[
            {
              "op": "replace",
              "path": "/billing_info/outstanding_balance",
              "value": {
                "currency_code": "USD",
                "value": "50.00"
              }
            }
          ]', true);
          
          return $response = $this->provider->updateSubscription('I-BW452GLLEP1G', $data);
    }

    public function show_subscription()
    {
        return $subscription = $this->provider->showSubscriptionDetails('I-BW452GLLEP1G');
    }
    public function activate_subscription()
    {
        return $subscription = $this->provider->activateSubscription('I-BW452GLLEP1G', 'Reactivating the subscription');
    }
    public function cancel_subscription()
    {
        return $subscription = $this->provider->cancelSubscription('I-BW452GLLEP1G', 'Not satisfied with the service');
    }
    public function suspend_subscription()
    {
        return $subscription = $this->provider->suspendSubscription('I-BW452GLLEP1G', 'Item out of stock');
    }
    public function authorized_subscription()
    {
        return $subscription = $this->provider->captureSubscriptionPayment('I-BW452GLLEP1G', 'Charging as the balance reached the limit', 100);
    }
    public function list_subscription()
    {
        return $subscription = $this->provider->listSubscriptionTransactions('I-BW452GLLEP1G', '2018-01-21T07:50:20.940Z', '2018-08-22T07:50:20.940Z');
           
    }
}
