<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsTemplate extends Model
{
    use HasFactory;

    protected $fillable = ['name','model','sms', 'recipient', 'ou_id'];


    public function getCreatedAtAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', Carbon::parse($date))->format('D d M Y');
    }
    public function setRecipientAttribute($value)
    {
        $this->attributes['recipient'] = serialize($value);
    }

    public function getRecipientAttribute($value)
    {
        return unserialize($value);
    }
}
