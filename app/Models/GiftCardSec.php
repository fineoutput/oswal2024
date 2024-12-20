<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class GiftCardSec extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'gift_cards_1';

    protected $primaryKey = 'id';

    protected $fillable = [
        'product_id',
        'type_id',
        'name',
        'image',
        'appimage',
        'price',
        'ip',
        'date',
        'added_by',
        'is_active',
    ];

    public function product()
    {
        return $this->belongsTo(EcomProduct::class, 'product_id' , 'id');
    }

    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id' , 'id');
    }

    public function vendortype()
    {
        return $this->belongsTo(VendorType::class, 'type_id' , 'id');
    }

    public function order()
    {
        return $this->hasOne(Order::class, 'gift1_id' , 'id');
    }
    public function updateStatus($status) {
        
        $this->is_active = $status;

        return $this->save();
    }
    
}
