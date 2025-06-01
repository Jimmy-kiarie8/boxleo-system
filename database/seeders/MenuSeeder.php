<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        $faker->addProvider(new \Bezhanov\Faker\Provider\Commerce($faker));
        foreach (range(1, 7) as $index) {
            Menu::create([
                'user_id' => $faker->numberBetween($min = 2, $max = 4),
                'menu' => $faker->department(3, true),
            ]);
        }
    }
}
