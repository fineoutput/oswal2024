<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Otp extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tbl_otp';

    protected $fillable = [
        'name',
        'contact_no',
        'email',
        'email',
        'otp',
        'ip',
        'added_by',
        'is_active',
        'date',
    ];

}
