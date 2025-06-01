<?php

namespace App\Jobs;

use App\Http\Controllers\CustomViewController;
use App\Http\Controllers\MailableController;
use App\Mail\AutomaticMail;
use App\Models\Automation;
use App\Models\Client;
use App\Models\Sale;
use App\Models\settings\Mailable;
use App\Models\Sms;
use App\Models\SmsTemplate;
use App\Models\User;
use App\Seller;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;


class AutomationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 60;

    public $id, $updated_fields, $model, $action, $ou_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id, $updated_fields, $model, $action, $ou_id)
    {
        $this->id = $id;
        $this->updated_fields = $updated_fields;
        $this->action = $action;
        $this->model = $model;
        $this->ou_id = $ou_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->action();
    }

    public function action()
    {

        Sale::withoutEvents(function () {
            $auto_mail = new Automation();
            $id = $this->id;
            $updated_fields = array_keys($this->updated_fields);
            

            // $updated_fields = ['delivery_status', 'status'];
            $auto = new Automation();
            $automations = $auto->where('ou_id', $this->ou_id)->where('model', $this->model)->where('trigger_when', '!=', 5)->get();

            if (count($automations) < 1) {
                return;
            }

            Log::info('Automations', ['automations' => $automations]);

            foreach ($automations as $key => $automation) {
                if ($automation->trigger_when == '1') {
                    $trigger_when = 'Create';
                } elseif ($automation->trigger_when == '2') {
                    $trigger_when = 'Edit';
                } elseif ($automation->trigger_when == '3') {
                    $trigger_when = 'Edit/Create';
                } elseif ($automation->trigger_when == '4') {
                    $trigger_when = 'Deleted';
                }
                if ($this->action == $trigger_when) {
                    // $item_model = new $auto;
                    // $model = new $auto;
                    $model = $auto->getModelName($automation->model);

                    if ($automation->execute_when == '2') {
                        $selected_column = $automation->selected_column;
                        $containsSearch = !empty(array_intersect($selected_column, $updated_fields));
                    } elseif ($automation->execute_when == '3') {
                        $selected_column = $automation->selected_column;
                        $containsSearch = count(array_intersect($selected_column, $updated_fields)) == count($selected_column);
                    } else {
                        $containsSearch = true;
                    }

                    if ($containsSearch) {
                        // return 'no selected';

                        $conditions = $automation->conditions;
                        $actions = $automation->actions;

                        // DB::enableQueryLog(); // Enable query log

                        $table_model = new $model;
                        $table_model = $table_model->setEagerLoads([])->where('id', $id);

                        // $table_model = $table_model->where(function($query) use($id) {
                        //     $query->where('id', $id);
                        // });

                        // DB::enableQueryLog(); // Enable query log

                        $table_model = $table_model->where(function ($query) use ($conditions) {
                            foreach ($conditions as $condition) {
                                if ($condition['row']['Type'] == 'tinyint(1)') {
                                    $condition['value'] = ($condition['value'] == 'true') ? true : false;
                                }
                                if ($condition['operator'] == 'When' || $condition['operator'] == 'AND') {
                                    // dd($condition);
                                    $query->where($condition['row']['Field'], $condition['condition'], $condition['value']);
                                } else {
                                    // dd($condition);
                                    $query->orWhere($condition['row']['Field'], $condition['condition'], $condition['value']);
                                }
                            }
                        });



                        /*$table_model = $table_model->where(function ($query) use ($conditions) {
                        foreach ($conditions as $condition) {
                            if ($condition['row']) {
                                if ($condition['row']['Type'] == 'tinyint(1)') {
                                    $condition['value'] = ($condition['value'] == 'true') ? true : false;
                                }
                                if ($condition['operator']) {
                                    if ($condition['operator'] == 'When' || $condition['operator'] == 'AND') {
                                        // dd($condition);
                                        $query->where($condition['row']['Field'], $condition['condition'], $condition['value']);
                                    } else {
                                        // dd($condition);
                                        $query->orWhere($condition['row']['Field'], $condition['condition'], $condition['value']);
                                    }
                                }
                            }
                        }
                    });*/




                        $table_model = $table_model->exists();


                        // dd(DB::getQueryLog()); // Show results of log

                        if ($table_model) {
                            // return 'no action';
                         

                            // return 'success';
                            foreach ($actions as $action) {
                                if ($action['action'] == 'Send Email') {
                                    // $mail = new MailableController;
                                    $auto_mail->auto_mail($id, $action['mailable_id'], 'mail', $this->ou_id);
                                } elseif ($action['action'] == 'Send SMS') {
                                    Log::info('Sending SMS');
                                    $auto_mail->auto_mail($id, $action['smstemplate_id'], 'sms', $this->ou_id);
                                } elseif ($action['action'] == "Update Order") {
                                    $order = Sale::find($id);
                                    if ($action['update_field']['Type'] == 'datetime' || $order->{$action['update_field']['Type']} == 'date') {
                                        $order->{$action['update_field']['Field']} = Carbon::parse($order->{$action['update_field']['Field']})->addDays((int)$action['value']);
                                    } else {
                                        $order->{$action['update_field']['Field']} = $action['value'];
                                    }
                                    $order->saveQuietly();
                                    //    $order->update([$action['update_field']['Field'] => $action['value']]);
                                }
                            }
                        } else {
                        }
                    } else {
                    }
                }
            }
            // return $table_model;
        });

        // $dispatcher = Sale::getEventDispatcher();
        // Sale::unsetEventDispatcher();

        // Sale::setEventDispatcher($dispatcher);

    }

    public function send_sms($id, $smstemplate_id)
    {
        return SmsTemplate::find($smstemplate_id);
    }
}
