<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $table = 'user_address';

    protected $fillable = [
        'device_id',
        'user_id',
        'name',
        'address',
        'landmark',
        'doorflat',
        'latitude',
        'longitude',
        'location_address',
        'city',
        'state',
        'zipcode',
        'date',
        'updated_date'
    ];

    // Define relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'address_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state');
    }
}
