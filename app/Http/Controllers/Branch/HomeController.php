<?php

namespace App\Http\Controllers\Branch;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{

    protected $redirectTo = '/branch/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('branch.auth:branch');
    }

    /**
     * Show the Branch dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view('branch.home');
    }

}