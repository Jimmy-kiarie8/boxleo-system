<?php

namespace App\Http\Controllers;

use App\Events\UserOnline;
use App\Events\UserStatusEvent;
use App\Models\Company;
use App\Models\Plan;
use App\Models\Subscriber;
use App\Models\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Redirect;

class HomeController extends Controller
{
    public function logged_user()
    {
        $user = new User;
        return  $user->logged_user();
    }
    public function index()
    {
        if (Auth::guard('seller')->check()) {
            return redirect()->route('seller.dashboard');
        }
        if (!Company::first()) {
            return redirect('account_setup');
        }

        if (Auth::check()) {
            if (Auth::user()->agent_sip) {
                broadcast(new UserOnline(Auth::id()));
            }
        }




    //    $tenant = Tenant::setEagerLoads([])->with(['subscriber', 'subscriptions' => function ($query) {
    //         $query->latest()->take(1);
    //     }])->where('id', tenant('id'))->first();

        // $subscribers = $tenant->subscriber;

        // if ($subscribers->) {
        //     # code...
        // }

        // $tenant->days_remaining = Carbon::parse($tenant->subscriber->subscription_expire)->diffInDays(Carbon::now());
        // return $tenant;
        //   return $plan = $tenant->subscriber->tenant_plan->portals;
        $user = $this->logged_user();

        $permissions = [];
        foreach (Permission::where('guard_name', 'web')->get() as $permission) {
            // return $permission->name;
            // return $user->hasPermissionTo('Custom');
            // return  $user->can($permission->name);
            if ($user->hasPermissionTo($permission->name)) {
                $permissions[$permission->name] = true;
            } else {
                $permissions[$permission->name] = false;
            }
        }

        // $user = $user->setAppends(['is_client', 'is_admin'])->toArray();
        $auth_user = array_prepend($user->toArray(), $permissions, 'can');

        // $tenant = Tenant::setEagerLoads([])->with(['subscriber'])->where('id', tenant('id'))->first();
        // $tenant->enc_domain = Crypt::encrypt($tenant->id);
        //  return $tenant;


        // if (Auth::check() || Auth::guard('seller')->check() || Auth::guard('admin')->check()) {
        // $auth_user = $this->logged_user();


        return view('welcome', compact('auth_user'));
        // } else {
        //     return redirect('/login');
        // }
    }


    public function domain(Request $request)
    {
        $url =  'http://' . $request->domain . env('CENTRAL_DOMAIN', '.logixsaas.com');
        return Redirect::to($url);
    }

}
