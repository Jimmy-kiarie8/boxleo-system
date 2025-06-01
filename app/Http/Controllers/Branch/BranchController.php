<?php

namespace App\Http\Controllers\Branch;

use App\Branch;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BranchSale;
use App\Models\Sale;

class BranchController extends Controller
{
    public function index()
    {
        return Branch::with('sales')->get();
    }

    public function store(Request $request)
    {
        $data = $request->all();
        return Branch::create([
            'name' => $data['name'],
            'address' => $data['address'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'password' => bcrypt('password'),
            'delivery_charges' => $data['delivery_charges'],
            'return_charges' => $data['return_charges'],
        ]);
    }

}
