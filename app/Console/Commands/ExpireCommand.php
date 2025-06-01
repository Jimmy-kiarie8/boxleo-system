<?php

namespace App\Console\Commands;

use App\Models\OrderHistory;
use App\Models\Sale;
use App\Models\User;
use Carbon\Carbon;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ExpireCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:cancel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancell pedding olders which are more than a month old';

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
        $this->close_orders();
        $this->info("Updated!");
    }

    public function close_orders()
    {
        $today = Carbon::today();
        $prev_month = $today->subMonth();
        // Your Eloquent query
        $ids = Sale::select('id')->setEagerLoads([])
            ->where('delivery_status', 'Pending')
            ->whereDate('created_at', '<=', $prev_month)
            ->get('id')->pluck('id')->toArray();

        $history_comment = 'Order expiried';
        Sale::whereIn('id', $ids)->update(['delivery_status' => 'Cancelled', 'cancelled_on' => now(), 'history_comment' => $history_comment]);
        return;
    }
}
