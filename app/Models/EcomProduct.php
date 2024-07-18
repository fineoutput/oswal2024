<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EcomProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id ', 'product_category_id ', 'name', 'long_desc', 'name_hi', 
        'long_desc_hi', 'url', 'url', 'hsn_code', 'video', 'img1', 'img2', 'img3', 'img4', 'img_app1', 'img_app2', 'img_app3', 'img_app4', 'is_active', 'is_cat_delete', 'cur_date', 'ip', 'added_by', 'is_hot'
    ];

    public function updateStatus(string $newStatus)
    
    {
        $this->is_active = $newStatus;

        return $this->save();
    }

    public function category()
    {
        return $this->belongsTo(EcomCategory::class);
    }


    public function isSoftDeleted()
    {
        return $this->is_cat_delete == 1;
    }

    public function type()  {

        return $this->hasOne(Type::class);

    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function offers()
    {
        return $this->hasMany(Offer::class, 'product_id');
    }

    public function offers2()
    {
        return $this->hasMany(Offer2::class, 'product_id');
    }

    public function sliders()
    {
        return $this->hasMany(Slider::class, 'product_id');
    }

    public function recent()
    {
        return $this->hasMany(Recent::class, 'product_id' , 'id');
    }

    public function trending()
    {
        return $this->hasMany(Trending::class, 'product_id' , 'id');
    }

    public function themetrending()
    {
        return $this->hasMany(ThemeTrending::class, 'product_id' , 'id');
    }

    public function giftcardsec()
    {
        return $this->hasMany(GiftCardSec::class, 'product_id' , 'id');
    }

    public function comboproduct() {
        
        return $this->hasMany(ComboProduct::class, 'product_id' , 'id');
        
    }
    public function comboproduct2() {
        
        return $this->hasMany(ComboProduct::class, 'main_product' , 'id');
        
    }
}
