<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDeviceToken extends Model
{
    use HasFactory;

    protected $table = 'users_device_token';

    protected $primaryKey = 'id';

    protected $fillable = [
        'device_id',
        'device_token',
        'user_id',
    ];

    // public function category()
    // {
    //     return $this->belongsTo(EcomCategory::class, 'category_id');
    // }

    public function user()
    {
        return $this->belongsTo(UserDeviceToken::class, 'user_id' , 'id');
    }

    public function updateStatus($status) {
        
        $this->is_active = $status;

        return $this->save();
    }
}
