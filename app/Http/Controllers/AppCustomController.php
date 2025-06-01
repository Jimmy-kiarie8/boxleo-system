<?php

namespace App\Http\Controllers;

use App\Models\AppCustom;
use App\Models\CustomView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AppCustomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return AppCustom::all();
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
        // $selected_columns = [];
        // foreach ($request->selected_columns as $key => $value) {
        //     $selected_columns['field'] = $value['label'];
        //     $selected_columns['label'] = $value['label'];
        // }
        // return $request->all();

        $selected_columns = collect($request->selected_columns)->transform(function ($values) {
            return [
                'checked' => true,
                'field' => Str::snake($values['label']),
                'label' => $values['label']
            ];
        })->toArray();
        // return serialize($selected_columns);

        // return $request->all();
        $app_custom = new AppCustom();

        $app_custom->name = $request->name;
        $app_custom->conditions = $request->conditions;
        $app_custom->columns = $selected_columns;
        $app_custom->order = $selected_columns;
        $app_custom->user_id = Auth::id();
        $app_custom->app_view = true;
        $app_custom->save();
        return $app_custom;
    }

    public function show($id)
    {
        $app_custom = new AppCustom();
        return $app_custom->show($id);
    }




    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AppCustom  $appCustom
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        // return $request->all();

        $selected_columns = collect($request->selected_columns)->transform(function ($values) {
            return [
                'checked' => true,
                'field' => Str::snake($values['label']),
                'label' => $values['label']
            ];
        })->toArray();
        // return serialize($selected_columns);

        // return $request->all();
        $app_custom = AppCustom::find($id);

        $app_custom->name = $request->name;
        $app_custom->conditions = $request->conditions;
        $app_custom->columns = $selected_columns;
        $app_custom->order = $selected_columns;
        $app_custom->user_id = Auth::id();
        $app_custom->app_view = true;

        $app_custom->save();
        return $app_custom;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AppCustom  $appCustom
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function cusom_edit($id)
    {
        return  AppCustom::find($id);
    }
}
