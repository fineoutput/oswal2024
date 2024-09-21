<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EcomProduct extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id ', 'product_category_id ', 'name', 'long_desc', 'name_hi', 
        'long_desc_hi', 'url', 'url', 'hsn_code', 'video', 'img1', 'img2', 'img3', 'img4', 'img_app1', 'img_app2', 'img_app3', 'img_app4', 'is_active', 'is_cat_delete', 'cur_date', 'ip', 'added_by', 'is_hot', 'is_featured', 'product_view'
    ];

    public function updateStatus(string $newStatus)
    
    {
        $this->is_active = $newStatus;

        return $this->save();
    }

    public function category()
    {
        return $this->belongsTo(EcomCategory::class, 'category_id', 'id');
    }

    protected static function boot()
    {
       
        parent::boot();


        static::deleting(function (EcomProduct $ecomProduct) {

            $ecomProduct->type()->delete();

            $ecomProduct->cart()->delete();
                
            $ecomProduct->offers()->delete();
            
            $ecomProduct->offers2() ->delete();
            
            $ecomProduct->sliders()->delete();
            
            $ecomProduct->recent()->delete();

            $ecomProduct->trending()->delete();

            $ecomProduct->themetrending()->delete();

            $ecomProduct->giftcardsec()->delete();

            $ecomProduct->comboproduct()->delete();

            $ecomProduct->comboproduct2()->delete();

            // $ecomProduct->footerimages()->delete();

            $ecomProduct->wishlist()->delete();
            
        });
    }
    
    public function type()  {

        return $this->hasMany(Type::class ,'product_id' ,'id');

    }

    public function vendortype()  {

        return $this->hasMany(VendorType::class ,'product_id' ,'id');

    }

    public function cart()
    {
        return $this->hasMany(Cart::class ,'product_id' ,'id');
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
        
        return $this->hasMany(ComboProduct::class, 'main_product' , 'id');
        
    }

    public function comboproduct2() {
        
        return $this->hasMany(ComboProduct::class, 'combo_product' , 'id');
        
    }

    // public function footerimages()
    // {
    //     return $this->hasMany(FooterImage::class, 'product_id' , 'id');
    // }

    public function wishlist()
    {
        return $this->hasMany(Wishlist::class ,'product_id' , 'id');
    }

    public function rating()
    {
        return $this->hasMany(ProductRating::class ,'product_id' , 'id');
    }
}
