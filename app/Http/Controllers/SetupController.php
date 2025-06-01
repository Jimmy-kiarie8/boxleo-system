<?php

namespace App\Http\Controllers;

use App\Mail\subscription\SubscriptionMail;
use App\Models\Company;
use App\Models\Ou;
use App\Models\Setup;
use App\Models\User;
use App\Models\Zone;
use App\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class SetupController extends Controller
{
    public function index()
    {
        return Setup::first();
    }

    public function encrypt_domain($domain)
    {
        return Crypt::encrypt($domain);
    }
    public function account_setup()
    {
        // if(Company::first()) {
        //     return redirect('account_setup');
        // }
        $subscriber = tenant()->subscriber;
        if ($subscriber->setup) {
            return redirect('login');
        }

        $company = Company::first();
        if (!$company) {
            $company = new Company;
            $company->name = $subscriber->company;
            $company->email = $subscriber->email;
            $company->phone = $subscriber->phone;
            $company->ou = $subscriber->ou;
            $company->region = $subscriber->region;
            // $company->tenant = '';
            $company->website = '';
            $company->address = '';
            $company->about = '';
            $company->terms = '';
            $company->notes = '';
            $company->save();
        }

        if (Auth::check()) {
            $auth_user = Auth::user();
        } else {
            $auth_user = null;
        }
        return view('admin.account', compact('auth_user'));
    }

    public function company_details()
    {
        // if(Company::first()) {
        //     return redirect('account_setup');
        // }

        $company = Company::first();
        $subscriber = tenant()->subscriber;
        if (!$company) {
            $company = new Company;
            $company->name = $subscriber->company;
            $company->email = $subscriber->email;
            $company->phone = $subscriber->phone;
            $company->ou = $subscriber->ou;
            $company->region = $subscriber->region;
            // $company->tenant = '';
            $company->website = '';
            $company->address = '';
            $company->about = '';
            $company->terms = '';
            $company->notes = '';
            $company->save();
        }
        return $company;

        if (Auth::check()) {
            $auth_user = Auth::user();
        } else {
            $auth_user = null;
        }
        return view('admin.account', compact('auth_user'));
    }

    public function tenant_sub()
    {
        return tenant('subscription');
    }

    public function account(Request $request)
    {
        // return $request->all();

        // return tenant();
        $data = $request->validate([
            'name' => 'required'
        ]);

        // return $data;

        // $ou = Ou::updateOrCreate(
        //     [
        //         'ou' => $request->ou
        //     ],
        //     [
        //         'currency_code' => $request->currency_code,
        //         'ou_code' => $request->ou_code
        //     ]
        // );
        if (Company::first()) {
            Company::first()->update($request->all());
        } else {
            Company::create($request->all());
        }
        $setup = new Setup();
        $setup->updateSetup('company');

        return Company::first();
        // return $request->all();
        // $password = $this->generateRandomString();
        // $password_hash = Hash::make($password);
        // $user->password = $password_hash;
    }

    public function zones(Request $request)
    {
        // return $request->all();
        $zone_details = $request->zone_details;
        $ou = $zone_details['ou'];
        $zone_index = $zone_details['zone_index'];
        foreach ($request->zones as $key => $zone) {
            $zone['ou_id'] = $ou;
            // Zone::create($zone);
            Zone::firstOrCreate([
                'code' => $zone['code'],
                'ou_id' => $zone['ou_id']
            ], [
                'name' => $zone['name'],
            ]);
            // dd($key, $zone_index);
            if ($key == $zone_index) {
                // return $zone;
                // return $zone['code'];
                $zone_fun = new Zone;
                $zone_fun->default_zone($zone['code'], $ou);
            }
        }


        $user = new User();
        $user->user_setup($ou);

        $setup = new Setup();
        $setup->updateSetup('zones');

        return;
    }

    public function tenant_update()
    {
        $tenant = Tenant::where('id', tenant('id'))->first()->subscriber;
        $tenant->setup = true;
        $tenant->save();
        return $tenant;
    }
}
