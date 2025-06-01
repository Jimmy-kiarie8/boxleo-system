<?php

namespace App\Models\Warehouse;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asn extends Model
{
    use HasFactory;

    protected $fillable = ['asn_no', 'vendor_id', 'invoice_no', 'po_number', 'product_id', 'quantity', 'vehicle_no', 'weight'];

    public function create($data)
    {
        // dd($data['asn_no']);
        $asn = new Asn();
        $asn->create([
            'asn_no' => $data['asn_no'], 
            'vendor_id' => $data['vendor_id'], 
            'invoice_no' => $data['invoice_no'], 
            'po_number' => $data['po_number'], 
            'product_id' => $data['product_id'], 
            'quantity' => $data['quantity'],
            'vehicle_no' => $data['vehicle_no'],
            'weight' => $data['weight']
        ]);
    }
    
    public function update_data(Asn $asn, $data)
    {
        return $asn->update([
            'vendor_id' => $data['vendor_id'], 
            'invoice_no' => $data['invoice_no'], 
            'po_number' => $data['po_number'], 
            'product_id' => $data['product_id'], 
            'quantity' => $data['quantity'],
            'vehicle_no' => $data['vehicle_no'],
            'weight' => $data['weight']
        ]);
    }
}
