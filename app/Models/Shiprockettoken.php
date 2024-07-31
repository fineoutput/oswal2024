<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Shiprockettoken extends Model
{
    use HasFactory;

    protected $table = 'shiprocket_tokens';

    protected $fillable = ['token', 'expires_at'];

 
}
