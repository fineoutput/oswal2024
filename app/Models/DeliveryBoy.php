<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;

class DeliveryBoy extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'delivery_boy';

    protected $fillable = [
        'device_token',
        'fcm_token',
        'name',
        'email',
        'role_type',
        'phone',
        'password',
        'address',
        'pincode',
        'latitude',
        'longitude',
        'longitude',
        'address',
        'ip',
        'date',
        'updated_date',
        'added_by',
        'is_active'
    ];

     /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    
    public function deliveryAmount() {

       return $this->hasMany(DeliveryAmount::class, 'deluser_id', 'id');

    }

    public function transferOrders()
    {
        return $this->hasMany(TransferOrder::class, 'delivery_user_id');
    }

    public function updateStatus($status) {
        
        $this->is_active = $status;

        return $this->save();
    }

 
}
