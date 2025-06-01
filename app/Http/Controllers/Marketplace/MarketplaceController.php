<?php

namespace App\Http\Controllers\Marketplace;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use App\Tenant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Spatie\Permission\Models\Permission;
use App\Models\api\Order;

class MarketplaceController extends Controller
{

    public function logged_user()
    {
        $user = new User();
        return  $user->logged_user();
    }
    public function marketplace()
    {
        if (!Company::first()) {
            return redirect('account_setup');
        }
        $user = $this->logged_user();
        $permissions = [];
        foreach (Permission::where('guard_name', 'web')->get() as $permission) {
            if ($user->hasPermissionTo($permission->name)) {
                $permissions[$permission->name] = true;
            } else {
                $permissions[$permission->name] = false;
            }
        }

        $auth_user = array_prepend($user->toArray(), $permissions, 'can');
        $logo = $auth_user['company']['logo'];
        return view('marketplace.index', compact('auth_user'));
    }
}
