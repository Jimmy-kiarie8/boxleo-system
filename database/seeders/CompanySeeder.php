<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $subscriber = tenant()->subscriber;
        // if ($subscriber) {
        //     $company = $subscriber['company'];
        //     $email = $subscriber['email'];
        //     // $password = $subscriber['password'];
        // } else {
        //     $company = 'Company name';
        //     $email = '';
        //     // $password = Hash::make('password');
        // }

        // foreach (range(1, 1) as $index) {
        Company::create([
            'name' => 'Logixsaas',
            'email' => 'support@logixsaas.com',
        ]);
        // }
    }
}
