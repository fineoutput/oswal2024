<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class OrderDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tbl_order2';

    protected $fillable = [
        'main_id',
        'product_id',
        'type_id',
        'type_mrp',
        'gst',
        'gst_percentage_price',
        'quantity',
        'combo_gst',
        'combo_product',
        'combo_name',
        'combo_type',
        'amount',
        'ip',
        'date'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'main_id');
    }

    public function product()
    {
        return $this->belongsTo(EcomProduct::class, 'product_id');
    }

    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id');
    }

    public function vendortype()
    {
        return $this->belongsTo(VendorType::class, 'type_id');
    }
}
