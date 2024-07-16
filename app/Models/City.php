<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

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
        return $this->hasMany(Type::class);
    }

    public function shipingPrice() {

        return $this->hasOne(ShippingCharge::class, 'city_id', 'id');

    }
}
