<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Billingaddress extends Model
{
    protected $fillable = [
        'name', 'street_address', 'city', 'country', 'postal_code', 'phone', 'email', 'ou', 'client_id'
    ];

    /**
     * Get the client that owns the Shippingaddress
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function address($data)
    {
        Billingaddress::create($data);
    }
    public function address_update($id, $data)
    {
        Billingaddress::find($id)->update($data);
    }
}
