<?php

namespace App\Console\Commands;

use App\Models\Automation;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AutomationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'automation:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run date based automations';

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
        $auto_mail = new Automation();

        $automations = Automation::where('trigger_when', 5)->get();
        $auto = new Automation();
        // DB::enableQueryLog(); // Enable query log

        foreach ($automations as $key => $automation) {
            $model = $auto->getModelName($automation->model);
            $conditions = $automation->conditions;
            $actions = $automation->actions;


            $table_model = new $model;
            $table_model = $table_model->setEagerLoads([]);
            // $table_model = $table_model->setEagerLoads([])->where('id', $id);

            $table_model = $table_model->where(function ($query) use ($conditions) {
                foreach ($conditions as $condition) {
                    if ($condition['row']['Type'] == 'tinyint(1)') {
                        $condition['value'] = ($condition['value'] == 'true') ? true : false;
                        if ($condition['operator'] == 'When' || $condition['operator'] == 'AND') {
                            $query->where($condition['row']['Field'], $condition['condition'], $condition['value']);
                        } else {
                            $query->orWhere($condition['row']['Field'], $condition['condition'], $condition['value']);
                        }
                    } elseif ($condition['row']['Type'] == 'datetime' || $condition['row']['Type'] == 'timestamp') {
                        // dd($condition['value']);
                        $date = Carbon::today()->subDays($condition['value']);
                        $query->whereDate($condition['row']['Field'], $condition['condition'], $date);
                    } else {
                        if ($condition['operator'] == 'When' || $condition['operator'] == 'AND') {
                            $query->where($condition['row']['Field'], $condition['condition'], $condition['value']);
                        } else {
                            $query->orWhere($condition['row']['Field'], $condition['condition'], $condition['value']);
                        }
                    }
                }
            });

            $table_model = $table_model->get();
            // dd(DB::getQueryLog()); // Show results of log
            // dd($table_model);

            if ($table_model) {
                // return 'no action';

                // return 'success';
                foreach ($table_model as $order_update) {
                    foreach ($actions as $action) {
                        if ($action['action'] == 'Send Email') {
                            // $mail = new MailableController;
                            $auto_mail->auto_mail($order_update->id, $action['mailable_id'], 'mail', 1);
                        } elseif ($action['action'] == 'Send SMS') {
                            $auto_mail->auto_mail($order_update->id, $action['smstemplate_id'], 'sms', 1);
                        } elseif ($action['action'] == "Update Order") {
                            $order = Sale::find($order_update->id);
                            $order->{$action['update_field']['Field']} = $action['value'];
                            $order->saveQuietly();
                            //    $order->update([$action['update_field']['Field'] => $action['value']]);
                        }
                    }
                }
            }
        }
        // dd(DB::getQueryLog()); // Show results of log

        $this->info("Updated!");
    }
}
