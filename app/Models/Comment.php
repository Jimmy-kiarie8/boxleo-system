<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class Comment extends Model
{
    protected $fillable = [
        'comment', 'sale_id', 'user_id',
    ];
    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }


    public function getCreatedAtAttribute($value)
    {
        $timezone = optional(auth()->user())->timezone ?? config('app.timezone');
        return Carbon::parse($value)->timezone($timezone)->format('D d M Y H:i');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function comment($data, $id)
    {
        $history  = new OrderHistory();
        $history->action = 'Commented';
        $reference_no = IdGenerator::generate(['table' => 'order_histories', 'field' => 'reference_no', 'length' => 15, 'prefix' => 'REF_']);
        $history->comment = $data['comment'];
        $history->update_comment = 'A comment added';
        $history->reference_no = $reference_no;
        $history->user_name = Auth::user()->name;

        $history->user_id = Auth::id();
        $history->sale_id = $id;
        $history->save();
    }

}
