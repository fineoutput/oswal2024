<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class MajorCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'major_categorys';

    protected $fillable = [
        'name', 'short_dis', 'long_desc', 'url', 'image','is_active', 'ip', 'added_by', 'cur_date'
    ];

     /**
     * Update the status of the Category.
     *
     * @param string $newStatus
     * @return bool
     */

     protected static function boot()
     {
        
         parent::boot();
 
 
         static::deleting(function (MajorCategory $majorCategory) {
            
              $majorCategory->majorproducts()->delete();
 
         });
     }
    public function updateStatus(string $newStatus)
    
    {
        $this->is_active = $newStatus;

        return $this->save();

    }
    
    public function majorproducts()
    {
        return $this->hasMany(MajorProduct::class ,'major_id' , 'id');
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
