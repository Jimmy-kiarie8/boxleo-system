<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class OrderHistory extends Model
{
    use SoftDeletes;
    public $with = ['user'];
    // protected $attributes = array(
    //     'show_comment' => '',
    // );
    protected $appends = ['show_comment', 'color', 'step'];

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }

    public function getCreatedAtAttribute($value)
    {
        $timezone = optional(auth()->user())->timezone ?? config('app.timezone');
        return Carbon::parse($value)->timezone($timezone)->format('D d M Y H:i');
    }

    public function getColorAttribute($value)
    {
        $color = '';
        $value = $this->attributes['tracking_comment'];
        if ($value == 'Delivered') {
            $color = '#19d237';
        } else if ($value == 'Returned' || $value == 'Returned' || $value == 'Returned') {
            $color = '#f00';
        } else if ($value == 'In transit') {
            $color = '#445d98';
        } else if ($value == 'Order confirmed') {
            $color = '#1976d2';
        } else {
            $color = '#1976d2';
        }

        return $color;
    }

    public function getStepAttribute()
    {
        
        $value = $this->attributes['tracking_comment'];
        if ($value == 'Delivered') {
            $step = 5;
        } else if ($value == 'Returned' || $value == 'Returned' || $value == 'Returned') {
            $step = 0;
        } else if ($value == 'In transit') {
            $step = 3;
        } else if ($value == 'Order confirmed') {
            $step = 2;
        } else {
            $step = 1;
        }

        return $step;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getShowCommentAttribute()
    {
        return $this->update_comment . ' by <b style="color:red"> ' . $this->user_name . '</b>';
    }

    public function setUpdatedFieldsAttribute($value)
    {
        $this->attributes['updated_fields'] = serialize($value);
    }

    public function getUpdatedFieldsAttribute($value)
    {
        return unserialize($value);
    }
}
