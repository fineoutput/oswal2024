<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class State extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'all_states';

    protected $fillable = [
        'state_name',
    ];

    public function cities()
    {
        return $this->hasMany(City::class);
    }
    
    public function type() 
    {
        return $this->hasMany(Type::class ,'state_id' , 'id');
    }

    public function vendortype() 
    {
        return $this->hasMany(Type::class ,'state_id' , 'id');
    }

    public function shipingPrice() {

        return $this->hasOne(ShippingCharge::class , 'state_id', 'id');
    }

    public function address() {

        return $this->hasMany(Address::class, 'state', 'id');
    }

    public function vendor() {

        return $this->hasOne(vendor::class, 'state_id', 'id');
    }
}
