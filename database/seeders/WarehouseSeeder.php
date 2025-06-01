<?php

namespace Database\Seeders;

use App\Models\Ou;
use App\Models\User;
use App\Models\Warehouse\Warehouse;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::first();
        $ou = Ou::first();
        $faker = \Faker\Factory::create();
        Warehouse::create([
            'user_id' => $user->id,
            'ou_id' => $ou->id,
            'name' => 'HQ',
            'code' => 'HQ'
        ]);
    }
}
