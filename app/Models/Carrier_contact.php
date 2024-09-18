<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrier_contact extends Model
{
    use HasFactory;

    protected $table = 'careers';

    protected $fillable = [
        'fname', 'lname', 'phone', 'email', 'message','subject', 'reply',
        'reply_message', 'cur_date', 'phone2', 
    ];
    public function updateStatus($status) {
        
        $this->is_active = $status;

        return $this->save();
    }

    public function getFullNameAttribute()
    {
        return "{$this->fname} {$this->lname}";
    }
}