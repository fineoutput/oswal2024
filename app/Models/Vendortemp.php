<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendortemp extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'vendor_temp';

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
        'is_active',
        'ip',
        'date',
        'added_by',
        'referral_code',
        'status',
        'password',
        'contact',
        'email',
        'auth',
        'device_id',
        'first_name_hi',
        'first_name',
        'shop_code',
        'role_type',
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
