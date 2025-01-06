<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class OrderRatings extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'order_ratings';

   protected $fillable = [
        'user_id',
        'device_id',
        'order_id',
        'rating',
        'description',
        'description_hi',
        'ip',
        'date'
    ];

    // Define relationships
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'main_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id' ,'id');
    }

    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id' ,'id');
    }

    public function gift()
    {
        return $this->belongsTo(GiftCard::class, 'gift_id');
    }

    public function gift1()
    {
        return $this->belongsTo(GiftCardSec::class, 'gift1_id');
    }

    public function rating()
    {
        return $this->belongsTo(Order::class, 'id' ,'order_id');
    }

    public function promocodes()
    {
        return $this->belongsTo(Promocode::class, 'promocode');
    }

    public function invoices()
    {
        return $this->hasOne(OrderInvoice::class, 'order_id');
    }

    public function transferOrder()
    {
        return $this->hasOne(TransferOrder::class, 'order_id' , 'id');
    }
    
}
