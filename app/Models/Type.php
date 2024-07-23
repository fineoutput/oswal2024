<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Type extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'category_id',
        'type_name',
        'type_name_hi',
        'del_mrp',
        'mrp',
        'state_id',
        'city_id',
        'gst_percentage',
        'gst_percentage_price',
        'selling_price',
        'weight',
        'rate',
        'ip',
        'date',
        'added_by',
        'is_active',
        'update_date'
    ];

    protected static function boot()
    {
       
        parent::boot();


        static::deleting(function (Type $type) {

            $type->giftcardsec()->delete();

            $type->comboproduct()->delete();

            $type->comboproduct2()->delete();
            
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
        return $this->belongsTo(State::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }    

    public function cart()  {
        return $this->hasMany(Cart::class ,'type_id' ,'id');
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
        return $this->hasMany(OrderDetail::class);
    }
}
