<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Promocode  extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'promocodes';

    protected $fillable = [
        'promocode',
        'percent',
        'type',
        'minimum_amount',
        'maximum_gift_amount',
        'expiry_date',
        'ip',
        'date',
        'added_by',
        'is_active',
    ];

    public function updateStatus($status) {
        
        $this->is_active = $status;

        return $this->save();
    }

    public function order()  {
        
      return $this->hasOne(Order::class , 'promocode');
    }
 
}
