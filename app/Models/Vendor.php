<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'vendors';

    protected $fillable = [
        'user_id',
        'shopname',
        'pincode',
        'city_id',
        'state_id',
        'address',
        'addhar_front_image',
        'addhar_back_image',
        'gstno',
        'shop_code',
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id' , 'id');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id' , 'id');
    }
    
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id' , 'id');
    }
}
