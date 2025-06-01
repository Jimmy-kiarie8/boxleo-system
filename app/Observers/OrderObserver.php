<?php

namespace App\Observers;

use App\Events\SaleEvent;
use App\Http\Resources\SaleResource;
use App\Jobs\AutomationJob;
use App\Jobs\ServiceJob;
use App\Jobs\WebhookDispatchJob;
use App\Models\Sale;
use App\Models\AutoGenerate;
use App\Models\DailyStats;
use App\Models\Invoice;
use App\Models\OrderHistory;
use App\Models\Service;
use App\Models\settings\Webhook;
use App\Models\Sms;
use App\Models\User;
use App\Models\Zone;
use App\Shopify\ShopifyService;
use Illuminate\Support\Facades\Auth;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Support\Facades\Log;
use Spatie\WebhookServer\WebhookCall;

class OrderObserver
{
    public function logged_user()
    {
        $user = new User();
        return  $user->logged_user();
    }
    /**
     * Handle the sale "created" event.
     *
     * @param  \App\model\Sale  $sale
     * @return void
     */
    public function created(Sale $sale)
    {
        // $orders[] = $sale;
        // if(count($orders) >= 10){
        //     AutomationJob::dispatch($sale->id, $sale->getDirty(), 'sales', 'Create', $sale);
        //     $orders = [];
        // }

        // AutomationJob::dispatch($sale->id, $sale->getDirty(), 'sales', 'Create');

        // if ($sale->id) {
        //     return;
        // }


        $history  = new OrderHistory();
        // if (Auth::guard('clients')->check()) {
        //     $user  = Auth::guard('clients')->user();
        // } elseif (Auth::guard('web')->check()) {
        //     $user = Auth::guard('web')->user();
        // } elseif (Auth::guard('api')->check()) {
        //     $user = Auth::guard('api')->user();
        // } else {
        //     $user = User::find($sale->user_id);
        // }

        // $user = (Auth::check()) ? $user : User::find($sale->user_id);
        Sale::withoutEvents(function () use ($sale, $history) {

            if ($sale->delivery_status == 'Scheduled') {
                $sale->confirmed = true;
                $sale->status = 'Confirmed';
            } elseif ($sale->delivery_status == 'Dispatched' || $sale->delivery_status == 'In Transit') {
                $sale->status = 'Shipped';
            } elseif ($sale->delivery_status == 'Pending') {
                $sale->status = $sale->delivery_status;
            } elseif ($sale->delivery_status == 'Delivered') {
                $sale->status = 'Closed';
            } elseif ($sale->delivery_status == 'Returned') {
                $sale->status = 'Closed';
            } elseif ($sale->delivery_status == 'Cancelled') {
                $sale->cancelled_on = now();
                $sale->status = 'Closed';
            } elseif ($sale->delivery_status == 'Awaiting Dispatch') {
                $sale->status = 'Awaiting Dispatch';
            } elseif ($sale->delivery_status == 'Awaiting Return') {
                $sale->status = 'Awaiting Return';
            } elseif ($sale->delivery_status == 'Undispatched') {
                $sale->status = 'Undispatched';
            } else {
                $sale->status = 'Pending';
            }


            $user = User::find($sale->user_id);

            if (!$user) {
                $user = User::first();
            }

            // if($user->is_vendor) {
            //     $user = User::first(); 
            // }

            $history->action = 'Order Created';
            $reference_no = IdGenerator::generate(['table' => 'order_histories', 'field' => 'reference_no', 'length' => 15, 'prefix' => 'REF_']);
            $history->comment = $sale->comment;
            $history->update_comment = 'Order placed';
            // $history->update_comment = 'Order created by ' . '<b style="color: red;">' . $user->name . '</b>';
            $history->user_name = $user->name;
            $history->reference_no = $reference_no;

            $history->user_id = $user->id;
            $history->sale_id = $sale->id;
            $tracking_comment = 'Order received';
            $history->tracking_comment = $tracking_comment;
            $history->save();
        });


        // sms
        if ($sale->seller_id === 292) {
            if ($sale->pickup_phone) {
                $sms = new Sms();
                $message = "Dear {$sale->pickup_shop}, We have received a request from [Shoppepo] to collect goods for order reference {$sale->pickup_phone}. Our call agent will reach out to you via +254709155578 to coordinate a prompt delivery to your client.";
                $sms->sms($sale->pickup_phone, $message, 1);
            }
        }

        // Dispatch webhook job for order creation
        // $webhook = Webhook::where('vendor_id', $sale->seller_id)->first();
        // if ($webhook) {
        //     WebhookDispatchJob::dispatch($sale, 'order_created', []);
        // }

        return;

        // Remove the old webhook code
        // $webhook = Webhook::where('vendor_id', $sale->seller_id)->first();
        // if ($webhook) {
        //     return SaleResource::collection(Sale::with(['orderHistory' => function ($query) {
        //         $query->setEagerLoads([]);
        //     }])->paginate(200));
        //     WebhookCall::create()
        //         ->url($webhook->url)
        //         ->payload(['key' => 'value'])
        //         ->useSecret('sign-using-this-secret')
        //         ->dispatch();
        // }
    }

    /**
     * Handle the sale "updated" event.
     *
     * @param  \App\model\Sale  $sale
     * @return void
     */
    public function updated(Sale $sale)
    {
        // $dispatcher = Sale::getEventDispatcher();
        // Sale::unsetEventDispatcher();
        Sale::withoutEvents(function () use ($sale) {
            // dd($sale->instructions);
            if ($sale->getDirty()) {

                // $history = OrderHistory::orderBy('id', 'Desc')->first()->updated_fields;
                $update_arr = array_keys($sale->getDirty());

                $updated = $sale->getDirty();
                unset($updated['updated_at']);
                unset($updated['history_comment ']);
                $original_arr = array();

                foreach ($update_arr as $k) {
                    if ($k != 'updated_at') {
                        $original_arr[$k] = $sale->getOriginal()[$k];
                    }
                }

                $user = User::find($sale->user_id);

                $history  = new OrderHistory();

                if (!$user) {
                    $user = (Auth::check()) ? $this->logged_user() : User::setEagerLoads([])->select('id', 'name')->where('email', env('ADMIN_MAIL', 'support@logixsaas.com'))->first();
                }

                $history->action = 'Order updated';
                $reference_no = make_reference_id('SKU', OrderHistory::max('id') + 1);;
                // $reference_no = IdGenerator::generate(['table' => 'order_histories', 'field' => 'reference_no', 'length' => 15, 'prefix' => 'REF_']);
                $history->comment = $sale->comment;
                $history->update_comment = $sale->history_comment;
                $history->user_name = $user->name;
                $history->reference_no = $reference_no;
                $tracking_comment = null;
                if ($sale->isDirty('delivery_status')) {
                    // Reduce the size
                    $sale_event = Sale::setEagerLoads([])->with(['products' => function ($q) {
                        $q->setEagerLoads([]);
                    }, 'client'])->find($sale->id);

                    // broadcast(new SaleEvent($user, $sale_event))->toOthers();
                }

                // Check if there's a webhook configured for this seller
                // $webhook = Webhook::where('vendor_id', $sale->seller_id)->first();

                if ($sale->getOriginal('delivery_status') != $sale->delivery_status) {
                    // $daily_start = new DailyStats();

                    if ($sale->delivery_status == 'Scheduled') {
                        $tracking_comment = 'Order confirmed';
                        $sale->confirmed = true;
                        $sale->schedule_date = now();
                        $sale->status = 'Confirmed';
                        // $daily_start->scheduled_count();
                    } elseif ($sale->delivery_status == 'Dispatched' || $sale->delivery_status == 'In Transit') {
                        $tracking_comment = 'In transit';
                        $sale->status = 'Shipped';
                    } elseif ($sale->delivery_status == 'Pending') {
                        $tracking_comment = 'Pending';
                        $sale->status = $sale->delivery_status;
                    } elseif ($sale->delivery_status == 'Delivered') {
                        $tracking_comment = 'Delivered';
                        $sale->status = 'Closed';
                    } elseif ($sale->delivery_status == 'Returned') {
                        $tracking_comment = 'Returned';
                        $sale->status = 'Closed';
                    } elseif ($sale->delivery_status == 'Cancelled') {
                        $tracking_comment = 'Cancelled';
                        // $sale->cancelled_on = ($sale->cancelled_on) ? $sale->cancelled_on : now();
                        $sale->status = 'Closed';
                    } elseif ($sale->delivery_status == 'Awaiting Dispatch') {
                        $tracking_comment = 'Awaiting Dispatch';
                        $sale->status = 'Awaiting Dispatch';
                    } elseif ($sale->delivery_status == 'Undispatched') {
                        $tracking_comment = 'Undispatched';
                        $sale->status = 'Undispatched';
                    } else {
                        $tracking_comment = $sale->delivery_status;
                        $sale->status = 'Pending';
                    }

                    $sale->save();

                    // Dispatch webhook for delivery status change if webhook exists
                    // if ($webhook) {
                    //     WebhookDispatchJob::dispatch($sale, 'delivery_status_changed', [
                    //         'old_status' => $sale->getOriginal('delivery_status'),
                    //         'new_status' => $sale->delivery_status
                    //     ]);
                    // }
                }

                // Dispatch webhook for client details updated if client_id has changed and webhook exists
                // if ($webhook && isset($updated['client_id'])) {
                //     WebhookDispatchJob::dispatch($sale, 'client_updated', [
                //         'old_client_id' => $original_arr['client_id'] ?? null,
                //         'new_client_id' => $updated['client_id'] ?? null
                //     ]);
                // }

                // Dispatch webhook for products updated if products have changed and webhook exists
                // if ($webhook && $sale->isDirty('products')) {
                //     WebhookDispatchJob::dispatch($sale, 'products_updated', [
                //         'old_products' => $original_arr['products'] ?? [],
                //         'new_products' => $updated['products'] ?? []
                //     ]);
                // }

                $history->tracking_comment = $tracking_comment;
                $history->updated_fields = [
                    'updated' => $updated,
                    'original' => $original_arr
                ];

                if ($user->is_vendor) {
                    $user = User::first();
                }

                $history->user_id = ($user) ? $user->id : 1;
                $history->sale_id = $sale->id;
                $history->save();

                $order_user = User::withTrashed()->find($sale->user_id);

                $zone_exists = Zone::where('ou_id', $order_user->ou_id)->where('default_zone', true)->exists();
                // $service = new Service();
                // $charge->charge_service($sale->id, $sale->getDirty('status'));
                $statuses = ['Delivered', 'Returned', 'Cancelled', 'Scheduled'];


                $statuses_2 = ['In Transit', 'Scheduled', 'Pending', 'Dispatched'];

                if (in_array($sale->delivery_status, $statuses_2)) {

                    AutomationJob::dispatch($sale->id, $updated, 'sales', 'Edit', $sale->ou_id);
                }
                if ($zone_exists) {
                    // ServiceJob;
                    // $id, $old_status, $vendor_id

                    if (in_array($sale->delivery_status, $statuses)) {
                        ServiceJob::dispatch($sale->id, $sale->getOriginal('delivery_status'), $sale->seller_id);
                    }
                    // $service->charge_service($sale->id, $sale->getOriginal('delivery_status'), $sale->seller_id);
                }


                if ($sale->status == 'Returned' || $sale->status == 'Delivered') {
                    $invoice = new Invoice();
                    $invoice->create_invoice($sale->id);
                }


                // $history->user_id = ($user) ? $user->id : 1;
                // $history->action = 'Order updated';
                // $history->order_id = $sale->id;
                // $history->update_instructions = $sale->instructions;
                // $reference_no = IdGenerator::generate(['table' => 'order_histories', 'field' => 'reference_no', 'length' => 15, 'prefix' => 'REF_']);
                // $history->reference_no = $reference_no;
                // $history->reference_no = $reference_no->OrdReferenceNo();
                // $history->save();
                // Log::debug($sale->isDirty('delivery_status'));


                // $webhook = Webhook::where('vendor_id', $sale->seller_id)->first();

                // if ($webhook) {
                //     WebhookCall::create()
                //         ->url($webhook->url)
                //         ->payload($sale->toArray())
                //         // ->useSecret('sign-using-this-secret')
                //         ->dispatch();
                // }


                if ($sale->platform == 'Shopify') {
                    $shopify = new ShopifyService;

                    if ($sale->delivery_status == 'Delivered') {
                        // Fulfill order in shopify
                        $shopify->updateFulfillment($sale->id);
                    } else {
                        // $tracking_comment = 'Order updated to Inprogress';
                        $shopify->updateOrder($sale->id, $tracking_comment);
                    }
                }


                // Replace the direct webhook call with our job
                // $webhook = Webhook::where('vendor_id', $sale->seller_id)->first();
                // if ($webhook) {
                //     WebhookCall::create()
                //         ->url($webhook->url)
                //         ->payload($sale->toArray())
                //         ->useSecret('sign-using-this-secret')
                //         ->dispatch();
                // }
            }
        });

        // Sale::setEventDispatcher($dispatcher);
    }

    /**
     * Handle the sale "deleted" event.
     *
     * @param  \App\model\Sale  $sale
     * @return void
     */
    public function deleted(Sale $sale)
    {
        $user = $this->logged_user();
        // AutomationJob::dispatch($sale->id, $sale->getDirty(), 'Delete');

        $history  = new OrderHistory();
        $history->action = 'Order deleted';
        $reference_no = IdGenerator::generate(['table' => 'order_histories', 'field' => 'reference_no', 'length' => 15, 'prefix' => 'REF_']);
        $history->comment = $sale->comment;
        $history->update_comment = 'Order <b>deleted</b>';
        $history->user_name = $user->name;
        $history->reference_no = $reference_no;
        $history->user_id = $user->id;
        $history->sale_id = $sale->id;
        $history->save();

        // Dispatch webhook job for order deletion
        $webhook = Webhook::where('vendor_id', $sale->seller_id)->first();
        if ($webhook) {
            WebhookDispatchJob::dispatch($sale, 'order_deleted', []);
        }
    }

    /**
     * Handle the sale "restored" event.
     *
     * @param  \App\model\Sale  $sale
     * @return void
     */
    public function restored(Sale $sale)
    {
        $user = $this->logged_user();
        $history  = new OrderHistory();
        $history->action = 'Order restored';
        $reference_no = IdGenerator::generate(['table' => 'order_histories', 'field' => 'reference_no', 'length' => 15, 'prefix' => 'REF_']);
        $history->comment = $sale->comment;
        $history->update_comment = 'Order <b>restored</b>';
        $history->user_name = $user->name;
        $history->reference_no = $reference_no;
        $history->user_id = $user->id;
        $history->sale_id = $sale->id;
        $history->save();
    }

    /**
     * Handle the sale "force deleted" event.
     *
     * @param  \App\model\Sale  $sale
     * @return void
     */
    public function forceDeleted(Sale $sale)
    {
        $user = $this->logged_user();
        $history  = new OrderHistory();
        $history->action = 'Order deleted';
        $reference_no = IdGenerator::generate(['table' => 'order_histories', 'field' => 'reference_no', 'length' => 15, 'prefix' => 'REF_']);
        $history->comment = $sale->comment;
        $history->update_comment = 'Order <b>Forced deleted</b>';
        $history->user_name = $user->name;
        $history->reference_no = $reference_no;
        $history->user_id =  $user->id;
        $history->sale_id = $sale->id;
        $history->save();
    }
}
