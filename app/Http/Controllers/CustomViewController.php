<?php

namespace App\Http\Controllers;

use App\Models\AppCustom;
use App\Models\AutoGenerate;
use App\Models\CustomView;
use App\Models\Report;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CustomViewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return CustomView::all();
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

        $columns = [];
        $labels = [];
        $order = [];

        foreach ($request->all() as $item) {
            if ($item['checked']) {
                $columns[] = Str::snake($item['field']);
                $labels[] = $item['label'];
            }
            $order[] = Str::snake($item['field']);
        }
        // return $order;

        $custom = CustomView::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'app_view' => false
            ],
            [
                'columns' => $columns,
                'order' => $order,
                'labels' => $labels,
                'user_id' => Auth::id(),
                'model' => 'Sale'
            ]
        );

        return $custom;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CustomView  $customView
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return AppCustom::select('id')->find($id);
        // return CustomView::where('user_id', Auth::id())->where('app_view', true)->first('id');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CustomView  $customView
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CustomView $customView)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CustomView  $customView
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomView $customView)
    {
        //
    }

    public function table_column(Request $request)
    {
        $id = $request->id;


        // $columns = Schema::getColumnListing('sales'); // users table
        // $columns = Schema::getColumnListing('sales'); // users table
        $custom = CustomView::where('user_id', Auth::id())->where('app_view', 0)->first();

        $show_columns = [
            "total_price",
            "sub_total",
            "order_no",
            "sku_no",
            "customer_notes",
            "discount",
            "shipping_charges",
            "charges",
            "delivery_date",
            "status",
            "delivery_status",
            "warehouse_id",
            "payment_id",
            "terms",
            "is_return_waiting_for_approval",
            "is_salesreturn_allowed",
            "is_emailed",
            "is_cancel_item_waiting_for_approval",
            "confirmed",
            "delivered",
            "returned",
            "cancelled",
            "invoiced",
            "packed",
            "printed",
            "paid",
            "return_count",
            "delivered_on",
            "returned_on",
            "cancelled_on",
            "user_id",
            "created_at",
            "created_by",
            "client_name",
            "client_address",
            "client_email",
            "client_phone",
            "product_name"
        ];
        // $custom = CustomView::where('user_id', Auth::id())->first();
        if ($custom) {
            $columns = $custom->order;

            return collect($columns)->transform(function ($values) use ($custom, $id) {
                $selected = [];
                if ($id) {
                    $selected = Report::find($id);
                }
                return [
                    'checked' => (in_array($values, $custom->columns) || in_array($values, $selected)) ? true : false,
                    // 'field' => Str::ucfirst(str_replace('_', ' ', $values['field'])),
                    // 'label' => Str::ucfirst(str_replace('_', ' ', $values['label']))

                    'field' => Str::ucfirst(str_replace('_', ' ', $values)),
                    'label' => Str::ucfirst(str_replace('_', ' ', $values))
                ];
            });
        } else {
            $columns = new Sale();
            // return $columns = $columns->columns();
            $columns = $show_columns;
            return collect($columns)->transform(function ($values) use ($id) {
                if ($id) {
                    $selected = Report::find($id)->toArray();
                    $columns = $selected['columns'];
                } else {
                    $columns = [];
                }
                // dd($selected['columns']);
                // dd(in_array($values, $custom->columns));
                return [
                    'checked' => (in_array($values, $columns)) ? true : false,
                    // 'checked' => false,
                    'field' => Str::ucfirst(str_replace('_', ' ', $values)),
                    'label' => Str::ucfirst(str_replace('_', ' ', $values))
                ];
            });
        }
    }

    public function table_rows($table)
    {

        // if ($table == 'sales') {
        //     $columns = new Sale();
        //     $columns = $columns->columns();
        //     return collect($columns)->transform(function ($values) {
        //         // dd(in_array($values, $custom->columns));
        //         return [
        //             'checked' => false,
        //             'field' => Str::ucfirst(str_replace('_', ' ', $values)),
        //             'label' => Str::ucfirst(str_replace('_', ' ', $values))
        //         ];
        //     });
        // } else {
        $rows = DB::select('describe ' . $table);

        if ($table == 'users') {
            foreach ($rows as $key => $row) {
                // dd($row);
                if ($row->Field == 'id' || $row->Field == 'two_factor_recovery_codes' || $row->Field == 'last_login' || $row->Field == 'first_login' || $row->Field == 'drawer_open' || $row->Field == 'company_id' || $row->Field == 'current_team_id' || $row->Field == 'profile_photo_path' || $row->Field == 'deleted_at' || $row->Field == 'remember_token' || $row->Field == 'created_at' || $row->Field == 'updated_at' || $row->Field == 'two_factor_secret') {
                    unset($rows[$key]);
                }
            }
            // return $rows;
        } else {


            // }
            // $myArray = [];
            // foreach ($rows as $result) {

            $myObject = new AutoGenerate;

            $myObject->Field = "client_name";
            $myObject->Type = "varchar(255)";
            $myObject->Null = "YES";
            $myObject->Key = "";
            $myObject->Default = null;
            $myObject->Extra = "";


            $rows[] = $myObject;
            $myObject = new AutoGenerate;


            $myObject->Field = "client_address";
            $myObject->Type = "varchar(255)";
            $myObject->Null = "YES";
            $myObject->Key = "";
            $myObject->Default = null;
            $myObject->Extra = "";


            $rows[] = $myObject;
            $myObject = new AutoGenerate;


            $myObject->Field = "client_email";
            $myObject->Type = "varchar(255)";
            $myObject->Null = "YES";
            $myObject->Key = "";
            $myObject->Default = null;
            $myObject->Extra = "";


            $rows[] = $myObject;
            $myObject = new AutoGenerate;

            $myObject->Field = "client_phone";
            $myObject->Type = "varchar(255)";
            $myObject->Null = "YES";
            $myObject->Key = "";
            $myObject->Default = null;
            $myObject->Extra = "";


            $rows[] = $myObject;
            $myObject = new AutoGenerate;

            $myObject->Field = "product_name";
            $myObject->Type = "varchar(255)";
            $myObject->Null = "YES";
            $myObject->Key = "";
            $myObject->Default = null;
            $myObject->Extra = "";

            $rows[] = $myObject;


            $myObject = new AutoGenerate;

            $myObject->Field = "driver_name";
            $myObject->Type = "varchar(255)";
            $myObject->Null = "YES";
            $myObject->Key = "";
            $myObject->Default = null;
            $myObject->Extra = "";
            $rows[] = $myObject;



            $myObject = new AutoGenerate;

            $myObject->Field = "driver_phone";
            $myObject->Type = "varchar(255)";
            $myObject->Null = "YES";
            $myObject->Key = "";
            $myObject->Default = null;
            $myObject->Extra = "";
            $rows[] = $myObject;
            // }
            // return $rows;

            // return $rows;
            // $rows = Schema::getColumnListing($table);
            $rows_arr = [];
            $rows_a = [];
        }
        foreach ($rows as $key => $value) {
            $value->key = $key;
            $value->label = Str::ucfirst(str_replace('_', ' ', $value->Field));
            // dd($value->key);
            // $rows_arr['label'] = Str::ucfirst(str_replace('_', ' ', $value['Field']));
            // $rows_arr['key'] = $key;

            // $rows_a[] = $rows_arr;
        }
        return $rows;
    }

    public function get_views(Request $request)
    {
    }

    public function custom_view(Request $request)
    {
        return $request->all();
        $conditions = $request->conditions;
        $columns = $request->data;
    }
}
