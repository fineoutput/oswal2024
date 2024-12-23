<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserTemp extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'user_temp';

    protected $fillable = [
        'name',
        'contact',
        'email',
        'first_name_hi',
        'device_id',
        'auth',
        'status',
        'added_by',
        'date',
        'ip',
        'referral_code',
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
