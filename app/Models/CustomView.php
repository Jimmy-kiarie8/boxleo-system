<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class CustomView extends Model
{
    protected $fillable = ['user_id', 'columns', 'labels', 'user_id', 'model', 'order', 'app_view'];

    public function setColumnsAttribute($value)
    {
        $this->attributes['columns'] = serialize($value);
    }

    public function setLabelsAttribute($value)
    {
        $this->attributes['labels'] = serialize($value);
    }

    public function setOrderAttribute($value)
    {
        $this->attributes['order'] = serialize($value);
    }

    public function setConditionsAttribute($value)
    {
        $this->attributes['conditions'] = serialize($value);
    }

    public function getCreatedAtAttribute($value)
    {
        $timezone = optional(auth()->user())->timezone ?? config('app.timezone');
        return Carbon::parse($value)->timezone($timezone)->format('D d M Y H:i');
    }

    public function getConditionsAttribute($value)
    {
        return unserialize($value);
    }
    public function getOrderAttribute($value)
    {
        return unserialize($value);
    }

    public function getColumnsAttribute($value)
    {
        return unserialize($value);
    }

    public function getLabelsAttribute($value)
    {
        return unserialize($value);
    }
}
