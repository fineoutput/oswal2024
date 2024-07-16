<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dealer extends Model
{
    use HasFactory;

    protected $table = 'pincode_wises';

    protected $fillable = [
        'dealer_name', 'pincode', 'is_active', 'ip', 'added_by', 'cur_date'
    ];

}
