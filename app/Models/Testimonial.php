<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Testimonial extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'testimonials';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'image',
        'description',
        'rating',
        'ip',
        'added_by',
        'is_active',
        'date',
    ];

    // public function category()
    // {
    //     return $this->belongsTo(EcomCategory::class, 'category_id');
    // }

    // public function product()
    // {
    //     return $this->belongsTo(EcomProduct::class, 'product_id' , 'id');
    // }

    public function updateStatus($status) {
        
        $this->is_active = $status;

        return $this->save();
    }
}
