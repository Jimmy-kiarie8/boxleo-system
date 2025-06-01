<?php

namespace Database\Seeders;

use App\Models\Addon;
use Illuminate\Database\Seeder;

class AddonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $addons = ['Users', 'Orders', 'Warehouses', 'Inventory managment', 'Shopify integrations', 'Wordpress integrations', 'Api integrations', 'Automations', 'Operating Units', 'Client portal'];
        // $amount = ['Users', 'Orders', 'Warehouses', 'Inventory managment', 'Shopify integrations', 'Wordpress integrations', 'Api integrations', 'Automations', 'Operating Units', 'Client portal'];

        foreach ($addons as $key => $value) {
            Addon::create([
                'addon' => $value,
                'amount' => 200
            ]);
        }
    }
}
