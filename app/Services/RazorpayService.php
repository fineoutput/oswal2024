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
        $amountInPaise = (int)($amount * 100);
       
        $order = $this->api->order->create([
            'receipt' => strval($receipt),
            'amount' => $amountInPaise,
            'currency' => $currency,
            'notes' => []
        ]);

        return $order;
    }


   public function verifySignature($request) {
        
        // $payment = $this->api->payment->fetch($request['razorpay_payment_id']);
        try {
            $attributes = array(

                'razorpay_order_id' => $request['razorpay_order_id'],

                'razorpay_payment_id' => $request['razorpay_payment_id'],

                'razorpay_signature' => $request['razorpay_signature']

            );

             $this->api->utility->verifyPaymentSignature($attributes);

             return ['status' => true , 'message' => 'payment verify sucessfully'];

        } catch (\Exception $e) {

            $message =  $e->getMessage();

            return ['status' => false , 'message' => $message ];
        }
    }
}