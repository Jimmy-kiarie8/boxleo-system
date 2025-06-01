<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PoSubscription;
use App\Paypal\PaypalAgreement;
use App\Paypal\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaypalSubscriptionController extends Controller
{

    public function index()
    {
        $subscriptions = PoSubscription::all();

        return view('subscription', compact('subscriptions'));
        $data = PoSubscription::first();
        return PoSubscription::all();
        dd($data['paypal_data']->payment_definitions[0]->id);
    }
    public function pricing()
    {
        $subscriptions = PoSubscription::all();

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
        $plan_exists = PoSubscription::where('subscription', $request->subscription)->first();
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
        return $agreement->create($id);
    }

    public function executeAgreement($status)
    {
        if ($status == 'true') {
            $agreement = new PaypalAgreement;
            $agreement->execute(request('token'));
            return 'done';
        }
    }

    public function paypal()
    {
        $subscriptions = PoSubscription::all();

        return view('paypal', compact('subscriptions'));
    }
}
