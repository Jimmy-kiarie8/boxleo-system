<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class UserDeactivate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // return 0;

        $users = User::all();

        foreach ($users as $key => $user) {

            if (!$user->hasRole('Super admin')) {

                $user_update = User::find($user->id);
                $user_update->active = false;
                $user_update->save();
            }
        }


        $this->info('Users deactivated');


        // $users->hasRole('Admin')


    }
}
