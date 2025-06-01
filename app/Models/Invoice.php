<?php

namespace App\Models;

use Carbon\Carbon;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class Invoice extends Model
{
    protected $fillable = ['sale_id', 'client_id', 'balance', 'total', 'user_id', 'invoice_no', 'due_date', 'status', 'paid', 'shipment_charges'];

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }

    public function getCreatedAtAttribute($value)
    {
        $timezone = optional(auth()->user())->timezone ?? config('app.timezone');
        return Carbon::parse($value)->timezone($timezone)->format('D d M Y H:i');
    }

    public function create_invoice($id)
    {
        $sale = Sale::find($id);
        // dd($sale);
        $invoice = Invoice::create(
            [
                'sale_id' => $id,
                'client_id' =>  $sale->client_id,
                'balance' => $sale->sub_total,
                'total' => $sale->sub_total,
                'shipment_charges' => $sale->shipping_charges,
                'user_id' => (Auth::check()) ? Auth::id() : 1,
                'invoice_no' => IdGenerator::generate(['table' => 'invoices', 'field' => 'invoice_no', 'length' => 8, 'prefix' => 'INV_']),
                'due_date' => now()
            ]
        );

        $sale->invoiced = true;
        $sale->history_comment = 'Order invoiced';
        $sale->save();

        return $invoice;
    }
    public function invoice($data)
    {
        $invoice = Invoice::create($data);
        return $invoice;
    }
    public static function booted()
    {
        static::addGlobalScope('created_at', function (Builder $builder) {
            $builder->orderBy('id', 'DESC');
        });
    }

}
