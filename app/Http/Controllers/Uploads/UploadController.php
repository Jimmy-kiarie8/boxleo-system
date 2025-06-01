<?php

namespace App\Http\Controllers\Uploads;

use App\Http\Controllers\Controller;
use App\Imports\UserImport;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class UploadController extends Controller
{

    public function logged_user()
    {
        $user = new User();
        return $user->logged_user();
    }
    public function get_users(Request $request)
    {

        // dd( $request->all());
        $users = Excel::toArray(new UserImport, request()->file('file'));
        $arr = $users[0];

        $user_trans = [];
        $user_arr = [];
        foreach ($arr as $value) {
            // return $value;
            $user_trans['name'] = $value['name'];
            // $user_trans['name'] = $value['first_name'] . ' ' .$value['last_name'];
            $user_trans['email'] = $value['email'];
            $user_trans['phone'] = $value['phone'];
            $user_trans['address'] = $value['address'];
            $user_trans['role'] = $value['role'];
            $user_arr[] = $user_trans;
        }


        return response()->json([
            'users' => $user_arr,
            'exist_users' => [],
        ], 200);
    }


    public function usersUpload(Request $request)
    {

        foreach ($request->users as $key => $value) {

            $user = new User;
            $user->name = $value['name'];
            $user->email = $value['email'];
            $user->phone = $value['phone'];
            $user->ou_id = Auth::user()->ou_id;
            $user->company_id = Company::first()->id;
            $user->save();
            // $user->assignRole($value['role']);

            $user_mail = new User;
            $user_mail->welcome_mail($user);

            // return $user;
        }
    }
}
