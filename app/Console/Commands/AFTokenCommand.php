<?php

namespace App\Console\Commands;

use App\Models\Ou;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AFTokenCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'africastalking:token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate africastalking agent tokens';

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
        // if (Auth::user()->email == 'support@logixsaas.com') {
        $user = new User();
        $ous = Ou::where('phone', '!=', null)->get();
        foreach ($ous as $key => $ou) {
            $ou_id = $ou->id;
            $user->getToken($ou->phone, $ou_id);
        }
        // }
    }
}
