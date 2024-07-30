<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'all_cities';

    protected $fillable = [
        'city_name',
        'state_id',
    ];

    
    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function type()
    {
        return $this->hasMany(Type::class ,'city_id' ,'id');
    }

    public function shipingPrice() {

        return $this->hasOne(ShippingCharge::class, 'city_id', 'id');

    }

    public function address() {

        return $this->hasMany(Address::class, 'city', 'id');
    }
}
