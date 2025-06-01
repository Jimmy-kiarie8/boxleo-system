<?php

namespace Database\Seeders;

use App\Mail\CustomMail;
use App\Models\Company;
use App\Models\Ou;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

// use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ou = Ou::first('id');
        $company = Company::first('id');
        // dd(tenant('id'));

        // $subscriber = tenant()->subscriber;
        // if ($subscriber) {
        //     $name = $subscriber['name'];
        //     $email = $subscriber['email'];
        //     $phone = $subscriber['phone'];
        //     // $password = $subscriber['password'];
        // } else {

        $name = 'Logixsaas';
        $email = 'support@logixsaas.com';
        $phone = '+19176727279';
        $password = str_random(8);
        User::create([
            'name' => $name,
            'email' => $email,
            'email_verified_at' => now(),
            'password' => Hash::make($password), // password
            'phone' => $phone,
            // 'address' => $address,
            'ou_id' => $ou->id,
            'company_id' => $company->id
        ]);

        $content = 'This is the account password: ' . $password;


        Mail::to('support@logixsaas.com')
            ->send(new CustomMail($content));
    }
}
