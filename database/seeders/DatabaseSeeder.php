<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CompanySeeder::class);
        $this->call(OuSeeder::class);
        $this->call(UserSeeder::class);
        // $this->call(SellerSeeder::class);
        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(StatusSeeder::class);
        $this->call(WarehouseSeeder::class);
        $this->call(SetupSeeder::class);

        $this->call(PassportSeeder::class);

        // $this->call(AddonSeeder::class);

        

        // $this->call(BranchSeeder::class);
        // $this->call(MenuSeeder::class);
        // $this->call(CategorySeeder::class);
        // $this->call(OptionSeeder::class);
        // $this->call(OptionValueSeeder::class);
        // $this->call(ProductTableSeeder::class);
    }
}
