<?php

namespace Database\Seeders;

use App\Models\Setup;
use Illuminate\Database\Seeder;

class SetupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setup::create([
            'warehouse' => false,
            'company_logo' => false,
            'products' => false,
            'zones' => false,
            'status' => false,
            'services' => false,
            'vendors' => false
        ]);
    }
}
