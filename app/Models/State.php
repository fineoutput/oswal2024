<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;

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
        return $this->hasMany(Type::class);
    }

    public function shipingPrice() {

        return $this->hasmany(ShippingCharge::class , 'state_id', 'id');
    }
}
