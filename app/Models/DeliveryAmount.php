<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryAmount extends Model
{
    use HasFactory;

    protected $table = 'delivery_amounts';

    protected $fillable = [
        'deluser_id',
        'amount',
        'payment_type',
        'ip',
        'date',
        'added_by',
        'is_active',
    ];

    public function deliveryBoy()
    {
        return $this->belongsTo(DeliveryBoy::class, 'deluser_id', 'id');
    }

}
