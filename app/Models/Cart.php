<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'device_id',
        'user_id',
        'category_id',
        'product_id',
        'type_id',
        'type_price',
        'quantity',
        'total_qty_price',
        'checkout_status',
        'cart_from',
        'ip',
    ];

    public function category()
    {
        return $this->belongsTo(EcomCategory::class ,'category_id' ,'id');
    }

    public function product()
    {
        return $this->belongsTo(EcomProduct::class ,'product_id' ,'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id', 'id');
    }
}
