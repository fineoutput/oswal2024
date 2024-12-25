<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Popupimage extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'popup_image';

    protected $fillable = [
        'image',
        'status',
        'web_image',
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id' , 'id');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id' , 'id');
    }
    
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id' , 'id');
    }
}
