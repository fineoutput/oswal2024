<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trending extends Model
{
    use HasFactory;

    protected $table = 'trendings';

    protected $primaryKey = 'id';

    protected $fillable = [
        'product_id',
        'trending',
        'ip',
        'date',
        'added_by',
        'is_active',
    ];

    // public function category()
    // {
    //     return $this->belongsTo(EcomCategory::class, 'category_id');
    // }

    public function product()
    {
        return $this->belongsTo(EcomProduct::class, 'product_id' , 'id');
    }

    public function updateStatus($status) {
        
        $this->is_active = $status;

        return $this->save();
    }
}
