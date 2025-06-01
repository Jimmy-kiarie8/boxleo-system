<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $table = 'transaction';

    
    public function mpesa($code)
    {
        $code_exists = Transaction::where('TransID', $code)->first();
        if (!$code_exists) {
            abort(422, 'Mpesa code not found in our system. Please check again');
        } elseif ($code_exists->code_used) {
            abort(422, 'Mpesa code has already been used. Please check again');
        } else {
            $code_exists->code_used = true;
            $code_exists->save();
        }
    }
    
    public function getCreatedAtAttribute($value)
    {
        $timezone = optional(auth()->user())->timezone ?? config('app.timezone');
        return Carbon::parse($value)->timezone($timezone)->format('D d M Y H:i');
    }

    /**
     * Get the sale that owns the Transaction
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class, 'BillRefNumber');
    }
}
