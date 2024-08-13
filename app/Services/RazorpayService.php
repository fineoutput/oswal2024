<?php

namespace App\Services;

use Razorpay\Api\Api;

use function PHPSTORM_META\type;

class RazorpayService
{
    protected $api;

    public function __construct()
    {
        // $this->api = new Api(config('services.razorpay.key_id'), config('services.razorpay.key_secret'));
        $this->api = new Api('rzp_test_nyyE7NA4CoCIbN', 'U0iJVwdeskvEw7qVstJ7mh1c');
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
        dd($attributes);
        return $this->api->utility->verifyPaymentSignature($attributes);
    }
}
