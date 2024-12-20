<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Dealer extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pincode_wises';

    protected $fillable = [
        'dealer_name', 'pincode', 'is_active', 'ip', 'added_by', 'cur_date'
    ];

}
