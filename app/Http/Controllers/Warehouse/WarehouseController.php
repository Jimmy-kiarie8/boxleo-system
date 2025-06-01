<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Warehouse\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Warehouse\Level;
use App\Models\Warehouse\Product_warehouse;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Crypt;
use Spatie\Permission\Models\Permission;

class WarehouseController extends Controller
{
    public function logged_user()
    {
        $user = new User();
        return  $user->logged_user();
    }
    public function warehouse()
    {

        if (!Company::first()) {
            return redirect('account_setup');
        }
        // $tenant = Tenant::setEagerLoads([])->with(['subscriber', 'subscriptions' => function ($query) {
        //     $query->latest()->take(1);
        // }])->where('id', tenant('id'))->first();

        // $tenant->days_remaining = Carbon::parse($tenant->subscriber->subscription_expire)->diffInDays(Carbon::now());
        // return $tenant;
        //   return $plan = $tenant->subscriber->tenant_plan->portals;
        $user = $this->logged_user();

        $permissions = [];
        foreach (Permission::all() as $permission) {
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
        return view('warehouse.index', compact('auth_user'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $warehouses = Warehouse::all();
        $warehouses->transform(function ($warehouse) {
            // $user = User::find($warehouse->user_id);
            // $warehouse->warehouse_id = $warehouse->id;
            $warehouse->location = unserialize($warehouse->location);
            $warehouse->user_name = ($warehouse->user) ? $warehouse->user->name : '';
            return $warehouse;
        });
        return $warehouses;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:warehouses',
            'phone' => 'required',
            'location' => 'required',
        ]);
        $warehouse = new Warehouse;
        $warehouse->phone = $request->phone;
        $warehouse->name = $request->name;
        $warehouse->code = $request->name;
        $warehouse->location = serialize($request->location);
        $warehouse->user_id = $this->logged_user()->id;
        $warehouse->ou_id = $this->logged_user()->ou_id;
        $warehouse->save();
        return $warehouse;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Warehouse  $warehouse
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $warehouse = Warehouse::with('products')->find($id);
        $warehouse->products->transform(function ($product) use ($warehouse) {
            $onhand = Product_warehouse::where('product_id', $product['id'])->where('warehouse_id', $warehouse->id)->sum('onhand');
            // dd($onhand);
            $product->onhand = $onhand;
            return $product;
        });
        return $warehouse;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Warehouse  $warehouse
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // return $request->all();
        $warehouse = Warehouse::find($id);
        $warehouse->phone = $request->phone;
        $warehouse->name = $request->name;
        // if ($request->location['name'] != null) {
        $warehouse->location = serialize($request->location);
        // }
        $warehouse->user_id = Auth::id();
        $warehouse->save();
        return $warehouse;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Warehouse  $warehouse
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Warehouse  $warehouse
     * @return \Illuminate\Http\Response
     */
    public function dimensions(Request $request, $id)
    {
        // return $request->all();
        $warehouse = Warehouse::find($id);
        $warehouse->height = $request->height;
        $warehouse->length = $request->length;
        $warehouse->width = $request->width;
        $warehouse->capacity = $request->capacity;
        $warehouse->non_storage = $request->non_storage;
        $warehouse->save();
        return $warehouse;
    }


    public function bins()
    {
        return Warehouse::find(3);
    }

    public function levels()
    {
        return Level::all();
    }
}
