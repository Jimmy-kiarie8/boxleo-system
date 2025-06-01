<?php

namespace App\Http\Controllers;

use App\Models\Automation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AutomationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Automation::where('ou_id', Auth::user()->ou_id)->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request->all();

        // if($request->trigger_when == '1' || $request->trigger_when == '4')
        $data = $request->all();
        $data['ou_id'] = Auth::user()->ou_id;

        return Automation::create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Automation  $automation
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Automation::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Automation  $automation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return Automation::find($id)->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Automation  $automation
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function action($id)
    {
        $updated_fields = ['delivery_status', 'status'];

        $auto = new Automation;
        $automation = $auto->first();
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

        if(!$containsSearch) {
            return 'no selected';
        }


        $conditions = $automation->conditions;
        $actions = $automation->actions;


        $table_model = new $model;
        $table_model = $table_model->setEagerLoads([])->where('id', $id);

        // DB::enableQueryLog(); // Enable query log

        foreach ($conditions as $condition) {
            if ($condition['row']['Type'] == 'tinyint(1)') {
                $condition['value'] = ($condition['value'] == 'true') ? true : false;
            }

            if ($condition['operator'] == 'When' || $condition['operator'] == 'AND') {
                $table_model = $table_model->where($condition['row']['Field'], $condition['condition'], $condition['value']);
            } elseif ($condition['operator'] == 'OR') {
                $table_model = $table_model->orWhere($condition['row']['Field'], $condition['condition'], $condition['value']);
            }
        }

        $table_model = $table_model->exists();
        // dd(DB::getQueryLog()); // Show results of log

        if (!$table_model) {
            return 'no action';
        }

        return 'success';
        foreach ($actions as $action) {
            if ($action['action'] == 'Send Email') {
                $mail = new MailableController;
                $mail->testmail($id);
            } elseif ($action['action'] == 'Send SMS') {
            }
        }


        return $table_model;
    }
}
