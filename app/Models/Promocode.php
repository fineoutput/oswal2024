<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promocode  extends Model
{
    use HasFactory;

    protected $table = 'promocode';

    protected $fillable = [
        'promocode',
        'percent',
        'type',
        'minimum_amount',
        'maximum_gift_amount',
        'expiry_date',
        'ip',
        'date',
        'added_by',
        'is_active',
    ];

    public function updateStatus($status) {
        
        $this->is_active = $status;

        return $this->save();
    }

 
}
