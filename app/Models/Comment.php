<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Comment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'comments';

    protected $fillable = [
        'blog_id',
        'name',
        'email',
        'comment',
        'rplay_status',
        'cur_date',
        'ip',
        'is_active',
    ];

    public function updateStatus($status) {
        
        $this->is_active = $status;

        return $this->save();
    }

    public function blog()
    {
        return $this->belongsTo(Blog::class, 'id', 'blog_id');
    }

    public function Comment()
    {
        return $this->hasMany(ReplayComment::class, 'id', 'comment_id');
    }
}
