<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Spatie\Permission\Models\Permission;

class HomeController extends Controller
{

    protected $redirectTo = '/seller/login';

    public function logged_user()
    {
        $user = new User();
        return  $user->logged_user();
    }
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('seller.auth:seller');
    }

    /**
     * Show the Seller dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $tenant = Tenant::setEagerLoads([])->with(['subscriber', 'subscriptions' => function ($query) {
        //     $query->latest()->take(1);
        // }])->where('id', tenant('id'))->first();
        // // return view('seller.home');
        // // return Auth::guard('seller')->check();
        
        
        if (!Company::first()) {
            return redirect('account_setup');
        }
        $user = $this->logged_user();

        $permissions = [];
        foreach (Permission::where('guard_name', 'seller')->get() as $permission) {
            // return $permission->name;
            // return  $user->can($permission->name);
            if ($user->hasPermissionTo($permission->name)) {
                $permissions[$permission->name] = true;
            } else {
                $permissions[$permission->name] = false;
            }
        }

        $user = $user->toArray();

        $user['company'] = Company::first();

        // $user = $user->setAppends(['is_client', 'is_admin'])->toArray();
         $auth_user = array_prepend($user, $permissions, 'can');

        // $logo = $auth_user['company']['logo'];
        $tenant = tenant('');

        return view('welcome', compact(['auth_user', 'tenant']));
    }
}
