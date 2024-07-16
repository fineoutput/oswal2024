<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RetailShop extends Model
{
    use HasFactory;

    protected $table = 'retail_shops';

    protected $fillable = [
        'shop_name', 'person_name', 'address', 'area', 'city', 'state',
        'pincode', 'phone1', 'phone2', 'map', 'is_active', 'ip', 'added_by' ,'date'
    ];

    public function updateStatus($status) {
        
        $this->is_active = $status;

        return $this->save();
    }

}
