<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class OrderInvoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'order_invoices';

    protected $fillable = [
        'user_id',
        'order_id',
        'invoice_no'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
