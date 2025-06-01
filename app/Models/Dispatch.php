<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dispatch extends Model
{
    use HasFactory;
    protected $fillable = [
        'dispatch_date',
        'vendor_name',
        'status',
        'warehouse_notes',
        'quality_checked',
        'quality_notes',
        'approval_level',
        'approval_notes',
        'waybill_no',
        'approval_notes2',
        'approval_notes3',
        'approval_notes4',
        'approval_notes5',
        'approval_notes6',
        'products',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}
