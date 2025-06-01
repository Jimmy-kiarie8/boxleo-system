<?php

namespace App\Http\Controllers\Subscription;

use App\Http\Controllers\Controller;
use App\Models\BillingAgreement;
use App\Models\Log as ModelsLog;
use App\Models\Plan;
use App\Models\PoSubscription;
use App\Models\Subscriber;
use App\Models\Admin\Subscription;
use App\Paypal\PaypalAgreement;
use App\Paypal\SubscriptionPlan;
use App\Subscription\AccountSubscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = Plan::all();
        // $subscriptions = PoSubscription::all();

        return view('subscription', compact('subscriptions'));
        $data = PoSubscription::first();
        return PoSubscription::all();
        dd($data['paypal_data']->payment_definitions[0]->id);
    }
    public function pricing()
    {
        $subscriptions = Plan::all();

        return view('pricing', compact('subscriptions'));
        // $data = PoSubscription::first();
        // return PoSubscription::all();
        // dd($data['paypal_data']->payment_definitions[0]->id);
    }

    /**
     *
     */
    public function createPlan(Request $request)
    {
        $data = $request->all();
        // dd($data);
        $data['plan'] = $request->subscription;
        $plan_exists = Plan::where('plan', $request->plan)->first();
        $plan = new SubscriptionPlan();
        if ($plan_exists) {
            // dd($plan_exists->subscription_id);

            $data['id'] = $plan_exists->subscription_id;
            $plan->update_plan($data);
        } else {
            // dd(2);
            $plan->create($data);
        }
    }

    /**
     * @return \PayPal\Api\PlanList
     */
    public  function listPlan()
    {
        // dd('ded');
        $plan = new SubscriptionPlan();
        return $plan->listPlan();
    }

    public function showPlan($id)
    {
        $plan = new SubscriptionPlan();
        return $plan->planDetails($id);
    }

    public function activatePlan($id)
    {
        $plan = new SubscriptionPlan();
        $plan->activate($id);
    }

    public function deactivatePlan($id)
    {
        $plan = new SubscriptionPlan();
        $plan->deactivate($id);
    }

    public function CreateAgreement($id)
    {
        // dd($id);
        $agreement = new PaypalAgreement;
        $subscriber = Subscriber::where('tenant_id', Auth::id())->first();
        if (!$subscriber) {
            $subscriber = new Subscriber;
        }
        // return $subscriber;
        $subscriber->upgrade_plan_id = $id;
        $subscriber->save();
        $subscriber->create_sub($id);

        return $agreement->create($id);
    }

    public function executeAgreement($status)
    {
        $this->upgrade();

        // Log::debug($status);
        if ($status == 'true') {
            $agreement = new PaypalAgreement;
            $agreement->execute(request('token'));
            // Log::debug($agreement);
            return 'done';
        }
    }

    public function paypal()
    {
        $subscriptions = PoSubscription::all();

        return view('paypal', compact('subscriptions'));
    }


    public function webhook(Request $request)
    {
        $data = [];
        Mail::send('emails.welcome', $data, function ($message) {
            $message->from('us@example.com', 'Laravel');
            $message->to('foo@example.com');
        });
        // Log::debug('**********************************');
        // Log::debug('**********************************');
        // Log::debug('**********************************');
        // Log::debug('**********************************');
        // Log::debug('**********************************');
        // Log::debug('**********************************');
        // Log::debug($request->all());
        // Log::debug('**********************************');
        // Log::debug('**********************************');
        // Log::debug('**********************************');
        // Log::debug('**********************************');
        // Log::debug('**********************************');
        // Log::debug('**********************************');

        $logs = new ModelsLog;
        $logs->logs = $request->all();
        $logs->save();

        // $data = ($request->all());


        $subscriber = new AccountSubscription();
        if ($request->event_type == 'BILLING.SUBSCRIPTION.RENEWED' || $request->event_type == 'BILLING.SUBSCRIPTION.CREATED') {
            $subscriber->subscribe($request->all());
        } elseif ($request->event_type == 'BILLING.SUBSCRIPTION.EXPIRED') {
            $subscriber->expired();
        } elseif ($request->event_type == 'PAYMENT.SALE.COMPLETED') {
            $subscriber->renew($request->all());
            // $subscriber->expired();
        } elseif ($request->event_type == 'BILLING.SUBSCRIPTION.CANCELLED') {
            $subscriber->cancel($request->all());
            // $subscriber->expired();
        }
        return;
    }
    public function webhook_index(Request $request)
    {
        return $model = ModelsLog::latest()->get('logs');
        $model = ModelsLog::latest()->first('logs');




        $data = (($model->logs));

        // return $data['resource']['subscriber']['email_address'];
        // dd($data);

        // $plan = Plan::where('plan_id', $data['resource']['plan_id'])->first();
        // $plan = Plan::first();

        // $email = $data['resource']['subscriber']['email_address'];

        $billing_agreement_id = (array_key_exists('billing_agreement_id', $data['resource'])) ? $data['resource']['billing_agreement_id'] : '2222222';


        $subscriber = Subscriber::where('agreement_id', $billing_agreement_id)->first();
        // $subscriber = Subscriber::first();
        // $subscriber->tenant_id = $data->tenant_id;
        $subscriber->status = true;
        $subscriber->platform = 'Paypal';
        // $subscriber->plan = $data->plan;
        // $subscriber->plan = $data['resource']['plan_id'];
        // $subscriber->agreement_id = $billing_agreement_id;
        // $subscriber->trial_ends = $data->trial_ends;
        // $subscriber->subscription_start = $data->subscription_start;
        $subscriber->subscription_expire = Carbon::parse($subscriber->subscription_expire)->addDays(30);
        $subscriber->subscription_adddays = 30;
        $subscriber->expired = false;
        $subscriber->save();


        $subscription = new Subscription();
        $subscription->subscriber_id = $subscriber->id;
        $subscription->billing_agreement_id = $billing_agreement_id;
        // $subscription->amount = $data['amount']['total'];
        // $subscription->trial_ends = $data->resource;
        $subscription->subscription_start = now();
        $subscription->subscription_expire = Carbon::today()->addDays(30);
        $subscription->subscription_adddays = 30;
        $subscription->expired = false;
        $subscription->save();

        return $subscription;
    }

    public function agreement_details($id)
    {
        $agreement = new PaypalAgreement;
        return $agreement->agreement_details($id);
    }

    public function upgrade()
    {
        // $agreement = new PaypalAgreement;
        // return $agreement->upgrade();
        $subscriber = Subscriber::where('tenant_id', Auth::id())->first('agreement_id');
        if (!$subscriber) {
            return;
        } else {
            $subscriber->plan_id = $subscriber->upgrade_plan_id;
            $subscriber->save();
        }
        $agreement = $subscriber->agreement_id;
        $old_subscription = new PaypalAgreement;
        // $agreement = 'I-3LP90C2H4915';
        $agreement_status =  $this->agreement_details($agreement)->state;
        if ($agreement_status == 'Active') {
            $old_subscription->suspend($agreement);
        }
        return;
    }


    public function account_expired()
    {
        // return tenant('id');
        $tenant = Crypt::encrypt(tenant('id'));

        return view('admin.account_expired', compact('tenant'));
    }
}
