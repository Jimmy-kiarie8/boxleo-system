<?php

namespace App\Http\Controllers\Rider;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{

    protected $redirectTo = '/rider/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('rider.auth:rider');
    }

    /**
     * Show the Rider dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view('rider.home');
    }

}