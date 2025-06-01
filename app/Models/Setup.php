<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setup extends Model
{
    use HasFactory;

    public function getSetup($column)
    {
        $setup = Setup::first();
        return $setup->{$column};
    }

    public function updateSetup($column)
    {
        $setup = Setup::first();
        $setup->{$column} = true;
        $setup->save();
        return $setup;
    }
}
