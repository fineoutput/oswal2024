<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class GoogleAccessToken extends Model
{
    use HasFactory;

    protected $table = 'google_access_tokens';

    protected $fillable = ['token', 'expires_at'];

 
}
