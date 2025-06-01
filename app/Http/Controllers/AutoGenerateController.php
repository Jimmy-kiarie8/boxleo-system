<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class AutoGenerateController extends Controller
{
    public function auto_generate(Request $request)
    {
        $table = $request->table;
        $prefix = $request->prefix;
        $column = $request->column;
        return IdGenerator::generate(['table' => $table, 'field' => $column, 'length' => 15, 'prefix' => $prefix]);
    }

    public function unique_sku()
    {
        
    }
}
