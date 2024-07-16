<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class PushNotification extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'title', 
        'description', 
        'image', 
        'ip', 
        'added_by', 
        'is_active', 
        'date'
    ];

  
}
