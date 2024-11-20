<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type_sub extends Model
{
    use HasFactory;
    protected $fillable = [
        'start_range',
        'end_range',
        'mrp',
        'gst_percentage',
        'gst_percentage_price',
        'selling_price',
        'selling_price_gst',
        'weight',
        'rate',
        'type_id',
    ];
    // If required, define reverse relation
    public function vendortype()
    {
    return $this->belongsTo(VendorType::class, 'type_id', 'id');
    }

}
