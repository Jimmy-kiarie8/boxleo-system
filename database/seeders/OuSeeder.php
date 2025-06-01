<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Ou;
use Illuminate\Database\Seeder;

class OuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $company = Company::first();
        $faker = \Faker\Factory::create();
        foreach (range(1, 1) as $index) {
            Ou::create([
                'ou' => 'HQ',
                'ou_code' => 'HQ',
                'company_id' => $company->id,
            ]);
        }
    }
}
