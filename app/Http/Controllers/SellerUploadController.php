<?php

namespace App\Http\Controllers;

use App\Imports\SellerImport;
use App\Models\Storedetails;
use App\Models\User;
use App\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class SellerUploadController extends Controller
{
    public function logged_user()
    {
        $user = new User();
        return  $user->logged_user();
    }
    public function get_sellers(Request $request)
    {

        // dd( $request->all());
        $sellers = Excel::toArray(new SellerImport, request()->file('file'));
        $arr = $sellers[0];


        $seller_trans = [];
        $seller_arr = [];
        foreach ($arr as $value) {
            // return $value;
            $seller_trans['name'] = $value['name'];
            // $seller_trans['name'] = $value['first_name'] . ' ' .$value['last_name'];
            $seller_trans['reference'] = $value['reference'];
            $seller_trans['email'] = $value['email'];
            $seller_trans['phone'] = $value['phone'];
            $seller_trans['address'] = $value['address'];
            $seller_trans['order_prefix'] = $value['order_prefix'];
            $seller_trans['date'] = $value['created_at'];
            $seller_trans['company_name'] = $value['name'];
            $seller_arr[] = $seller_trans;
        }



        return response()->json([
            'sellers' => $seller_arr,
            'exist_sellers' => [],
        ], 200);
    }


    public function sellersUpload(Request $request)
    {
        // return $request->all();
        $user = $this->logged_user();

        foreach ($request->sellers as $key => $data) {
            // $request->validate([
            //     'email' => 'required|unique:sellers',
            //     'name' => 'required',
            //     'phone' => 'required',
            // ]);
            // $data = $request->all();
            $seller = Seller::create(
                [
                    'email' => $data['email'],
                    'name' => $data['name'],
                    'reference' => $data['reference'],
                    'phone' => $data['phone'],
                    'company_id' => Auth::user()->company_id,
                    'ou_id' => Auth::user()->ou_id,
                    'address' => $data['address'],
                    'order_prefix' => $data['order_prefix'],

                    'date' => $data['date'],
                    'created_at' => $data['date'],
                    // 'autogenerate' => ($data['generate'] == 'generate') ? true : false,
                    // 'order_no_start' => (array_key_exists('order_no_start', $data)) ? $data['order_no_start'] : '',
                    // 'order_no_end' => (array_key_exists('order_no_end', $data)) ?  $data['order_no_end'] : '',
                    'password' => Hash::make('password'),
                    'active' => true
                ]
            );

            $user_details = Storedetails::updateOrCreate(
                [
                    'seller_id' => $seller->id
                ],
                [
                    'company_name' => (array_key_exists('company_name', $data)) ? $data['company_name'] : $seller->name,
                    'company_address' => (array_key_exists('address', $data)) ? $data['address'] : $seller->address,
                    'address_2' => (array_key_exists('address_2', $data)) ? $data['address_2'] : null,
                    'company_phone' => (array_key_exists('company_phone', $data)) ? $data['company_phone'] : $seller->phone,
                    'company_email' => (array_key_exists('company_email', $data)) ? $data['company_email'] : $seller->email,
                    'company_website' => (array_key_exists('company_website', $data)) ? $data['company_website'] : null,
                    'postal_code' => (array_key_exists('postal_code', $data)) ? $data['postal_code'] : null,
                    'business_no' => (array_key_exists('business_no', $data)) ? $data['business_no'] : null,
                    'building' => (array_key_exists('building', $data)) ? $data['building'] : null,
                    'floor' => (array_key_exists('floor', $data)) ? $data['floor'] : null,
                    'location' => (array_key_exists('location', $data)) ? $data['location'] : null,
                    'longitude' => (array_key_exists('longitude', $data)) ? $data['longitude'] : null,
                    'latitude' => (array_key_exists('latitude', $data)) ? $data['latitude'] : null,
                    'payment_mode' => (array_key_exists('payment_mode', $data)) ? $data['payment_mode'] : null,
                    'bank_name' => (array_key_exists('bank_name', $data)) ? $data['bank_name'] : null,
                    'bank_code' => (array_key_exists('bank_code', $data)) ? $data['bank_code'] : null,
                    'account_no' => (array_key_exists('account_no', $data)) ? $data['account_no'] : null,
                    'branch' => (array_key_exists('branch', $data)) ? $data['branch'] : null,
                    'account_name' => (array_key_exists('account_name', $data)) ? $data['account_name'] : null,
                    'mpesa_reg_name' => (array_key_exists('mpesa_reg_name', $data)) ? $data['mpesa_reg_name'] : null,
                    'mpesa_phone' => (array_key_exists('mpesa_phone', $data)) ? $data['mpesa_phone'] : null
                ]
            );
        }

        return 'success';
    }
}
