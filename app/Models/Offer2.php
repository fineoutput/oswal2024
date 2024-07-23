<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Offer2 extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'offers2';

    protected $primaryKey = 'id';

    protected $fillable = [
        'offer_name',
        'offer_name_hi',
        'image',
        'ip',
        'date',
        'added_by',
        'is_active',
        'category_id',
        'product_id'
    ];

    public function category()
    {
        return $this->belongsTo(EcomCategory::class, 'category_id');
    }

    public function product()
    {
        return $this->belongsTo(EcomProduct::class, 'product_id');
    }

    public function updateStatus($status) {
        
        $this->is_active = $status;

        return $this->save();
    }

}
