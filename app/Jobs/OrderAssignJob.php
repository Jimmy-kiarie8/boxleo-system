<?php

namespace App\Jobs;

use App\Models\Sale;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Cache\LockTimeoutException;

class OrderAssignJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 60;
    public $ou;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($ou)
    {
        $this->ou = $ou;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $lock = Cache::lock('orderAssign-' . $this->ou->id, 25);
        try {
            $lock->block(25);

            $this->assignOrdersToAgents();
            if ($this->ou->id == 1) {
                $this->assignSpecificOrders([287, 331, 344, 367], ['christine.muga@boxleocourier.com']);
                $this->assignSpecificOrders([54], [
                    'leah.boxleo@gmail.com',
                    'mwangi.boxleo@gmail.com',
                    'angela.boxleo@gmail.com',
                    'kimanthi.boxleo@gmail.com',
                    'nafula.boxleo@gmail.com',
                ]);

                $this->assignExcludeOrders([287, 331, 344, 367, 54], ['emmaculate.boxleo@gmail.com', 'christine.muga@boxleocourier.com']);
                // $this->assignExcludeOrders([367], ['emmaculate.boxleo@gmail.com']);
            }
        } catch (LockTimeoutException $e) {
            // Unable to acquire lock...
        } finally {
            optional($lock)->release();
        }
    }

    private function assignOrdersToAgents()
    {
        $this->processOrders(
            Sale::whereBetween('created_at', [Carbon::now()->subDays(3), Carbon::tomorrow()])
                ->where('delivery_status', 'New')
                ->where('agent_id', null)
                ->where('ou_id', $this->ou->id)
                ->when($this->ou->id == 1, function ($query) {
                    // return $query->whereNotIn('seller_id', [149, 287, 331, 344]);
                    return $query->whereNotIn('seller_id', [54, 149, 287, 331, 344, 367]);
                })
                ->orderBy('id', 'asc')
                ->take(50)
        );
    }
    private function assignExcludeOrders(array $sellerIds, array $emails)
    {
        // Define time range for orders
        $startDate = Carbon::now()->subDays(3);
        $endDate = Carbon::tomorrow();
    
        // Query for orders with specific criteria
        $ordersQuery = Sale::whereBetween('created_at', [$startDate, $endDate])
            ->where('delivery_status', 'New')
            ->whereNull('agent_id') // use whereNull for null checks
            ->where('ou_id', 1)
            ->whereIn('seller_id', $sellerIds)
            ->inRandomOrder()
            ->take(50);
    
        // Define the roles to filter users by
        $roles = ['Call center', 'Follow UP'];
    
        // Fetch users who can receive orders, excluding specific emails
        $users = User::setEagerLoads([]) // disable eager loading for performance
            ->where('ou_id', $this->ou->id)
            ->where('can_receive_orders', true)
            ->where('active', true)
            ->whereNotIn('email', $emails) // Exclude users by email
            ->whereHas('roles', function ($query) use ($roles) {
                $query->whereIn('name', $roles);
            })
            ->get();
    
        // Return early if no users were found
        if ($users->isEmpty()) {
            return;
        }
    
        // Process the orders with the filtered users
        $this->processOrders($ordersQuery, $users);
    }
    

    private function assignSpecificOrders(array $sellerIds, array $emails)
    {
        $ordersQuery = Sale::whereBetween('created_at', [Carbon::now()->subDays(3), Carbon::tomorrow()])
            ->where('delivery_status', 'New')
            ->where('agent_id', null)
            ->where('ou_id', 1)
            ->whereIn('seller_id', $sellerIds)
            ->inRandomOrder()
            ->take(50);

        $users = User::setEagerLoads([])
            ->whereIn('email', $emails)
            ->where('active', true)
            ->get();

        if ($users->isEmpty()) {
            return;
        }

        $this->processOrders($ordersQuery, $users);
    }

    private function processOrders($ordersQuery, $users = null)
    {
        $orders = $ordersQuery->get();

        if ($orders->isEmpty()) {
            return;
        }

        // Get unique orders
        $uniqueOrders = $orders->unique('order_no');
        $duplicateOrderIds = $orders->pluck('id')->diff($uniqueOrders->pluck('id'));

        // Delete duplicate orders
        Sale::whereIn('id', $duplicateOrderIds)->delete();

        if (!$users) {
            // $users = User::setEagerLoads([])
            //     ->where('ou_id', $this->ou->id)
            //     ->role('Call center')
            //     ->where('active', true)
            //     ->where('can_receive_orders', true)
            //     ->get();

            $roles = ['Call center', 'Follow UP'];

            $users = User::setEagerLoads([])
                ->where('ou_id', $this->ou->id)
                ->where('active', true)
                ->where('can_receive_orders', true)
                ->whereHas('roles', function ($query) use ($roles) {
                    $query->whereIn('name', $roles);
                })
                ->get();
        }


        if ($users->isEmpty()) {
            return;
        }

        // Find the last assigned order to determine where to start assignment
        $lastAssignedOrder = Sale::whereNotNull('agent_id')
            ->where('ou_id', $this->ou->id)
            ->orderBy('id', 'desc')
            ->first();

        // Find the index of the last assigned user in our users array
        $lastAssignedUserIndex = -1;
        if ($lastAssignedOrder) {
            $userIds = $users->pluck('id')->toArray();
            $lastAssignedUserIndex = array_search($lastAssignedOrder->agent_id, $userIds);
            // If the user is not found in our current array, default to -1
            if ($lastAssignedUserIndex === false) {
                $lastAssignedUserIndex = -1;
            }
        }

        $userCount = count($users);
        // Start with the next user after the last assigned one
        $userIndex = ($lastAssignedUserIndex + 1) % $userCount;

        $bulkUpdateData = [];

        foreach ($uniqueOrders as $order) {
            $user = $users[$userIndex];
            $bulkUpdateData[] = [
                'id' => $order->id,
                'agent_id' => $user->id
            ];

            $userIndex = ($userIndex + 1) % $userCount;
        }

        // Perform bulk update
        DB::table('sales')->upsert($bulkUpdateData, ['id'], ['agent_id']);
    }
}
