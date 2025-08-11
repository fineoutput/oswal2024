<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OswalStores extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'oswal_stores';

    protected $primaryKey = 'id';

    protected $fillable = [
        'store_name',
        'operator_name',
        'phone_no',
        'GST_No',
        'address',
        'state_id',
        'city_id',
        'locality',
        'shop_code'
    ];

    public function cities()
    {
         return $this->belongsTo(City::class, 'city_id');
    }
    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

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
