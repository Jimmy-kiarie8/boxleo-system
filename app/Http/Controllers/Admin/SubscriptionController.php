<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\AccountJob;
use App\Mail\subscription\PaymentMail;
use App\Models\Plan;
use App\Models\Subscriber;
use App\Subscription\AccountSubscription;
use App\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd('dd');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // CreatingAccountEvent::dispatch('test 123...', 1);
        // return $request->id;
        // Mail::to('john@johndoe.com')->send(new TestMail);

        // broadcast(new CreatingAccountEvent('Creating Database'));

        // return $request->all();

        // $tenant = Tenant::where('id', $request->domain)->exists();

        $request->validate([
            'id' => 'required|unique:tenants',
            'email' => 'required|unique:subscribers',
            'name' => 'required',
            'company' => 'required',
            // 'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // return 'ddd';

        // if ($tenant) {
        //     abort('Tenant Account Exists');
        // }

        AccountJob::dispatch($request->all());
        // $this->dispatch(new AccountJob($request->all()));

        return;


        // $tenant = Tenant::where('id','bar')->first();
        $tenant = Tenant::create(['id' => $request->id]);

        $tenant->domains()->create(['domain' => $request->id . env('CENTRAL_DOMAIN')]);
        // $tenant->domains()->create(['domain' => $this->account['id'] . config('tenancy.central_domains')]);

        // $subscription = new Subscription;

        // $subscription->company = $request->company;
        // $subscription->name = $request->name;
        // $subscription->email = $request->email;
        // $subscription->domain = $request->domain;
        // $subscription->password = $request->password;
        // $subscription->save();

        return 'Account created';
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Admin\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function renew(Request $request)
    {
        $anual_sub = ($request->anual) ? true : false;
        // return $request->all();
        $addons = [];

        foreach ($request->addons as $addon) {
            // return $addon['count'];
            if ($addon['count'] > 0 && $addon['count']) {
                // dd($addon);
                $addons[] = $addon;
            }
        }
        // return $addons;
        $subscription = new AccountSubscription;
        $subscription->subscribe($request->data, $request->plan, $request->domain, $addons, $anual_sub);
        // return $subscription;

        $subscriber = Subscriber::where('tenant_id', $request->domain)->first();
        // $subscriber = Subscriber::first();



        $url = 'http://' . $request->domain . env('CENTRAL_DOMAIN');

        Mail::to($subscriber->email)->send(new PaymentMail($url, $subscriber));

        return $url;

        // return Redirect::to($url);

        // $subscription->subscription_expire = Carbon::parse($subscription->subscription_expire)->addDays(30);
        // $subscription->subscription_adddays = 30;
        // $subscription->expired = false;
        // $subscription->amount = false;
    }

    public function sms_bundles()
    {
        
    }

    public function validate_account(Request $request, $step)
    {
        // return $request->plan;
        // return $request->all();
        if ($step == 0) {
            $request->validate([
                // 'id' => 'required|unique:tenants',
                'email' => 'required|unique:subscribers',
                'name' => 'required',
                'company' => 'required',
                // 'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);
        }
    }


    public function upgrade(Request $request)
    {
        $domain = $request->domain;
        $domain = Crypt::decrypt($domain);

        $plans = Plan::where('status', true)->get();

        $tenant = Tenant::where('id', $domain)->first();

        if (!$tenant) {
            abort(403, 'Domain ' . $domain .  ' not found');
        }
        // $subscriber = Subscriber::where('tenant_id', $domain)->first();

        $plans->transform(function ($plan, $key) {
            // dd($key);
            if ($key == 0) {
                $plan->color = '#fa7070';
            } elseif ($key == 1) {
                $plan->color = '#8070fa';
            } elseif ($key == 2) {
                $plan->color = '#22cd1a';
            }
            return $plan;
        });

        return view('admin.upgrade.index', compact('plans', 'domain', 'tenant'));
    }

    public function check_account_expired()
    {
    }

    public function thankyou($domain)
    {

        $plans = Plan::where('status', true)->get();

        $tenant = Tenant::where('id', $domain)->first();
        return view('admin.upgrade.thankyou', compact('tenant'));
    }
    public function thankyou_payment($domain)
    {

        $plans = Plan::where('status', true)->get();

        $tenant = Tenant::where('id', $domain)->first();
        return view('admin.upgrade.thankyou-payment', compact('tenant'));
    }
}
