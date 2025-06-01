<?php

namespace Database\Seeders;

use App\Branch;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        foreach (range(1, 2) as $index) {
            Branch::create([
                'name' => $faker->city,
                'email' => $faker->safeEmail,
                'phone' => $faker->phoneNumber,
                'address' => $faker->city,
                'delivery_charges' => $faker->numberBetween($min = 100, $max = 500),
                'return_charges' => $faker->numberBetween($min = 300, $max = 500)
            ]);
        }
    }
}
