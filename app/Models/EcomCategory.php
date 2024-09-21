<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EcomCategory extends Model
{
    use HasFactory, SoftDeletes;


    protected $fillable = [
        'name', 'short_dis', 'long_desc', 'sequence', 'name_hi', 
        'short_dis_hi', 'long_desc_hi', 'url', 'image', 'app_image', 'slide_img1', 'slide_img2', 'slide_img3', 'is_active', 'ip', 'added_by', 'cur_date', 'icon'
    ];

    protected static function boot()
    {
       
        parent::boot();


        static::deleting(function (EcomCategory $ecomCategory) {
           
             $ecomCategory->products()->delete();

            //  $ecomCategory->footerimages()->delete();
             
             $ecomCategory->wishlist()->delete();

            foreach ($ecomCategory->type as $type) {
                $type->delete();
            }

            foreach ($ecomCategory->vendortype as $vendortype) {
                $vendortype->delete();
            }


            foreach ($ecomCategory->carts as $cart) {
                $cart->delete();
            }

            foreach ($ecomCategory->offers as $offer) {
                $offer->delete();
            }

            foreach ($ecomCategory->offers2 as $offer2) {
                $offer2->delete();
            }

            foreach ($ecomCategory->sliders as $slider) {
                $slider->delete();
            }
        });
    }


     /**
     * Update the status of the Category.
     *
     * @param string $newStatus
     * @return bool
     */

    public function updateStatus(string $newStatus)
    
    {
        $this->is_active = $newStatus;

        return $this->save();

    }
    
    public function products()
    {
        return $this->hasMany(EcomProduct::class , 'category_id' , 'id');
    }

    public function type()
    {
        return $this->hasMany(Type::class ,'category_id' , 'id');
    }

    public function vendortype()
    {
        return $this->hasMany(VendorType::class ,'category_id' , 'id');
    }

    public function carts()
    {
        return $this->hasMany(Cart::class ,'category_id' , 'id');
    }
    
    public function offers()
    {
        return $this->hasMany(Offer::class, 'category_id');
    }

    public function offers2()
    {
        return $this->hasMany(Offer2::class, 'category_id');
    }

    public function sliders()
    {
        return $this->hasMany(Slider::class, 'category_id');
    }

    // public function footerimages()
    // {
    //     return $this->hasMany(FooterImage::class, 'category_id');
    // }

    public function wishlist()
    {
        return $this->hasMany(Wishlist::class ,'category_id' , 'id');
    }

    public function rating()
    {
        return $this->hasMany(ProductRating ::class ,'category_id' , 'id');
    }
}
