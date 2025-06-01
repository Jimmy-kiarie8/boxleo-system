<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Mail\PortalMail;
use App\Models\Ou;
use App\Models\Product;
use App\Models\SellerService;
use App\Models\Service;
use App\Models\Setup;
use App\Models\Storedetails;
use App\Seller;
use App\Models\User;
use App\Models\VendorOu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;


class SellerController extends Controller
{

    public function logged_user()
    {
        $user = new User();
        return  $user->logged_user();
    }
    public function index()
    {

        if (Auth::guard('seller')->check()) {
            return ;
        }
        $role = Auth::user()->getRoleNames()[0];
        $cacheKey = 'sellers:' . Auth::user()->ou_id . md5($role);
        // $cacheExists = Cache::has($cacheKey);
        // Log::info("Merchant Cache key '{$cacheKey}' exists: " . ($cacheExists ? 'Yes' : 'No'));

        return Cache::remember($cacheKey, now()->addHours(2), function () use ($cacheKey) {
            // Log::info("Merchant Cache miss for key '{$cacheKey}'. Fetching from database.");

            if (Auth::guard('seller')->check()) {
                return [];
            }
            $cacheKey = 'sellers_' . Auth::user()->ou_id;

            $sellers =  Cache::remember($cacheKey, now()->addHours(10), function () {
                $sellers = Seller::paginate(500);

                return $sellers;
            });


            $sellers->transform(function ($seller) {
                if (Auth::user() && !Auth::user()->hasRole('Super admin')) {
                    $seller->phone = '-';
                    $seller->email = '-';
                    $seller->address = '-';
                }

                return $seller;
            });
            return $sellers;
        });
    }

    public function show($id)
    {
        $cacheKey = 'seller_' . $id;

        // return Cache::forget($cacheKey);

        return Cache::remember($cacheKey, now()->addHours(10), function () use ($id) {
            return Seller::with(['services' => function ($query) {
                $query->select('services.id', 'services.name', 'services.charges', 'services.ou_id')->setEagerLoads([]);
            }])->find($id);
        });
    }

    public function store(Request $request)
    {

        // $this->authorize('create', Seller::class);
        // $seller_id = 1;

        $ous = $request->ous;
        $exists = false;
        foreach ($ous as $ou) {
            if (array_key_exists('checked', $ou)) {
                if ($ou['checked']) {
                    $exists = true;
                }
            }
        }

        if (!$exists) {
            abort(422, 'Select atleast 1 operating unit');
        }

        $request->validate([
            'email' => 'required|email|unique:sellers',
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
        ]);
        // return Auth::user();
        // return $request->all();
        // return Auth::user()->company_id;
        $data = $request->all();
        $user = Seller::create(
            [
                'email' => $data['email'],
                'name' => $data['name'],
                'phone' => $data['phone'],
                'company_id' => Auth::user()->company_id,
                'ou_id' => Auth::user()->ou_id,
                'address' => $data['address'],
                'autogenerate' => ($data['generate'] == 'generate') ? true : false,
                'order_no_start' => (array_key_exists('order_no_start', $data)) ? $data['order_no_start'] : '',
                'order_no_end' => (array_key_exists('order_no_end', $data)) ?  $data['order_no_end'] : '',
                'password' => Hash::make('password'),
                'active' => true
            ]
        );
        // $user = Seller::find(2); 
        // return  $data['name'];
        // return  $data['company_name'];


        $selected = $request->selected;
        $services = $request->services;
        foreach ($services as $service) {
            if (in_array($service['id'], $selected)) {
                $service_create = new SellerService();
                $service_create->create($user->id, $service['id'], $service['charges']);
            }
        }



        foreach ($ous as $ou) {
            // return $ou['checked'];
            if (array_key_exists('checked', $ou)) {
                if ($ou['checked']) {
                    $ou_create = new VendorOu();
                    $ou_create->create($user->id, $ou['id']);
                }
            }
        }
        /* 
        foreach ($ous as $ou) {
            if (!$ou['checked']) {
                VendorOu::where('ou_id', $ou['id'])->where('vendor_id', $user->id)->delete();
            } else {
                $ou_create = new VendorOu();
                $ou_create->create($user->id, $ou['id'], $ou['charges']);
            }
        } */

        $user_details = Storedetails::updateOrCreate(
            [
                'seller_id' => $user->id
            ],
            [
                'company_name' => (array_key_exists('company_name', $data) && $data['company_name']) ? $data['company_name'] : $data['name'],
                'company_address' => (array_key_exists('company_address', $data) && $data['company_name']) ? $data['company_address'] : $data['address'],
                'address_2' => (array_key_exists('address_2', $data) && $data['address_2']) ? $data['address_2'] : null,
                'company_phone' => (array_key_exists('company_phone', $data) && $data['company_phone']) ? $data['company_phone'] : $data['phone'],
                'company_email' => (array_key_exists('company_email', $data) && $data['company_email']) ? $data['company_email'] : $data['email'],
                'company_website' => (array_key_exists('company_website', $data) && $data['company_website']) ? $data['company_website'] : null,
                'postal_code' => (array_key_exists('postal_code', $data) && $data['postal_code']) ? $data['postal_code'] : null,
                'business_no' => (array_key_exists('business_no', $data) && $data['business_no']) ? $data['business_no'] : null,
                'building' => (array_key_exists('building', $data) && $data['building']) ? $data['building'] : null,
                'floor' => (array_key_exists('floor', $data) && $data['floor']) ? $data['floor'] : null,
                'location' => (array_key_exists('location', $data) && $data['location']) ? $data['location'] : null,
                'longitude' => (array_key_exists('longitude', $data) && $data['longitude']) ? $data['longitude'] : null,
                'latitude' => (array_key_exists('latitude', $data) && $data['latitude']) ? $data['latitude'] : null,
                'payment_mode' => (array_key_exists('payment_mode', $data) && $data['payment_mode']) ? $data['payment_mode'] : null,
                'bank_name' => (array_key_exists('bank_name', $data) && $data['bank_name']) ? $data['bank_name'] : null,
                'bank_code' => (array_key_exists('bank_code', $data) && $data['bank_code']) ? $data['bank_code'] : null,
                'account_no' => (array_key_exists('account_no', $data) && $data['account_no']) ? $data['account_no'] : null,
                'branch' => (array_key_exists('branch', $data) && $data['branch']) ? $data['branch'] : null,
                'account_name' => (array_key_exists('account_name', $data) && $data['account_name']) ? $data['account_name'] : null,
                'mpesa_reg_name' => (array_key_exists('mpesa_reg_name', $data) && $data['mpesa_reg_name']) ? $data['mpesa_reg_name'] : null,
                'mpesa_phone' => (array_key_exists('mpesa_phone', $data) && $data['mpesa_phone']) ? $data['mpesa_phone'] : null
            ]
        );

        $setup = new Setup();
        $setup->updateSetup('vendors');
        $role = Role::where('guard_name', 'seller')->first();

        $user->assignRole($role->name);
        $cacheKey = 'sellers_' . Auth::user()->ou_id;
        Cache::forget($cacheKey);

        return $user_details;
    }
    public function update(Request $request, $id)
    {
        $seller = Seller::withoutGlobalScopes()->where('id', $id)->first();
        $email = ($request->email == '-') ? $seller->email : $request->email;
        $phone = ($request->phone == '-') ? $seller->phone : $request->phone;
        $address = ($request->address == '-') ? $seller->address : $request->address;
        // $this->authorize('delete', Seller::find($id));
        // return $request->all();
        $data = $request->all();
        $user = Seller::withoutGlobalScopes()->where('id', $id)->update([
            'name' => $data['name'],
            'phone' => $phone,
            'address' => $address,
            'email' => $email,
            'password' => bcrypt('password'),
            // 'active' => true,
            'telegram_notifications' => $data['telegram_notifications'],
            'send_sms' => $data['send_sms'],
            'sheet_update' => $data['sheet_update']
        ]);

        // $selected = $request->selected;
        $services = $request->services;
        // SellerService::whereNotIn('id', $selected)->where('seller_id', $id)->delete();
        foreach ($services as $service) {
            // return $service;
            if (!$service['checked']) {
                SellerService::where('operating_id', Auth::user()->ou_id)->where('service_id', $service['id'])->where('seller_id', $id)->delete();
                // if (in_array($service['id'], $selected)) {
            } else {
                $service_create = new SellerService();
                $service_create->create($id, $service['id'], $service['charges']);
            }
        }
        $ous = $request->ous;

        foreach ($ous as $ou) {
            // return $ou['checked'];
            if (array_key_exists('checked', $ou)) {
                if (!$ou['checked']) {
                    VendorOu::where('ou_id', $ou['id'])->where('vendor_id', $id)->delete();
                    // if (in_array($service['id'], $selected)) {
                } else {
                    $ou_create = new VendorOu();
                    $ou_create->create($id, $ou['id']);
                }
            }
        }

        $vendor = Seller::withoutGlobalScopes()->find($id);
        $role = Role::where('guard_name', 'seller')->first();

        $vendor->assignRole($role->name);

        return;


        $data = $request->storedetail;
        $user_details = Storedetails::where('seller_id', $id)->update([
            'company_name' => (array_key_exists('company_name', $data) && $data['company_name']) ? $data['company_name'] : $data['name'],
            'company_address' => (array_key_exists('company_address', $data) && $data['company_name']) ? $data['company_address'] : $data['address'],
            'address_2' => (array_key_exists('address_2', $data) && $data['address_2']) ? $data['address_2'] : null,
            'company_phone' => (array_key_exists('company_phone', $data) && $data['company_phone']) ? $data['company_phone'] : $data['phone'],
            'company_email' => (array_key_exists('company_email', $data) && $data['company_email']) ? $data['company_email'] : $data['email'],
            'company_website' => (array_key_exists('company_website', $data) && $data['company_website']) ? $data['company_website'] : null,
            'postal_code' => (array_key_exists('postal_code', $data) && $data['postal_code']) ? $data['postal_code'] : null,
            'business_no' => (array_key_exists('business_no', $data) && $data['business_no']) ? $data['business_no'] : null,
            'building' => (array_key_exists('building', $data) && $data['building']) ? $data['building'] : null,
            'floor' => (array_key_exists('floor', $data) && $data['floor']) ? $data['floor'] : null,
            'location' => (array_key_exists('location', $data) && $data['location']) ? $data['location'] : null,
            'longitude' => (array_key_exists('longitude', $data) && $data['longitude']) ? $data['longitude'] : null,
            'latitude' => (array_key_exists('latitude', $data) && $data['latitude']) ? $data['latitude'] : null,
            'payment_mode' => (array_key_exists('payment_mode', $data) && $data['payment_mode']) ? $data['payment_mode'] : null,
            'bank_name' => (array_key_exists('bank_name', $data) && $data['bank_name']) ? $data['bank_name'] : null,
            'bank_code' => (array_key_exists('bank_code', $data) && $data['bank_code']) ? $data['bank_code'] : null,
            'account_no' => (array_key_exists('account_no', $data) && $data['account_no']) ? $data['account_no'] : null,
            'branch' => (array_key_exists('branch', $data) && $data['branch']) ? $data['branch'] : null,
            'account_name' => (array_key_exists('account_name', $data) && $data['account_name']) ? $data['account_name'] : null,
            'mpesa_reg_name' => (array_key_exists('mpesa_reg_name', $data) && $data['mpesa_reg_name']) ? $data['mpesa_reg_name'] : null,
            'mpesa_phone' => (array_key_exists('mpesa_phone', $data) && $data['mpesa_phone']) ? $data['mpesa_phone'] : null
        ]);
        return $user_details;
    }


    public function seller_search($search)
    {

        return Seller::where('name', 'LIKE', "%{$search}%")
            ->orWhere('email', 'LIKE', "%{$search}%")
            ->orWhere('phone', 'LIKE', "%{$search}%")
            ->paginate(100);
    }



    public function myServices($id)
    {
        $seller = Seller::setEagerLoads([])->with(['services' => function ($query) {
            $query->setEagerLoads([]);
        }, 'ous'])->where('id', $id)->first();
        $selle_serv =  $seller->services;
        $ids = [];
        // dd($selle_serv);

        foreach ($selle_serv as $value) {
            // dd($value);
            $ids[] = $value['id'];
        }

        // $seller_services = Service::whereIn('id', $ids)->get();
        $services = Service::setEagerLoads([])->select('id', 'name', 'charges')->get();
        $services->transform(function ($service) use ($ids, $id) {
            $seller_service = SellerService::where('seller_id', $id)->where('service_id', $service->id)->first('amount');
            if (in_array($service->id, $ids)) {
                $service->checked = true;
                $service->charges = $seller_service['amount'];
            } else {
                $service->checked = false;
            }

            return $service;
        });
        $ous = Ou::select('id', 'ou')->get();
        $ous->transform(function ($ou) use ($ids, $id) {
            $ou_vendor = VendorOu::where('vendor_id', $id)->where('ou_id', $ou->id)->exists();
            if ($ou_vendor) {
                $ou->checked = true;
            } else {
                $ou->checked = false;
            }
            return $ou;
        });

        return ['services' => $services, 'ous' => $ous];
    }

    public function myOus($id)
    {
        $seller = Seller::setEagerLoads([])->with(['services' => function ($query) {
            $query->setEagerLoads([]);
        }, 'ous'])->where('id', $id)->first();
        $selle_serv =  $seller->services;
        $ids = [];
        // dd($selle_serv);

        foreach ($selle_serv as $value) {
            // dd($value);
            $ids[] = $value['id'];
        }

        // $seller_services = Service::whereIn('id', $ids)->get();
        $services = Service::setEagerLoads([])->select('id', 'name', 'charges')->get();

        $services->transform(function ($service) use ($ids, $id) {
            $seller_service = SellerService::where('seller_id', $id)->where('service_id', $service->id)->first();
            // dd($seller_service);
            if (in_array($service->id, $ids)) {
                $service->checked = true;
                $service->charges = $seller_service['amount'];
            } else {
                $service->checked = false;
            }
            // if ($seller_service) {

            // }
            return $service;
        });

        return $services;
    }

    public function userpermisions(Request $request, $id)
    {
        // return $request->all();
        $user = Seller::find($id);
        $user->syncPermissions($request->selected);
        return $user;
    }

    public function getVendorPerm(Request $request, $id)
    {
        // return Permission::where('guard_name', $guard_name)->get();

        $user = Seller::find($id);
        $permissions = $user->getAllPermissions();
        $can = [];
        foreach ($permissions as $perm) {
            $can[] = $perm->name;
        }
        return $can;
    }
    public function vendor_product($id)
    {
        return Product::where('vendor_id', $id)->get();
    }

    public function destroy($id)
    {
        $this->authorize('delete', Seller::find($id));
        Seller::find($id)->delete();
    }

    public function shopify_email(Request $request, $id)
    {
        // return $request->all();

        $shoppify = Seller::find($id);
        $shoppify->shopify_email = $request->shopify_email;
        $shoppify->save();
    }

    public function portal_status(Request $request, $id)
    {
        $vendor = Seller::find($id);

        $url = env('APP_URL') . '/seller/login';
        // $url = 'https://' . tenant('id') . env('CENTRAL_DOMAIN', '.logixsaas.com') . '/seller/login';
        $pass = Str::random(9);

        $password = Hash::make($pass);
        $status = $request->status;
        if ($status == 'deactivated') {
            $vendor->update(['portal_active' => false, 'password' => $password]);
        } else {
            // $this->authorize('create', Seller::class);
            $vendor->update(['portal_active' => true, 'password' => $password]);
        }
        // $email = ['support@logixsaas.com'];
        $email = ['support@logixsaas.com', $vendor->email];
        Mail::to($email)->send(new PortalMail($vendor, $pass, $url, $status));
        // Mail::to($vendor->email)->send(new PortalMail($vendor, $pass, $url, $status));
    }


    public function seller_password(Request $request, $id)
    {
        Seller::find($id)->update(['password' => Hash::make($request->password)]);
    }
}
