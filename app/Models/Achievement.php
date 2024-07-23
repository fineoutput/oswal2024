<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Achievement  extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'achievements';

    protected $fillable = [
        'title',
        'url',
        'short_desc',
        'long_desc',
        'image',
        'ip',
        'date',
        'added_by',
        'is_active',
    ];

    public function updateStatus($status) {
        
        $this->is_active = $status;

        return $this->save();
    }

 
}
