<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserActivity extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "user_activity";

    protected $fillable = [
        'ip_address',
        'status',
    ];

    protected static function boot()
    {
       
        parent::boot();


        static::deleting(function (VendorType $type) {

            $type->giftcardsec()->delete();

            $type->comboproduct()->delete();

            $type->comboproduct2()->delete();

            $type->wishlist()->delete();
            
        });
    }

    public function updateStatus(string $newStatus)
    
    {
        $this->is_active = $newStatus;

        return $this->save();

    }

    public function destory()
    {
        return $this->delete();
    }

    public function product()
    {
        return $this->belongsTo(EcomProduct::class ,'product_id' ,'id');
    }

    public function category()
    {
        return $this->belongsTo(EcomCategory::class ,'category_id' ,'id');
    }

    public function state()
    {
        return $this->belongsTo(State::class , 'state_id' , 'id');
    }

    public function city()
    {
        return $this->belongsTo(City::class ,'city_id' , 'id');
    }    

    public function cart()
    {
        return $this->hasMany(Cart::class ,'type_id' , 'id');
    }
    public function giftcardsec()
    {
        return $this->hasMany(GiftCardSec::class, 'type_id' , 'id');
    }

    public function comboproduct() {
        
        return $this->hasMany(ComboProduct::class, 'main_type' , 'id');
        
    }

    public function comboproduct2() {
        
        return $this->hasMany(ComboProduct::class, 'combo_type' , 'id');
        
    }

    public function orderDetails()
    {
        return $this->hasOne(OrderDetail::class , 'type_id');
    }

    public function wishlist()
    {
        return $this->hasMany(Wishlist::class ,'type_id' , 'id');
    }

    public function type_sub()
    {
    return $this->hasMany(Type_sub::class, 'type_id', 'id');
    }

}
