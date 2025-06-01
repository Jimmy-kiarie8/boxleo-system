<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Laravel\Passport\ClientRepository;

class PassportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $client = new ClientRepository();

        $url = 'http://' . tenant('id') . env('CENTRAL_DOMAIN') . '/apicallback';

        $client->createPasswordGrantClient(null, tenant('id'), $url);
        $client->createPersonalAccessClient(null, tenant('id'), $url);
    }
}
