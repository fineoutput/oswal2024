<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Slider2 extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'sliders2';

    protected $primaryKey = 'id';

    protected $fillable = [
        'slider_name',
        'app_slider_name',
        'slider_name_hi',
        'app_slider_name_hi',
        'image',
        'app_image',
        'vendor_image',
        'vendor_slider_name',
        'vendor_slider_name_hi',
        'ip',
        'date',
        'added_by',
        'is_active',
    ];

    // public function category()
    // {
    //     return $this->belongsTo(EcomCategory::class, 'category_id');
    // }

    // public function product()
    // {
    //     return $this->belongsTo(EcomProduct::class, 'product_id');
    // }

    public function updateStatus($status) {
        
        $this->is_active = $status;

        return $this->save();
    }
}
