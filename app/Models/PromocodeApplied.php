<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromocodeApplied extends Model
{
    use HasFactory;

    protected $table = 'tbl_promocode_applied';

    protected $fillable = [
        'device_id',
        'user_id',
        'order_id',
        'promocode_id',
        'status',
        'promocode_discount',
        'date',
    ];
 
    // protected $colum = 'update_at';

    protected $primaryKey = 'id';

    
}
