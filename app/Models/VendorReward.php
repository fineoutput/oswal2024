<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorReward extends Model
{
    use HasFactory;

    protected $table = 'vendor_rewards';

    protected $fillable = [
        'vendor_id',
        'order_id',
        'reward_name',
        'reward_image',
        'reward_id',
        'status',
        'accepted_by',
        'achieved_at',
    ];


    protected $dates = ['achieved_at', 'created_at', 'updated_at'];

    const STATUS_APPLIED = 1;

    const STATUS_ACCEPTED = 2;

    const STATUS_REJECTED = 3;

    public function vendor() {

        return $this->belongsTo(User::class , 'vendor_id' , 'id');
  
    }

}