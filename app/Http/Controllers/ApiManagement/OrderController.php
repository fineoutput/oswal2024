<?php
namespace App\Http\Controllers\ApiManagement;

use Illuminate\Support\Facades\Validator;

use App\Models\WalletTransactionHistory;

use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;

use App\Services\RazorpayService;

use App\Models\TransferOrder;

use Illuminate\Http\Request;

use App\Models\OrderDetail;

use App\Models\Promocode;

use App\Models\Address;

use App\Models\Order;

use App\Models\Cart;
use App\Models\DeliveryBoy;
use App\Models\User;

use App\Models\Type;

class OrderController extends Controller
{
    protected $cartController;

    public function __construct(CartController $cartController ,RazorpayService $razorpayService)
    {
        $this->cart = $cartController;
        $this->razorpayService = $razorpayService;
    }

    public function calculate(Request $request)
    {
        
        $rules = [
            'user_id'    => 'required|exists:users,id',
            'device_id'  => 'required',
            'address_id' => 'required|exists:user_address,id',
            'promocode'  => 'nullable|string',
            'gift_card_id'    => 'nullable|integer|exists:gift_cards,id',
            'wallet_status'   => 'required|integer',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 400);
        }

        $deviceId       = $request->input('device_id');
        $userId         = $request->input('user_id');
        $promocode      = $request->input('promocode');
        $addressId      = $request->input('address_id');
        $gift_card_id   = $request->input('gift_card_id');
        $wallet_status  = $request->input('wallet_status');

        $userAddress  = Address::findOrFail($addressId);

        $stateId      = $userAddress->state;

        $cityId       = $userAddress->city;

        $addressresponse = [
            'user_name' => Auth::user()->first_name,
            'address'   => $userAddress->address,
            'state'     => $userAddress->states->state_name,
            'city'      => $userAddress->citys->city_name,
            'zipcode'   => $userAddress->zipcode,
            'email'     => Auth::user()->email,
            'phone'     => Auth::user()->contact,
        ];

        $cartItems = Cart::query()
                    ->where(function ($query) use ($userId, $deviceId) {
                        $query->Where('device_id', $deviceId)
                        ->orwhere('user_id', $userId);
                    })
                    ->with(['type' => fn($query) => $query->where('state_id', $stateId)->where('city_id', $cityId)])
                    ->get();


        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'Your cart is empty.', 'status' => 400]);
        }

        $cartItems->each(function ($cartItem) {
            if ($cartItem->type) {
                $cartItem->type_price = $cartItem->type->selling_price;
                $cartItem->total_qty_price = $cartItem->quantity * $cartItem->type_price;
                $cartItem->save();
            }
        });

        $cartItemTotal    = $cartItems->sum('total_qty_price'); 
        $deliveryCharge   = 0;
        $promocode_id     = null;
        $promo_discount   = 0;
        $applyGiftCard    = [];
        $promocode_name   = '';
        
        if (!empty($addressId)) {

            $cartItems->load('type');

            $total_order_weight = $cartItems->sum(function ($cartItem) {

                return (float)$cartItem->type->weight * (int)$cartItem->quantity;
            });

            $shipingCharges =  calculateShippingCharges($total_order_weight, $userAddress->city);

            if ($shipingCharges->original['success']) {

                $deliveryCharge = $shipingCharges->original['total_weight_charge'];
            } else {

                return $shipingCharges;
            }
        }

        if ($promocode != null) {

            $applyPromocode = $this->cart->applyPromocode($deviceId, $userId,$promocode,$cartItemTotal);

            if ($applyPromocode->original['success']) {

                $promo_discount       = $applyPromocode->original['promo_discount'];

                $promocode_id         = $applyPromocode->original['promocode_id'];

                $promocode_name       = $promocode;

            } else {

                return $this->generateResponse($deviceId,$userId,$stateId,$cityId,$wallet_status,$deliveryCharge,$promo_discount,$promocode_id,$promocode_name,$addressresponse,$applyGiftCard,200,$applyPromocode->original['message']);

            }
        }

        if ($gift_card_id != null) {
            
            $applyGiftCard  = $this->cart->applyGiftCard($cartItemTotal, $gift_card_id);

            if (!$applyGiftCard->original['success']) {

                return $this->generateResponse($deviceId,$userId,$stateId,$cityId,$wallet_status,$deliveryCharge,$promo_discount,$promocode_id,$promocode_name,$addressresponse,$applyGiftCard,200,$applyGiftCard->original['message']);

            }else{

                $applyGiftCard = $applyGiftCard->original['data'];
            }

        }
     
        return $this->generateResponse($deviceId,$userId,$stateId,$cityId,$wallet_status,$deliveryCharge,$promo_discount,$promocode_id,$promocode_name,$addressresponse,$applyGiftCard,200,'featch data sucessfully');
    }

    public function generateResponse($deviceId,$userId,$stateId,$cityId,$wallet_status,$deliveryCharge,$promo_discount,$promocode_id,$promocode_name,$addressresponse,$applyGiftCard,$status,$message) {

        $cartData = Cart::with(['product.type' => function ($query) use ($stateId, $cityId) {
            $query->where('is_active', 1)
                ->when($stateId, function ($query, $stateId) {
                    return $query->where('state_id', $stateId);
                })
                ->when($cityId, function ($query, $cityId) {
                    return $query->where('city_id', $cityId);
                })
                ->when(is_null($stateId) || is_null($cityId), function ($query) {
                    return $query->groupBy('type_name');
                });
        }])
        ->where(function ($query) use ($userId, $deviceId) {
            $query->Where('device_id', $deviceId)->orwhere('user_id', $userId);
        })
        ->get();

        $totalAmount = 0;
        $totalSaveAmount = 0;
        $walletDescount = 0;
        $totalwalletAmount  = 0;

        foreach ($cartData as $cartItem) {

            $product = $cartItem->product;

            if (!$product || !$product->is_active) {
                continue;
            }

            if ($cartItem->type) {

                $totalSaveAmount += $cartItem->quantity * $cartItem->type->del_mrp;

            } 
            
            $totalAmount += $cartItem->total_qty_price;

        }

        $user = User::where('device_id', $deviceId)->orWhere('id', $userId)->first();
        
        if($wallet_status){
            
          if($user->wallet_amount > 0){

              $walletDescount = (float) calculate_wallet_discount($user->wallet_amount);
    
          }else{

            $message = 'Your wallet balance is insufficient.';

          }

        }

        $totalwalletAmount = $user->wallet_amount;

        $finalAmount = $totalAmount + $deliveryCharge - $promo_discount - $walletDescount;

        $reponse = [
            'message'          => $message,
            'success'           => ($status == 200) ? true : false,
            'address'          => $addressresponse,
            'shipping_charge'  => (float)$deliveryCharge,
        ];

        $reponse['promocode'] = [
            'promo_id'       => $promocode_id,
            'promo_discount' => formatPrice($promo_discount,false),
            'promo_name'     => $promocode_name,
        ];

        // First Gift Card Detail
        if (!empty($applyGiftCard)) {
  
            $reponse['gift_card_1']      = [
                  'cal_promo_amu'         => formatPrice($finalAmount + $applyGiftCard['amount'],false),
                  'gift_card_amount'      => formatPrice($applyGiftCard['amount'],false),
                  'gift_card_gst_amount'  => formatPrice($applyGiftCard['gst_amount'],false),
            ];

            $finalAmount += $applyGiftCard['amount'];
        }
        
        $reponse['wallet_discount']  = $walletDescount;
        $reponse['wallet_amount']    = $totalwalletAmount;
        $reponse['total_discount' ]  = $promo_discount + $walletDescount;
        $reponse['sub_total' ]       = formatPrice($totalAmount,false);
        $reponse['save_total' ]      = formatPrice(($totalSaveAmount - $totalAmount) , false);
        $reponse['prepaid_final_amount']    = formatPrice($finalAmount,false);
        $reponse['cod_charge']    = formatPrice(getConstant()->cod_charge,false);
        $reponse['cod_final_amount' ]    = formatPrice(($finalAmount + getConstant()->cod_charge),false);
        
        return response()->json($reponse ,$status);
    }

    public function checkout(Request $request)
    {
        // Validation rules
        $rules = [
            'user_id'      => 'required|exists:users,id',
            'device_id'    => 'required|',
            'address_id'   => 'required|exists:user_address,id',
            // 'state_id'     => 'required|exists:all_states,id',
            // 'city_id'      => 'required|exists:all_cities,id',
            'promocode_id' => 'nullable|exists:promocodes,id',
            'gift_card_id' => 'nullable|exists:gift_cards,id',
            'wallet_status'   => 'required|integer',
            'total_amount'  => 'required',
            'payment_type'  => 'required|integer'
        ];
 
        // Validate request data
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return response()->json(['success' => false, 'errors' => $validator->errors()], 400);

        }
       
        // Retrieve request inputs
        $userId       = $request->input('user_id') ?? Auth::user()->id;

        $deviceId     = $request->input('device_id');

        $addressId    = $request->input('address_id');

        $promocodeId  = $request->input('promocode_id');

        $gift_card_id = $request->input('gift_card_id');
    
        $wallet_status= $request->input('wallet_status');

        // Fetch user address
        $userAddress = Address::findOrFail($addressId);

        $stateId      = $userAddress->state;

        $cityId       = $userAddress->city;
 
        $payment_type = $request->input('payment_type');

        // Fetch cart data with related products and types
        $cartData = Cart::with(['product.type' => function ($query) use ($stateId, $cityId) {

            $query->where('is_active', 1)

                ->when($stateId, fn ($query) => $query->where('state_id', $stateId))

                ->when($cityId, fn ($query) => $query->where('city_id', $cityId))

                ->when(is_null($stateId) || is_null($cityId), fn ($query) => $query->groupBy('type_name'));

        }])->where(function ($query) use ($userId, $deviceId) {

                $query->where('user_id', $userId)

                    ->orWhere('device_id', $deviceId);

            })->get();


        $totalWeight = 0;

        $subtotal = 0;

        $productData = [];

        $deductionAmount = 0;

        $type_rate_amount = 0;

        $applyGiftCard = [];

        $applyGiftCardSec = [];

        $walletDescount = 0;

        $order =  Order::create([
            'order_status'    => 0,
            'delivery_status' => 0,
            'payment_type'    => 0,
			'payment_status'  => 0,
            'ip'              => $request->ip(),
            'order_from'      => 'Application',
			'date'            => now()->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s')
        ]);

        foreach ($cartData as $cartItem) {

            $product = $cartItem->product;

            if (!$product || !$product->is_active) {
                continue;
            }

            $typeData = $product->type->filter(function ($type) use ($cartItem) {
                return $cartItem->type->id == $type->id;
            })->map(function ($type) use ($cartItem) {

                    $totalTypeQuantityPrice = $cartItem->quantity * $type->selling_price;

                    return [
                        'type_id'               => $type->id,
                        'type_name'             => $type->type_name,
                        'type_category_id'      => $type->category_id,
                        'type_product_id'       => $type->product_id,
                        'type_mrp'              => $type->del_mrp,
                        'gst_percentage'        => $type->gst_percentage,
                        'gst_percentage_price'  => $type->gst_percentage_price,
                        'selling_price'         => $type->selling_price,
                        'type_weight'           => $type->weight,
                        'type_rate'             => $type->rate,
                        'total_typ_qty_price'   => $totalTypeQuantityPrice
                    ];

                });

            $totalWeight += $cartItem->quantity * (float)$cartItem->type->weight;

            $subtotal    += $cartItem->total_qty_price;
        
            $type_rate_amount += $cartItem->quantity * (float)$cartItem->type->rate ?? 0;

            $productData[] = [
                'id'                => $cartItem->id,
                'product_id'        => $product->id,
                'category_id'       => $cartItem->category_id,
                'type_id'           => $cartItem->type_id,
                'type_price'        => $cartItem->type_price,
                'quantity'          => $cartItem->quantity,
                'total_qty_price'   => round($cartItem->total_qty_price),
                'product_name'      => $product->name,
                'long_desc'         => $product->long_desc,
                'url'               => $product->url,
                'image1'            => asset($product->img1),
                'image2'            => asset($product->img2),
                'image3'            => asset($product->img3),
                'image4'            => asset($product->img4),
                'is_active'         => $product->is_active,
                'type'              => $typeData
            ];

          // Apply Combo Product if exsit
          $comboProduct =  $this->cart->comboProduct($cartItem->type_id, $product, 'en');
         
            OrderDetail::create([      
                'main_id'               =>  $order->id,
                'product_id'            =>  $product->id,
                'type_id'               =>  $cartItem->type_id,
                'type_mrp'              =>  $cartItem->type->mrp,
                'gst'                   =>  $cartItem->type->gst_percentage,
                'gst_percentage_price'  =>  $cartItem->type->gst_percentage_price,
                'quantity'              =>  $cartItem->quantity,
                'combo_gst'             =>  0,
                'combo_product'         =>  (count($comboProduct) > 0) ? $comboProduct['product']['product_name'] : '',
                'combo_name'            =>  (count($comboProduct) > 0) ? $comboProduct['combodetail']['combo_type_name'] : '',
                'combo_type'            =>  (count($comboProduct) > 0) ? $comboProduct['combodetail']['id'] : '',
                'amount'                =>  $cartItem->total_qty_price,
                'ip'                    =>  $request->ip(),
                'date'                  =>  now()->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s')
            ]);

        }

        // Calculate shipping charges
        $shippingChargesResponse = calculateShippingCharges($totalWeight,  $cityId);

        if (!$shippingChargesResponse->original['success']) {

            return $shippingChargesResponse;
        }

        $deliveryCharge = $shippingChargesResponse->original['total_weight_charge'];

        $totalPriceWithDelivery = $subtotal + $deliveryCharge;

        $totalAmount = $totalPriceWithDelivery;

        $type_rate_amount =  ($type_rate_amount * 5 / 100) + ($totalWeight * 5 / 100);

        // Apply promocode if provided
        if ($promocodeId) {

            $promocode = Promocode::findOrFail($promocodeId);

            $deductionAmount = ($promocode->percent / 100) * $subtotal;

            if ($deductionAmount > $promocode->maximum_gift_amount) {
                $deductionAmount = $promocode->maximum_gift_amount;
            }

            $totalAmount -= $deductionAmount;
        }
        
        // Apply wallet if provided
        if($wallet_status){
            
            $user = User::where('device_id', $deviceId)->orWhere('id', $userId)->first();
            
            $walletDescount = (float) calculate_wallet_discount($user->wallet_amount);
  
            $totalAmount -= $walletDescount;

        }

        // Apply Gift first Card if provided
        if ($gift_card_id) {

            $applyGiftCard = $this->cart->applyGiftCard($subtotal, $gift_card_id);

            if ($applyGiftCard->original['success']) {

                $applyGiftCard = $applyGiftCard->original['data'];
            
                $totalAmount += $applyGiftCard['amount'];

            }else{
                $applyGiftCard = [];
            }
        }

        // Apply Gift Sec Card if Exsit
        $applyGiftCardSec = $this->cart->applyGiftCardSec($subtotal);
        
        if ($applyGiftCardSec->original['success']) {

            $applyGiftCardSec = $applyGiftCardSec->original['gift_detail'];

        }else{
            $applyGiftCardSec = [];
        }

        Order::where('id', $order->id)->update([
            'user_id'                    => $userId ?? Auth::user()->id,
            'total_amount'               => $request->total_amount ?? $totalAmount,
            'sub_total'                  => $subtotal,
            'address_id'                 => $addressId,
            'promocode'                  => $promocodeId ?? '',
            'promo_deduction_amount'     => $deductionAmount,
            'gift_id'                    => (count($applyGiftCard) > 0) ? $applyGiftCard['gift_detail']['id'] : '',
            'gift_amt'                   => (count($applyGiftCard) > 0) ? $applyGiftCard['amount'] : '',
            'gift_gst_amt'               => (count($applyGiftCard) > 0) ? $applyGiftCard['gst_amount'] : '',
            'gift1_id'                   => (count($applyGiftCardSec) > 0) ? $applyGiftCardSec['id'] : '',
            'delivery_charge'            => $deliveryCharge,
            'order_shipping_amount'      => $deliveryCharge,
            'extra_discount'             => $walletDescount,
            'total_order_weight'         => $totalWeight,
            'total_order_mrp'            => round($subtotal),
            'total_order_rate_am'        => round($type_rate_amount),
            'order_price'                => round($subtotal-$type_rate_amount),
            'ten_percent_of_order_price' => round(($subtotal-$type_rate_amount) * 10 / 100),
            'order_main_price'           => round(($subtotal-$type_rate_amount)-(($subtotal-$type_rate_amount) * 10 / 100)),
        ]);

        // Return the calculated data (or proceed with further processing)
        // return response()->json(['success' => true, 'message'=> 'Order successfully created', 'data'=>['order_id'=>$order->id , 'final_amount' => formatPrice($totalAmount ,false) ], 'status'=> 200],200);

        if($payment_type == 1){

          return $this->codCheckout($order->id,$payment_type);
        }else{
          return $this->paidCheckout($order->id,$payment_type);
        }
    }


    public function codCheckout($orderId, $paymentType)
    {
        // // Define validation rules
        // $rules = [
        //     'order_id' => 'required|integer|exists:tbl_order1,id',
        //     'payment_type' => 'required|integer'
        // ];

        // // Validate request data
        // $validator = Validator::make($request->all(), $rules);

        // if ($validator->fails()) {
        //     return response()->json(['success' => false, 'errors' => $validator->errors()], 400);
        // }

        // Get authenticated user
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized', 'status' => 401], 401);
        }

        // $orderId = $request->input('order_id');
        // $paymentType = $request->input('payment_type');

        // Fetch the order
        $order = Order::where('id', $orderId)
                    ->where('order_status', 0)
                    ->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found or invalid status', 'status' => 404], 404);
        }

        if ($paymentType != 1) {
            return response()->json(['message' => 'Invalid payment type', 'status' => 400], 400);
        }

        $maxCodAmount = getConstant()->cod_max_process_amount;

        if ($order->sub_total > $maxCodAmount) {
            return response()->json([
                'status' => 400,
                'message' => "The payment type is invalid for amounts exceeding ".formatPrice($maxCodAmount)
            ]);
        }

        // Handle COD payment type
        $codCharge = getConstant()->cod_charge;

        $order->update([
            'order_status'   => 1,
            'payment_type'   => 1,
            'payment_status' => 1,
            'cod_charge'     => $codCharge,
            'total_amount'   => $order->total_amount,
        ]);

        $invoiceNumber = generateInvoiceNumber($orderId);

        // Ensure invoice number is generated successfully
        if ($invoiceNumber) {

            Cart::where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->orWhere('device_id', $user->device_id);
            })->update(['checkout_status' => 1]);
            

            Cart::where(function ($query) use ($user) {
                $query->where('device_id', $user->device_id)
                      ->orWhere('user_id', $user->id);
            })->delete();

            if ($user instanceof \App\Models\User) {

                if($order->extra_discount != null){

                    $user->wallet_amount -= $order->extra_discount;
                    $user->save();
    
                    WalletTransactionHistory::createTransaction([
                        'user_id' => $user->id,
                        'transaction_type' => 'debit',
                        'amount' => $order->extra_discount,
                        'transaction_date' => now()->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s'),
                        'status' => WalletTransactionHistory::STATUS_COMPLETED,
                        'description' => "Used wallet amount for order ID: {$order->id}",
                    ]);

                }
            }

            // Prepare response
            $response = [
                'order_id' => $order->id,
                'amount' => formatPrice($order->total_amount,false),
                'invoice_number' => $invoiceNumber
            ];

            return response()->json(['message' => 'Order completed successfully', 'status' => 200, 'data' => $response], 200);
        }

        // Handle case where invoice generation fails
        return response()->json(['message' => 'Failed to generate invoice', 'status' => 500], 500);
    }

    public function paidCheckout($orderId , $paymentType) {

        // Define validation rules
        // $rules = [
        //     'order_id' => 'required|integer|exists:tbl_order1,id',
        //     'payment_type' => 'required|integer'
        // ];

        // $validator = Validator::make($request->all(), $rules);

        // if ($validator->fails()) {
        //     return response()->json(['success' => false, 'errors' => $validator->errors()], 400);
        // }

        // Get authenticated user
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized', 'status' => 401], 401);
        }

        // $orderId = $request->input('order_id');

        // $paymentType = $request->input('payment_type');

        // Fetch the order
        $order = Order::where('id', $orderId)
                    ->where('order_status', 0)
                    ->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found or invalid status', 'status' => 404], 404);
        }

        if ($paymentType != 2) {
            return response()->json(['message' => 'Invalid payment type', 'status' => 400], 400);
        }

        $razorpayOrder = $this->razorpayService->createOrder($order->total_amount , $order->id);

        $order->update([
            'payment_type'      => 2,
            'razorpay_order_id' => $razorpayOrder->id,
        ]);

        $data = [
            'razor_order_id' => $razorpayOrder->id,
            'amount' => formatPrice($order->total_amount,false),
            'email'  => $user->email,
            'phone'  => $user->contact,
            'name'   => $user->first_name,
        ];

        return response()->json(['status'=>true ,'message' => 'Order successfully created', 'data'=> $data],200);   
    }

    public function verifyPayment(Request $request) {
        
        $rules = [
            'razorpay_signature'  => 'required|string',
            'razorpay_payment_id' => 'required|string',
            'razorpay_order_id'   => 'required|string',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 400);
        }

        // Get authenticated user
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized', 'status' => 401], 401);
        }

        $razorpaySignature = $request->input('razorpay_signature');
        $razorpayPaymentId = $request->input('razorpay_payment_id');
        $razorpayOrderId   = $request->input('razorpay_order_id');

        $order = Order::where('razorpay_order_id', $razorpayOrderId)
        ->where('order_status', 0)
        ->first();

        if (!$order) {
           return response()->json(['message' => 'Order not found or invalid status', 'status' => 404], 404);
        }

        $signatureStatus = $this->razorpayService->verifySignature($request->all());
      
        if ($signatureStatus['status']) {

            $invoiceNumber = generateInvoiceNumber($order->id);

            if ($invoiceNumber) {
    
                Cart::where(function ($query) use ($user) {
                    $query->where('user_id', $user->id)
                          ->orWhere('device_id', $user->device_id);
                })->update(['checkout_status' => 1]);
                
    
                Cart::where(function ($query) use ($user) {
                    $query->where('device_id', $user->device_id)
                          ->orWhere('user_id', $user->id);
                })->delete();
                
                
                if ($user instanceof \App\Models\User) {
    
                    if($order->extra_discount != null){
    
                        $user->wallet_amount -= $order->extra_discount;
                        $user->save();
        
                        WalletTransactionHistory::createTransaction([
                            'user_id' => $user->id,
                            'transaction_type' => 'debit',
                            'amount' => $order->extra_discount,
                            'transaction_date' => now()->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s'),
                            'status' => WalletTransactionHistory::STATUS_COMPLETED,
                            'description' => "Used wallet amount for order ID: {$order->id}",
                        ]);
    
                    }
                }

            }

            $order->update([
                'order_status'        => 1,
                'payment_status'      => 1,
                'txn_id'              => 1,
                'razorpay_payment_id' => $razorpayPaymentId,
                'razorpay_signature'  => $razorpaySignature,
            ]);
            // Prepare response
            $response = [
                'order_id' => $order->id,
                'amount' => formatPrice($order->total_amount,false),
                'invoice_number' => $invoiceNumber
            ];

            return response()->json(['message' => 'Payment successful ,Order completed successfully', 'status' => 200, 'data' => $response], 200);
        } else {
            return response()->json(['status'=> false, 'message' =>  $signatureStatus['message'] ,'status' => 400,], 400);
        }
    }

    public function orders(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'      => 'required|exists:users,id',
            'device_id'    => 'required',
            'lang'         => 'required|string'
        ]);

        if ($validator->fails()) {

            return response()->json(['message' => $validator->errors()->first(), 'status' => 400]);
        }

        $user_id = $request->input('user_id');

        $lang    = $request->input('lang');

        $user = User::find($user_id);

        $dataw = [];

        

        if ($user) {

            $orders = Order::with('orderDetails.product')->where('user_id', $user->id)->where('order_status', '!=', 0)->orderBy('id', 'DESC')->get();
            
            if ($orders->isNotEmpty()) {

                foreach ($orders as $order) {

                    $promo = '';

                    if ($order->promocode != 'Apply coupon' && !empty($order->promocode)) {

                        $promocode = Promocode::find($order->promocode);

                        if ($promocode) {

                            $promo = $promocode->promocode;

                        }

                    }

                    $payment_type = '';

                    if ($order->payment_type == 1) {

                        $payment_type = $lang != 'hi' ? 'Cash on delivery' : lang_change('Cash on delivery');

                    } elseif ($order->payment_type == 2) {

                        $payment_type = $lang != 'hi' ? 'Online Payment' : lang_change('Online Payment');

                    }
                    $productImage = [];
                    
                    foreach ($order->orderDetails as $key => $detail) {
                        $product = $detail->product;
                        
                        if ($product) {
                            $productImage[] = asset($product->img_app1);
                        } else {
                            
                            $productImage[] = null;
                        }
                    }
                    
                    

                    $rating_avg = DB::table('order_ratings')->where('order_id', $order->id)->avg('rating');
                    
                    $rating_avg = number_format((float)$rating_avg, 1, '.', '');

                    $dataw[] = [
                        'order_id'        => $order->id,
                        'order_status'    => getOrderStatus($order->order_status),
                        'order_rejected'  => getRejectedByDetails($order->rejected_by , $order->rejected_by_id),
                        'sub_total'       => formatPrice($order->sub_total,false),
                        'amount'          => formatPrice($order->total_amount,false),
                        'promocode_discount' => formatPrice($order->promo_deduction_amount,false),
                        'delivery_charge' => formatPrice($order->delivery_charge,false),
                        'rating_status'   => $rating_avg > 0 ? 1 : 0,
                        'rating'          => $rating_avg,
                        'cod_charge'      => $order->cod_charge,     
                        'payment_type'    => $payment_type,
                        'date'            => $order->date,
                        'promocode'       => $promo,
                        'product_image'   => $productImage,
                    ];
                }
            }

            return response()->json([
                'message' => 'success',
                'data' => $dataw,
                'status' => 200
            ]);
        }

        return response()->json([
            'message' => 'success',
            'data' => $dataw,
            'status' => 200
        ]);
    }

    public function orderDetail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'   => 'required',
            'order_id'  => 'required',
            'lang'      => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([ 'message' => $validator->errors()->first(), 'status' => 422],422);
        }

        $user_id = $request->input('user_id');
        $order_id = $request->input('order_id');
        $lang = $request->input('lang');
        $deleveryBoy = [];

        $user = User::find($user_id);

        if (!$user) {
            return response()->json([
                'message' => 'User does not exist',
                'status' => 400
            ]);
        }

        $order = Order::find($order_id);
        // dd($order);
        if (!$order) {
            return response()->json([
                'message' => 'Order does not exist',
                'status' => 400
            ]);
        }
     
        $address  = $order->address;
        // $state_id = $address->state;
        // $city_id  = $address->city;
        
        $address->load('states', 'citys');

        $addr_string = "Doorflat {$address->doorflat}, ";

        $addr_string = "Doorflat {$address->doorflat}, " .
                   (!empty($address->landmark) ? "{$address->landmark}, " : '') .
                   "{$address->address},{$address->citys->city_name},{$address->states->state_name}, {$address->zipcode}";

        $orderDetails = $order->orderDetails()->orderBy('id', 'DESC')->get();
        $data = [];
        $productdata = [];

        $payment_type = '';

        if ($order->payment_type == 1) {

            $payment_type = $lang != 'hi' ? 'Cash on delivery' : lang_change('Cash on delivery');

        } elseif ($order->payment_type == 2) {

            $payment_type = $lang != 'hi' ? 'Online Payment' : lang_change('Online Payment');

        }

        if($order->delivery_status != 0 ){

          $delivery  = TransferOrder::with('deliveryBoy')->where('order_id' , $order->id)->first();

            $deleveryBoy = [
                'id' => $delivery->deliveryBoy->id,
                'name' => $delivery->deliveryBoy->name,
                'phone' => $delivery->deliveryBoy->phone,
            ];

        }

        foreach ($orderDetails as $detail) {
            $product = $detail->product;

            if (!$product) {
                continue;
            }

            $type = $detail->type;

            if (!$type) {
                continue;
            }
            // $type = Type::where('product_id', $product->id)
            //             ->where('id', $detail->type_id)
            //             ->where('is_active', 1)
            //             ->when($state_id, function ($query, $state_id) {
            //                 return $query->where('state_id', $state_id);
            //             })
            //             ->when($city_id, function ($query, $city_id) {
            //                 return $query->where('city_id', $city_id);
            //             })
            //             ->first();

            $product_name = $lang != "hi" ? $product->name : $product->name_hi;
            $type_name = $lang != "hi" ? $type->type_name : $type->type_name_hi;

            
             $productdata[] = [
                'order_detail_id'  => $detail->id,
                'product_name'     => $product_name,
                'product_image'    => asset($product->img_app1),
                'type_name'        => $type_name,
                'quantity'         => $detail->quantity,
                'quantity_price'   => $detail->amount,
            ];
        }

        $data = [
            'product'          => $productdata,
            'order_id'         => $order->id,
            'subtotal'         => formatPrice($order->sub_total,false),
            'promo_discount'   => formatPrice($order->promo_deduction_amount,false),
            'wallet_discount'  => formatPrice($order->extra_discount,false),
            'delivery_charge'  => formatPrice($order->delivery_charge,false),
            'gift_amount'      => formatPrice($order->gift_amt,false) ?? 0,
            'total_amount'     => $order->total_amount,
            'order_status'     => getOrderStatus($order->order_status),
            'address'          => $addr_string,
            'payment_mod'      => $payment_type,
            'cod_charge'       => $order->cod_charge,
            'order_datetime'   => $order->date,
            'deleveryBoydetail'=> $deleveryBoy
        ];

        return response()->json([
            'message' => 'success',
            'data' => $data,
            'status' => 200
        ]);
    }

    public function cancelOrder(Request $request)

    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'order_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
                'status' => 201,
            ]);
        }

        $user_id = $request->input('user_id');
        $order_id = $request->input('order_id');

        $user = User::find($user_id);

        if (!$user) {
            return response()->json([
                'message' => 'User does not exist',
                'status' => 201,
            ]);
        }

        $order = Order::find($order_id);

        if (!$order) {
            return response()->json([
                'message' => 'Order does not exist',
                'status' => 201,
            ]);
        }
   
        if($order->extra_discount != null){

            $user->wallet_amount += $order->extra_discount;
            $user->save();

            WalletTransactionHistory::createTransaction([
                'user_id' => $user->id,
                'transaction_type' => 'debit',
                'amount' => $order->extra_discount,
                'transaction_date' => now()->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s'),
                'status' => WalletTransactionHistory::STATUS_COMPLETED,
                'description' => "Refunded wallet amount for canceled order ID: {$order->id}",
            ]);

        }
        
        $order->order_status= 5;
        $order->rejected_by = 1;
        $order->rejected_by_id= Auth::user()->id;
        $order->save();
        
        return response()->json([
            'message' => 'Order canceled successfully',
            'status' => 200,
        ]);
    }

    public function trackOrder(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'device_id'=>'required|exists:users,device_id',
            'user_id'  => 'required|exists:users,id',
            'order_id' => 'required',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first(), 'status' => 422], 422);
        }
    
        $userId = $request->input('user_id');
        $orderId = $request->input('order_id');

        $order = Order::with('address')->find($orderId);
    
        if (!$order) {
            return response()->json(['message' => 'Order does not exist', 'status' => 400], 400);
        }
    
        $transfer = TransferOrder::where('order_id', $orderId)->first();
   
        if (!$transfer) {
            return response()->json(['message' => 'Track ID not found!', 'status' => 400], 400);
        }
    
        $deliveryBoy = DeliveryBoy::find($transfer->delivery_user_id);
      
        if (!$deliveryBoy) {
            return response()->json(['message' => 'Delivery boy not found', 'status' => 400], 400);
        }
    
        $data = [
            'deliveryBoy' => [
                'latitude'  => $deliveryBoy->latitude,
                'longitude' => $deliveryBoy->longitude,
            ],
            'user' => [
                'latitude'  => $order->address->latitude,
                'longitude' => $order->address->longitude,
            ],
        ];
    
        return response()->json(['status' => 200, 'data' => $data]);
    }
    
}
