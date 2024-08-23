<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferOrder extends Model
{
    use HasFactory;

    protected $table = 'transfer_orders';

    protected $fillable = [
        'order_id',
        'delivery_user_id',
        'status',
        'payment_type',
        'image',
        'start_location',
        'start_time',
        'end_location',
        'end_time',
        'ip',
        'date',
        'added_by',
    ];

    public $timestamps = false;

    public function deliveryBoy()
    {
        return $this->belongsTo(DeliveryBoy::class, 'delivery_user_id');
    }

    public function Orders()
    {
        return $this->belongsTo(Order::class, 'order_id' ,'id');
    }
}
