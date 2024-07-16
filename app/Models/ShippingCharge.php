<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingCharge extends Model
{
    use HasFactory;

    protected $fillable = [
        'state_id',
        'city_id',
        'weight1',
        'shipping_charge1',
        'weight2',
        'shipping_charge2',
        'weight3',
        'shipping_charge3',
        'weight4',
        'shipping_charge4',
        'weight5',
        'shipping_charge5',
        'weight6',
        'shipping_charge6',
        'ip',
        'date',
        'added_by',
        'is_active',
    ];

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id', 'id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    public function deleteShippingCharge()
    {
       return $this->delete();
    }

    public function updateStatus($status)
    {
        $this->is_active = $status;
        return $this->save();
    }
}
