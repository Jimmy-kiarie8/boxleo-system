<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AppCustom extends Model
{
    public function setConditionsAttribute($value)
    {
        $this->attributes['conditions'] = serialize($value);
    }
    public function setOrderAttribute($value)
    {
        $this->attributes['order'] = serialize($value);
    }
    public function setColumnsAttribute($value)
    {
        $this->attributes['columns'] = serialize($value);
    }

    public function getOrderAttribute($value)
    {
        if ($value) {
            return unserialize($value);
        }
    }
    public function getColumnsAttribute($value)
    {
        return unserialize($value);
    }
    public function getConditionsAttribute($value)
    {
        return unserialize($value);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AppCustom  $appCustom
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $app_custom = AppCustom::find($id);
        // dd($app_custom);

        $this->custom_view($app_custom);

        // return $app_custom;
        // DB::enableQueryLog();Log(); // Enable query log
        // $query = DB::table('sales');

        // DB::enableQueryLog();
        $sale_item = Sale::with(['products']);
        $conditions = $app_custom->conditions;
        $sale_item->where(function ($query) use ($conditions) {
            // $query = Sale::setEagerLoads([]);
            foreach ($conditions as $filter) {
                if ($filter['row']['Type'] == 'tinyint(1)') {
                    if ($filter['value'] == 'true') {

                        $filter['value'] = true;
                    } elseif ($filter['value'] == 'false') {
                        $filter['value'] = false;
                    }
                    // booleanValue
                    // dd(($filter['row']['Type']));
                }
                $column = Str::snake($filter['row']['label']);
                $operator = $filter['operator'];
                $row_value = $filter['value'];
                $op = '';
                // return $filter['condition'] == 'is';

                if ($filter['condition'] == 'is') {
                    // dd('dwdw');
                    $op = '=';
                } elseif ($filter['condition'] == 'is not') {
                    $op = '!=';
                } elseif ($filter['condition'] == 'is greater than') {
                    $op = '>';
                } elseif ($filter['condition'] == 'is greater than or equal to') {
                    $op = '>=';
                } elseif ($filter['condition'] == 'is less than') {
                    $op = '<';
                } elseif ($filter['condition'] == 'is less than or equal to') {
                    $op = '<=';
                } elseif ($filter['condition'] == 'contains') {
                    $op = 'LIKE';
                } elseif ($filter['condition'] == "doesn't contain") {
                    $op = 'NOT LIKE';
                } elseif ($filter['condition'] == 'is in') {
                    $op = 'whereIn';
                } elseif ($filter['condition'] == 'is not in') {
                    $op = 'whereNotIn';
                }

                if ($operator == 'OR') {
                    if ($op == 'LIKE' || $op == 'NOT LIKE') {
                        $query->orWhere($column, $op, "%{$row_value}%");
                    } elseif ($op == 'whereIn') {
                        $query->orWhereIn($column, [$row_value]);
                    } elseif ($op == 'whereNotIn') {
                        $query->orWhereNotIn($column, [$row_value]);
                    } else {
                        $query->orWhere($column, $op, $row_value);
                    }
                } else {
                    if ($op == 'LIKE' || $op == 'NOT LIKE') {
                        $query->where($column, $op, "%{$row_value}%");
                    } elseif ($op == 'whereIn') {
                        $query->whereIn($column, [$row_value]);
                    } elseif ($op == 'whereNotIn') {
                        $query->whereNotIn($column, [$row_value]);
                    } else {
                        $query->where($column, $op, $row_value);
                    }
                    // $query->where($column, $op, $row_value);
                }
            }
        });

        // dd($query);
        // dd(DB::getQueryLog()); // Show results of log

        $sales = $sale_item->paginate(200);
        // dd(DB::getQueryLog()); // Show results of log
        $sale_transform = new Sale;
        $sales = $sale_transform->transform_sales($sales);
        // $sales = $sales->


        $fields = $app_custom->columns;
        $labels = $app_custom->columns;

        // $more = ['actions'];
        // $labels = array_merge($labels, $more);
        // $fields = array_merge($labels, $more);

        $merged = collect($labels)->zip($fields)->transform(function ($values) {
            $val = ($values[0]['field']);
            // $val = (array_key_exists(0, $values)) ? $values[0] : '';
            return [
                'text' => Str::ucfirst(str_replace('_', ' ', $val)),
                'value' => $val
            ];
        });


        $myObject = new AutoGenerate;

        $myObject->checked = true;
        $myObject->value = "actions";
        $myObject->text = "Action";

        $merged[] = $myObject;

        return response()->json([
            'sales' => $sales,
            'columns' => $merged
        ], 200);
    }
    
    public function custom_view($data)
    {
        return CustomView::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'app_view' => true
            ],
            [
                // 'columns' => $columns,
                // 'order' => $order,
                // 'labels' => $labels,
                // 'user_id' => Auth::id(),
                'app_view' => true,
                'user_id' => Auth::id(),
                'app_custom_id' => $data['id'],
                'conditions' => $data['conditions'],
                'columns' => $data['columns'],
                'model' => 'Sales',
                'order' => $data['order'],
                'labels' => $data['columns']
            ]
        );
    }
}
