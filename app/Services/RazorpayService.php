<?php

namespace App\Services;

use Razorpay\Api\Api;

class RazorpayService
{
    protected $api;

    public function __construct()
    {
        // $this->api = new Api(config('services.razorpay.key_id'), config('services.razorpay.key_secret'));
        $this->api = new Api(config('rzp_test_nyyE7NA4CoCIbN'), config('U0iJVwdeskvEw7qVstJ7mh1c'));
    }

    public function createOrder($amount, $receipt, $currency = 'INR')
    {
        
        $amountInPaise = (float) $amount * 100;

        $order = $this->api->order->create([
            'receipt' => strval($receipt),
            'amount' => $amountInPaise,
            'currency' => $currency,
            'notes' => []
        ]);

        return $order;
    }


    public function verifySignature($attributes)
    {
        return $this->api->utility->verifyPaymentSignature($attributes);
    }
}
