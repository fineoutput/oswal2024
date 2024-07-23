<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReplayComment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'replay_comments';

    protected $fillable = [
        'comment_id',
        'name',
        'reply',
        'cur_date',
        'added_by',
        'ip',
        'is_active',
    ];

    public function updateStatus($status) {
        
        $this->is_active = $status;

        return $this->save();
    }

    public function Comment()
    {
        return $this->belongsTo(Comment::class, 'id', 'comment_id');
    }
}
