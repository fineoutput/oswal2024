<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;

   protected $table = 'wishlists';

    protected $fillable = [
        'device_id', 'user_id', 'product_id', 'category_id', 'type_id', 'type_price', 'date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class ,'user_id' , 'id');
    }

    public function product()
    {
        return $this->belongsTo(EcomProduct::class ,'product_id' , 'id');
    }

    public function category()
    {
        return $this->belongsTo(EcomCategory::class,'category_id' , 'id');
    }

    public function type()
    {
        return $this->belongsTo(Type::class ,'type_id' , 'id');
    }

    public function vendortype()
    {
        return $this->belongsTo(Type::class ,'type_id' , 'id');
    }
}
