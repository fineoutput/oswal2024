<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Popup extends Model
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $table = 'popups';

    protected $fillable = [
        'name', 
        'image', 
        'ip', 
        'date',
        'is_active', 
        'added_by', 
    ];

    public function updateStatus($status) {
        
        $this->is_active = $status;

        return $this->save();
    }
}
