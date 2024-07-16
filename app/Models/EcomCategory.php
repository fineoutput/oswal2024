<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EcomCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'short_dis', 'long_desc', 'sequence', 'name_hi', 
        'short_dis_hi', 'long_desc_hi', 'url', 'image', 'app_image', 'slide_img1', 'slide_img2', 'slide_img3', 'is_active', 'ip', 'added_by', 'cur_date'
    ];

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
        return $this->hasMany(EcomProduct::class);
    }

    public function type()
    {
        return $this->hasMany(Type::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }
    
}
