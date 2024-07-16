<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model
{
    use HasFactory;

    protected $table = 'contact_us';

    protected $fillable = [
        'fname', 'lname', 'phone', 'email', 'message', 'reply',
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
