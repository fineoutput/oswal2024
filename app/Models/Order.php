<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'tbl_order1';

   protected $fillable = [
        'user_id',
        'total_amount',
        'sub_total',
        'address_id',
        'promocode',
        'promo_deduction_amount',
        'gift_id',
        'gift_amt',
        'gift1_id',
        'gift1_gst_amt',
        'payment_type',
        'payment_status',
        'order_status',
        'cod_charge',
        'delivery_charge',
        'order_shipping_amount',
        'delivery_status',
        'discount',
        'extra_discount',
        'total_order_weight',
        'total_order_mrp',
        'total_order_rate_am',
        'order_price',
        'ten_percent_of_order_price',
        'order_main_price',
        'postback_param_id',
        'mihpay_id',
        'payment_id',
        'mode',
        'online_payment_status',
        'unmapped_status',
        'txn_id',
        'razorpay_payment_id',
        'razorpay_order_id',
        'razorpay_signature',
        'payment_gateway_amount',
        'additional_charges',
        'added_on',
        'created_on',
        'is_admin',
        'first_name',
        'email',
        'phone',
        'bank_ref_number',
        'bank_code',
        'err_message',
        'name_on_card',
        'card_num',
        'card_type',
        'track_id',
        'rejected_by',
        'rejected_by_id',
        'order_from',
        'online_payment_discount',
        'net_amount_debit',
        'last_update_date',
        'ip',
        'date',
        'check_data',
        'invoice_year',
        'invoice_no',
        'year',
        'remarks'
    ];

    // Define relationships
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'main_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id');
    }

    public function gift()
    {
        return $this->belongsTo(GiftCard::class, 'gift_id');
    }

    public function gift1()
    {
        return $this->belongsTo(GiftCardSec::class, 'gift1_id');
    }

    public function invoices()
    {
        return $this->hasMany(OrderInvoice::class, 'order_id');
    }
}
