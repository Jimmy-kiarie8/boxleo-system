<?php

namespace App\Jobs;

use App\Models\Ou;
use App\Models\Sale;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\Cache\LockTimeoutException;

class OrderAssignJob1 implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 60;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */

    public function handle()
    {
        $lock = Cache::lock('orderAssign', 25);
        try {
            $lock->block(25);
            $ous = Ou::where('active', true)->get();

            foreach ($ous as $key => $ou) {
                $orders = Sale::whereBetween('created_at', [Carbon::now()->subDays(3), Carbon::tomorrow()])
                    ->where('delivery_status', 'New')
                    ->where('agent_id', null)
                    ->where('ou_id', $ou->id)
                    ->when($ou->id == 1, function ($q) {
                        return $q->whereNotIn('seller_id', [54, 149, 287, 331]);
                    })
                    ->inRandomOrder()
                    ->take(50)
                    ->get();


                if (count($orders) > 0) {
                    // Step 1: Get the users and orders
                    $users = User::setEagerLoads([])
                        // ->where('agent_sip', '!=', null)
                        ->where('ou_id', $ou->id)
                        ->role('Call center')
                        ->where('active', true)
                        ->where('can_receive_orders', true)
                        ->get();


                    // Step 2: Remove duplicates from orders and keep only one copy
                    $uniqueOrders = $orders->unique('order_no');

                    // Step 3: Get the duplicate order IDs to remove
                    $duplicateOrderIds = $orders->pluck('id')->diff($uniqueOrders->pluck('id'));

                    // Step 4: Delete the duplicate orders except for one copy
                    foreach ($duplicateOrderIds as $orderId) {
                        Sale::where('id', $orderId)->where('ou_id', $ou->id)->orderBy('id', 'desc')->skip(1)->delete();
                    }

                    // Step 5: Retrieve the last assigned order and starting user index from the database or persistent storage
                    $lastAssignedOrder = Sale::whereNotNull('agent_id')->where('ou_id', $ou->id)->orderBy('id', 'desc')->first();
                    $lastAssignedUserIndex = $lastAssignedOrder ? array_search($lastAssignedOrder->agent_id, $users->pluck('id')->toArray()) : -1;

                    // Step 6: Calculate the number of orders and users, and orders per user
                    // $orderCount = count($uniqueOrders);
                    $userCount = count($users);
                    // $ordersPerUser = ceil($orderCount / $userCount);


                    if ($userCount > 0) {
                        $userIndex = ($lastAssignedUserIndex + 1) % $userCount;


                        // Step 7: Distribute orders to users, starting from the last assigned user index
                        $userIndex = ($lastAssignedUserIndex + 1) % $userCount;
                        foreach ($uniqueOrders as $order) {

                            $user = $users[$userIndex];
                            // Assign the order to the current user
                            $order->agent_id = $user->id;


                            $order->save();

                            // Move to the next user in a round-robin fashion
                            $userIndex = ($userIndex + 1) % $userCount;
                            // Lock acquired after waiting a maximum of 5 seconds...

                        }
                    }
                }
            }








            // Litudian




            $orders = Sale::whereBetween('created_at', [Carbon::now()->subDays(3), Carbon::tomorrow()])
                ->where('delivery_status', 'New')
                ->where('agent_id', null)
                ->where('ou_id', 1)
                ->whereIn('seller_id', [287, 331])
                ->inRandomOrder()
                ->take(100)
                ->get();

            if (count($orders) > 0) {
                $emails = [
                    'christine.muga@boxleocourier.com'
                ];


                $users = User::setEagerLoads([])
                    // ->where('agent_sip', '!=', null)
                    ->whereIn('email', $emails)
                    ->where('active', true)
                    ->get();

                if (count($orders) > 0) {
                    // Step 1: Get the users and orders


                    // Step 2: Remove duplicates from orders and keep only one copy
                    $uniqueOrders = $orders->unique('order_no');

                    // Step 3: Get the duplicate order IDs to remove
                    $duplicateOrderIds = $orders->pluck('id')->diff($uniqueOrders->pluck('id'));

                    // Step 4: Delete the duplicate orders except for one copy
                    foreach ($duplicateOrderIds as $orderId) {
                        Sale::where('id', $orderId)->where('ou_id', $ou->id)->orderBy('id', 'desc')->skip(1)->delete();
                    }

                    // Step 5: Retrieve the last assigned order and starting user index from the database or persistent storage
                    $lastAssignedOrder = Sale::whereNotNull('agent_id')->where('ou_id', $ou->id)->orderBy('id', 'desc')->first();
                    $lastAssignedUserIndex = $lastAssignedOrder ? array_search($lastAssignedOrder->agent_id, $users->pluck('id')->toArray()) : -1;

                    // Step 6: Calculate the number of orders and users, and orders per user
                    // $orderCount = count($uniqueOrders);
                    $userCount = count($users);
                    // $ordersPerUser = ceil($orderCount / $userCount);


                    if ($userCount > 0) {
                        $userIndex = ($lastAssignedUserIndex + 1) % $userCount;


                        // Step 7: Distribute orders to users, starting from the last assigned user index
                        $userIndex = ($lastAssignedUserIndex + 1) % $userCount;
                        foreach ($uniqueOrders as $order) {

                            $user = $users[$userIndex];
                            // Assign the order to the current user
                            $order->agent_id = $user->id;


                            $order->save();

                            // Move to the next user in a round-robin fashion
                            $userIndex = ($userIndex + 1) % $userCount;
                            // Lock acquired after waiting a maximum of 5 seconds...

                        }
                    }
                }
            }










            $orders = Sale::whereBetween('created_at', [Carbon::now()->subDays(3), Carbon::tomorrow()])
                ->where('delivery_status', 'New')
                ->where('agent_id', null)
                ->where('ou_id', 1)
                ->where('seller_id', 54)
                ->inRandomOrder()
                ->take(100)
                ->get();


            if (count($orders) > 0) {
                # code...
                $emails = [
                    'leah.boxleo@gmail.com',
                    'mwangi.boxleo@gmail.com',
                    'angela.boxleo@gmail.com',
                    'elizabeth.boxleo@gmail.com',
                    'kimanthi.boxleo@gmail.com',
                    // 'catherine.boxleo@gmail.com	',
                    'nafula.boxleo@gmail.com',
                    // 'katoni.boxleo@gmail.com'
                ];


                $users = User::setEagerLoads([])
                    // ->where('agent_sip', '!=', null)
                    ->whereIn('email', $emails)
                    ->where('active', true)
                    ->get();

                if (count($orders) > 0) {
                    // Step 1: Get the users and orders


                    // Step 2: Remove duplicates from orders and keep only one copy
                    $uniqueOrders = $orders->unique('order_no');

                    // Step 3: Get the duplicate order IDs to remove
                    $duplicateOrderIds = $orders->pluck('id')->diff($uniqueOrders->pluck('id'));

                    // Step 4: Delete the duplicate orders except for one copy
                    foreach ($duplicateOrderIds as $orderId) {
                        Sale::where('id', $orderId)->where('ou_id', $ou->id)->orderBy('id', 'desc')->skip(1)->delete();
                    }

                    // Step 5: Retrieve the last assigned order and starting user index from the database or persistent storage
                    $lastAssignedOrder = Sale::whereNotNull('agent_id')->where('ou_id', $ou->id)->orderBy('id', 'desc')->first();
                    $lastAssignedUserIndex = $lastAssignedOrder ? array_search($lastAssignedOrder->agent_id, $users->pluck('id')->toArray()) : -1;

                    // Step 6: Calculate the number of orders and users, and orders per user
                    // $orderCount = count($uniqueOrders);
                    $userCount = count($users);
                    // $ordersPerUser = ceil($orderCount / $userCount);


                    if ($userCount > 0) {
                        $userIndex = ($lastAssignedUserIndex + 1) % $userCount;


                        // Step 7: Distribute orders to users, starting from the last assigned user index
                        $userIndex = ($lastAssignedUserIndex + 1) % $userCount;
                        foreach ($uniqueOrders as $order) {

                            $user = $users[$userIndex];
                            // Assign the order to the current user
                            $order->agent_id = $user->id;


                            $order->save();

                            // Move to the next user in a round-robin fashion
                            $userIndex = ($userIndex + 1) % $userCount;
                            // Lock acquired after waiting a maximum of 5 seconds...

                        }
                    }
                }
            }
        } catch (LockTimeoutException $e) {
            // Unable to acquire lock...
        } finally {
            optional($lock)->release();
        }
    }

    /*
    public function handle()
    {
        $lock = Cache::lock('orderAssign', 10);
        try {
            $lock->block(10);
            $ous = Ou::where('active', true)->get();

            foreach ($ous as $key => $ou) {
                $orders = Sale::whereBetween('created_at', [Carbon::now()->subDays(7), Carbon::tomorrow()])
                    ->where('delivery_status', 'New')
                    ->where('agent_id', null)
                    ->where('ou_id', $ou->id)
                    ->when($ou->id == 1, function ($q) {
                        return $q->whereNotIn('seller_id', [54, 149]);
                    })
                    ->inRandomOrder()
                    ->take(100)
                    ->get();

                if (count($orders) > 0) {
                    // Step 1: Get the users and orders
                    $users = User::setEagerLoads([])
                        ->where('agent_sip', '!=', null)
                        ->where('ou_id', $ou->id)
                        ->role('Call center')
                        ->where('active', true)
                        ->get();

                    // Step 2: Remove duplicates from orders and keep only one copy
                    $uniqueOrders = $orders->unique('order_no');

                    // Step 3: Get the duplicate order IDs to remove
                    $duplicateOrderIds = $orders->pluck('id')->diff($uniqueOrders->pluck('id'));

                    // Step 4: Delete the duplicate orders except for one copy
                    foreach ($duplicateOrderIds as $orderId) {
                        Sale::where('id', $orderId)->where('ou_id', $ou->id)->orderBy('id', 'desc')->skip(1)->delete();
                    }

                    // Step 5: Retrieve the last assigned order and starting user index from the database or persistent storage
                    $lastAssignedOrder = Sale::whereNotNull('agent_id')->where('ou_id', $ou->id)->orderBy('id', 'desc')->first();
                    $lastAssignedUserIndex = $lastAssignedOrder ? array_search($lastAssignedOrder->agent_id, $users->pluck('id')->toArray()) : -1;

                    // Step 6: Calculate the number of orders and users, and orders per user
                    $userCount = count($users);

                    // Step 7: Distribute orders to users, starting from the last assigned user index
                    $userIndex = ($lastAssignedUserIndex + 1) % $userCount;

                    foreach ($uniqueOrders as $order) {
                        // Acquire the lock periodically to release it for other processes
                        $this->releaseLock($lock, $userIndex);

                        $user = $users[$userIndex];
                        $order->agent_id = $user->id;
                        $order->save();
                        $userIndex = ($userIndex + 1) % $userCount;
                    }

                    // Release the lock at the end of processing for this OU
                    $this->releaseLock($lock);
                }
            }
        } catch (LockTimeoutException $e) {
            // Unable to acquire lock...
        } finally {
            optional($lock)->release();
        }
    }

    private function releaseLock($lock, $userIndex = null)
    {
        // Release the lock if it's currently held
        if ($lock->isLocked()) {
            $lock->release();
        }

        // Re-acquire the lock if a user index is provided
        if ($userIndex !== null) {
            $lock->block(10);
        }
    }*/
}
