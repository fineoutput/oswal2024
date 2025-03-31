<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reward extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'rewards';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'product_id',
        'reward_type',
        'image',
        'quantity',
        'price',
        'weight',
        'ip',
        'date',
        'added_by',
        'is_active',
    ];

    public function product()
    {
        return $this->belongsTo(EcomProduct::class, 'product_id');
    }

    // public function product()
    // {
    //     return $this->belongsTo(EcomProduct::class, 'product_id');
    // }

    public function updateStatus($status) {
        
        $this->is_active = $status;

        return $this->save();
    }
}
