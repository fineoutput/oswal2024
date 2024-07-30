<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable , SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'first_name_hi',
        'device_id',
        'auth',
        'email',
        'contact',
        'password',
        'image',
        'status',
        'is_hidden',
        'ip',
        'date',
        'added_by',
        'is_active',
    ];
 
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function carts()
    {
        return $this->hasMany(Cart::class, 'user_id', 'id');
    }

    public function userdevicetoken()
    {
        return $this->belongsTo(User::class, 'user_id' , 'id');
    }

    public function updateStatus($status) {
        
        $this->is_active = $status;

        return $this->save();
    }
    
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function invoices()
    {
        return $this->hasMany(OrderInvoice::class);
    }

    public function wishlist()
    {
        return $this->hasMany(Wishlist::class ,'user_id' , 'id');
    }
    
    public function rating()
    {
        return $this->hasMany(ProductRating ::class ,'user_id' , 'id');
    }
}
