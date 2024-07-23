<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Email extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'emails';

    protected $fillable = [
        'team_id', 'pincode', 'team_email', 'reciver_name', 'reciver_email', 'cur_date' ,'msg' ,'status' ,'ip' ,'sended_by'
    ];

}
