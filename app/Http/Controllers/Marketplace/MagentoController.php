<?php

namespace App\Http\Controllers\Marketplace;

use App\Http\Controllers\Controller;
use Grayloon\Magento\Magento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class MagentoController extends Controller
{
    public function callback(Request $request)
    {
        // Log::debug('*************************');
        // Log::debug($request->all());
        // Log::debug('*************************');
        return true;
    }
    public function redirect(Request $request)
    {
        // Log::debug('*************************');
        // Log::debug($request->all());
        // Log::debug('*************************');
        // $data =

        return Redirect::to($request->success_call_back);

        return 'done';
    }

    public function login()
    {
        $username = 'jimkiarie8@gmail.com';
        $password = 'VJasNBEBbV5J67';
        $token = Magento::api('integration')->adminToken($username, $password);
    }
}
