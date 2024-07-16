<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    use HasFactory;

    protected $table = 'emails';

    protected $fillable = [
        'team_id', 'pincode', 'team_email', 'reciver_name', 'reciver_email', 'cur_date' ,'msg' ,'status' ,'ip' ,'sended_by'
    ];

}
