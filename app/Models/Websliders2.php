<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Websliders2 extends Model
{
    use HasFactory;

    protected $table = 'websliders2';

    protected $primaryKey = 'id';

    protected $fillable = [
        'link',
        'image',
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
