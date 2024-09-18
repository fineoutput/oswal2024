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
        'role_type',
        'first_name',
        'first_name_hi',
        'device_id',
        'auth',
        'fcm_token',
        'email',
        'contact',
        'password',
        'image',
        'status',
        'wallet_amount',
        'referral_code',
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
        return $this->hasMany(Order::class ,'user_id' ,'id');
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

    public function transactionhistory()
    {
        return $this->hasMany(WalletTransactionHistory::class ,'user_id' , 'id');
    }
    public function address()
    {
        return $this->hasMany(Address::class, 'user_id');
    }

    public function vendor()
    {
        return $this->hasOne(Vendor::class, 'user_id');
    }

    /**
     * Generate a unique referral code.
     *
     * @param int $length
     * @return string
     */

    public static function generateReferralCode($length = 5)
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        do {
            $referralCode = '';
            for ($i = 0; $i < $length; $i++) {
                $referralCode .= $characters[rand(0, strlen($characters) - 1)];
            }
        } while (self::where('referral_code', $referralCode)->exists());

        return $referralCode;
    }

}
