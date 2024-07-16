<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MajorProduct extends Model
{
    use HasFactory;

    protected $table = 'minor_products';

    protected $fillable = [
        'major_id', 'name', 'short_dis', 'long_dis', 'long_desc', 'url', 'reguler_price', 'sale_price', 'img1', 'img2', 'img3', 'is_active',  'cur_date', 'ip', 'added_by', 'video'
    ];

    public function updateStatus(string $newStatus)
    
    {
        $this->is_active = $newStatus;

        return $this->save();
    }

    public function majorcategory()
    {
        return $this->belongsTo(MajorCategory::class , 'major_id' , 'id');
    }

    public function type()  {

        return $this->hasOne(Type::class);

    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }
}
