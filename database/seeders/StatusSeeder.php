<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            'Scheduled',
            'Delivered',
            'Dispatched',
            'Returned',
            'Cancelled',
            'Inprogress'
        ];

        foreach ($statuses as $key => $value) {
            Status::create([
                'status' => $value
            ]);
        }
    }
}
