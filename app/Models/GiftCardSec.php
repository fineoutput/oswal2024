<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiftCardSec extends Model
{
    use HasFactory;

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
        return $this->belongsTo(EcomProduct::class, 'product_id');
    }

    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id' , 'id');
    }
    public function updateStatus($status) {
        
        $this->is_active = $status;

        return $this->save();
    }
}
