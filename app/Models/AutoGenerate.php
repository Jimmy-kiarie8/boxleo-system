<?php

namespace App\Models;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class AutoGenerate
{
    public function auto_generate($table, $column, $prefix)
    {
        return IdGenerator::generate(['table' => $table, 'field' => $column, 'length' => 15, 'prefix' => $prefix]);
    }
}
