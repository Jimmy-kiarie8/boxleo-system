<?php

namespace App\Http\Controllers\shopify;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Osiset\ShopifyApp\Http\Controllers\AuthController as ControllersAuthController;

class AuthController extends Controller
{
    public function authenticate(Request $request)
    {
        // dd($request->all());
    }
}
