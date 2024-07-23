<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Blog extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'blogs';

    protected $fillable = [
        'title',
        'url',
        'short_des',
        'auther',
        'long_des',
        'keywords',
        'image',
        'meta',
        'is_active',
        'added_by',
        'date',
        'ip',
    ];

    public function updateStatus($status) {
        
        $this->is_active = $status;

        return $this->save();
    }

    public function blog()
    {
        return $this->hasMany(Comment::class, 'id', 'blog_id');
    }
}
