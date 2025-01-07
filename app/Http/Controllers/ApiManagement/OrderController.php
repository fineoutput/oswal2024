<?php
namespace App\Http\Controllers\ApiManagement;

use Illuminate\Support\Facades\Validator;

use App\Models\WalletTransactionHistory;

use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\DB;

use App\Services\RazorpayService;

use App\Services\FirebaseService;

use App\Models\TransferOrder;

use Illuminate\Http\Request;

use Carbon\Carbon;

use App\Models\VendorReward;

use App\Models\DeliveryBoy;

use App\Models\Webhook;

use App\Models\OrderDetail;

use App\Models\Promocode;

use App\Models\Address;

use App\Models\Reward;

use App\Models\Order;

use App\Models\Cart;

use App\Models\User;

use App\Models\Type;
use Illuminate\Support\Facades\Session;
use App\Models\Type_sub;

class OrderController extends Controller
{
    protected $cartController;

    protected $firebaseService;


    public function __construct(CartController $cartController ,RazorpayService $razorpayService ,FirebaseService $firebaseService)
    {
        $this->cart = $cartController;
        $this->razorpayService = $razorpayService;
        $this->firebaseService = $firebaseService;
    }

    public function calculate(Request $request)
    {
        
        $user_id = 0;
        if ($request->header('Authorization')) {
            $auth_token = str_replace('Bearer ', '', $request->header('Authorization'));
            $userDetails = User::where('auth', $auth_token)->first();
            if ($userDetails) {
                $device_id = $userDetails->device_id;
                $user_id = $userDetails->id;
                $role_type = $userDetails->role_type;
            }
        }
        
        $rules = [
            'address_id'      => 'required|exists:user_address,id',
            'promocode'       => 'nullable|string',
            'gift_card_id'    => 'nullable|integer|exists:gift_cards,id',
            'wallet_status'   => 'required|integer',
            'device_id'   => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return response()->json(['success' => false, 'errors' => $validator->errors()]);

        }

// return $user_id;

        $user = User::where('id',$user_id)->first();
        // return $user;

        $deviceId       = $user->device_id;
        // return $deviceId;


        $userId         = $user->id;


        $promocode      = $request->input('promocode');

        $addressId      = $request->input('address_id');

        $gift_card_id   = $request->input('gift_card_id');

        $wallet_status  = $request->input('wallet_status');

        $userAddress  = Address::findOrFail($addressId);

        $stateId      = $userAddress->state;

        $cityId       = $userAddress->city;

        $addressresponse = [
            'user_name' => $user->first_name,
            'address'   => $userAddress->address,
            'state'     => $userAddress->states->state_name,
            'city'      => $userAddress->citys->city_name,
            'zipcode'   => $userAddress->zipcode,
            'email'     => $user->email,
            'phone'     => $user->contact,
        ];

        $cartItems = Cart::query()

            ->where(function ($query) use ($userId, $deviceId) {
                $query->where('device_id', $deviceId)
                    ->orWhere('user_id', $userId);
            })

            ->when(Auth::check() && $user->role_type == 2, function ($query) use ($stateId, $cityId) {
                $query->with(['vendortype' => function ($query) use ($stateId, $cityId) {
                    $query->where('state_id', $stateId)
                        ->where('city_id', $cityId);
                }]);
            })

            ->when(Auth::check() && $user->role_type == 1, function ($query) {
                $query->with(['type' => function ($query) {
                    $query->where('state_id', 29)
                        ->where('city_id', 629);
                }]);
            })

        ->get();


        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'Your cart is empty.', 'status' => 400]);
        }

        $cartItems->each(function ($cartItem) use ($user) {

            if($user->role_type == 2){
                $type_sub_data = Type_sub::where('type_id', $cartItem->type_id)->first();
                // dd($type_sub_data);
                $type = $type_sub_data;
            }
            else{
                $type = $cartItem->type;
            }

            if ($type) {

                $cartItem->type_price = $type->selling_price;

                $cartItem->total_qty_price = $user->role_type == 2 ? $cartItem->quantity * $type->selling_price :$cartItem->quantity * $cartItem->type_price;

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

            // $user = User::find($user_id);

            $total_order_weight = $cartItems->sum(function ($cartItem)  use ($user) {

                $type = $user->role_type == 2 ? $cartItem->vendortype : $cartItem->type;

                return (float)($type->weight ?? 0) * (int)$cartItem->quantity;

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

                return $this->generateResponse($deviceId,$userId,$stateId,$cityId,$wallet_status,$deliveryCharge,$promo_discount,$promocode_id,$promocode_name,$addressresponse,$applyGiftCard,400,$applyPromocode->original['message']);

            }
        }

        if ($gift_card_id != null) {
            
            $applyGiftCard  = $this->cart->applyGiftCard($cartItemTotal, $gift_card_id);

            if (!$applyGiftCard->original['success']) {

                return $this->generateResponse($deviceId,$userId,$stateId,$cityId,$wallet_status,$deliveryCharge,$promo_discount,$promocode_id,$promocode_name,$addressresponse,$applyGiftCard,400,$applyGiftCard->original['message']);

            }else{

                $applyGiftCard = $applyGiftCard->original['data'];
            }

        }
     
        return $this->generateResponse($deviceId,$userId,$stateId,$cityId,$wallet_status,$deliveryCharge,$promo_discount,$promocode_id,$promocode_name,$addressresponse,$applyGiftCard,200,'featch data sucessfully');
    }

    public function generateResponse($deviceId,$userId,$stateId,$cityId,$wallet_status,$deliveryCharge,$promo_discount,$promocode_id,$promocode_name,$addressresponse,$applyGiftCard,$status,$message) {

        if(Auth::check() && Auth::user()->role_type == 2){

            $cartData = Cart::with(['product.vendortype' => function ($query) use ($stateId, $cityId) {
                $query->where('is_active', 1)
                    ->when($stateId, function ($query, $stateId) {
                        return $query->where('state_id', 629);
                    })
                    ->when($cityId, function ($query, $cityId) {
                        return $query->where('city_id', 29);
                    })
                    ->when(is_null($stateId) || is_null($cityId), function ($query) {
                        return $query->groupBy('type_name');
                    });
            }])
            ->where(function ($query) use ($userId, $deviceId) {
                $query->Where('device_id', $deviceId)->orwhere('user_id', $userId);
            })
            ->get();

        }else{

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
        }

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
            // print_r($cartItem->total_qty_price);
            // exit;
            
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
        if($user->role_type == 2){
            $deliveryCharge = 0;
        }

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

        $constant = DB::table('constants')->first();

        // First Gift Card Detail
        if (!empty($applyGiftCard)) {
            if($constant){
                $gift_min_amt = $constant->gift_min_amount;
                if($finalAmount > $gift_min_amt){
                    $reponse['gift_card_1']      = [
                        'cal_promo_amu'         => formatPrice($finalAmount + $applyGiftCard['amount'],false),
                        'gift_card_amount'      => formatPrice($applyGiftCard['amount'],false),
                        'gift_card_gst_amount'  => formatPrice($applyGiftCard['gst_amount'],false),
                  ];
                  $finalAmount += $applyGiftCard['amount'];
                }
                else{
                    $reponse['gift_card_1']      = [
                        'cal_promo_amu'         => 0,
                        'gift_card_amount'      => 0,
                        'gift_card_gst_amount'  => 0,
                  ];
                }
    
            }
           
        }
        if($constant){
            $gift_min_amt = $constant->gift_min_amount;
            if($finalAmount > $gift_min_amt){
                $giftCardStatus = DB::table('gift_promo_status')->where('id', 2)->value('is_active');
            }
            else{
                $giftCardStatus = 0;
            }

        }
        
        if($user->role_type == 2){
            $promoStatus = 2;
            $cod_char = 0;
            $giftCardStatus = 0;
        }
        else{

            $promoStatus = DB::table('gift_promo_status')->where('id', 1)->value('is_active');
            $cod_char = formatPrice(getConstant()->cod_charge,false);

        }
        if($user->role_type == 2){
      
    $cod_final_amount = formatPrice(($finalAmount),false);

        }
        else{
            $cod_final_amount = formatPrice(($finalAmount + getConstant()->cod_charge),false);
        }

        $reponse['wallet_per']  = $constant->wallet_use_amount;
        $reponse['wallet_discount']  = round($walletDescount, 2);
        $reponse['promoStatus']  = $promoStatus == 1 ? 'Active' : 'Inactive';
        $reponse['giftCardStatus']  = $giftCardStatus ==1 ? 'Active' : 'Inactive';
        $reponse['wallet_amount']    = round($totalwalletAmount, 2);
        $reponse['total_discount' ]  = $promo_discount + $walletDescount;
        $reponse['sub_total' ]       = formatPrice($totalAmount,false);
        $reponse['save_total' ]      = formatPrice(($totalSaveAmount - $totalAmount) , false);
        $reponse['prepaid_final_amount']    = formatPrice($finalAmount,false);
        $reponse['cod_charge']    = $cod_char;
        $reponse['cod_final_amount' ]    = $cod_final_amount;
        $reponse['get_online_payment_status' ]    = 1;
        
        return response()->json($reponse);
    }

    public function checkout(Request $request)
    {

                $rules = [
            'address_id'    => 'required|exists:user_address,id',
            'promocode_id'  => 'nullable|exists:promocodes,id',
            'gift_card_id'  => 'nullable|exists:gift_cards,id',
            'wallet_status' => 'required|integer',
            'total_amount'  => 'required|numeric',
            'payment_type'  => 'required|integer'
        ];
 
        // Validate request data
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return response()->json(['success' => false, 'errors' => $validator->errors()], 400);

        }

        $user_id = 0;
        if ($request->header('Authorization')) {
            $auth_token = str_replace('Bearer ', '', $request->header('Authorization'));
            $userDetails = User::where('auth', $auth_token)->first();
            if ($userDetails) {
                $device_id = $userDetails->device_id;
                $user_id = $userDetails->id;
                $role_type = $userDetails->role_type;
            }
        }
        // return $user_id;

        $user = User::where('id',$user_id)->first();
        // return $user;
       
        // Retrieve request inputs
        $userId       =  $user->id;

        $deviceId     =  $user->device_id;


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

        if($user->role_type == 2){

            $cartData = Cart::with(['product.vendortype' => function ($query) use ($stateId, $cityId) {

                $query->where('is_active', 1)
    
                    ->when($stateId, fn ($query) => $query->where('state_id', 29))
    
                    ->when($cityId, fn ($query) => $query->where('city_id', 629))
    
                    ->when(is_null($stateId) || is_null($cityId), fn ($query) => $query->groupBy('type_name'));
    
            }])->where(function ($query) use ($userId, $deviceId) {
    
                    $query->where('user_id', $userId)
    
                        ->orWhere('device_id', $deviceId);
    
                })->get();
        }else{
            

            $cartData = Cart::with(['product.type' => function ($query) use ($stateId, $cityId) {

                $query->where('is_active', 1)
    
                    ->when($stateId, fn ($query) => $query->where('state_id', $stateId))
    
                    ->when($cityId, fn ($query) => $query->where('city_id', $cityId))
    
                    ->when(is_null($stateId) || is_null($cityId), fn ($query) => $query->groupBy('type_name'));
    
            }])->where(function ($query) use ($userId, $deviceId) {
    
                    $query->where('user_id', $userId)
    
                        ->orWhere('device_id', $deviceId);
    
                })->get();
        }
       
        
        if ($cartData->isEmpty()) {
            return response()->json(['message' => 'Order Not Found.', 'status' => 400]);
        }

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

             // Apply Combo Product if exsit
            //  return $user->role_type;
            // return $cartItem->vendortype->weight;
           $comboProduct =  $this->cart->comboProduct($cartItem->type_id, $product, 'en', $user->role_type);
            // return $comboProduct;
            if($user->role_type == 2) {

                $totalWeight += $cartItem->quantity * (float)$cartItem->vendortype->weight;

                $subtotal    += $cartItem->total_qty_price;
            
                $type_rate_amount += $cartItem->quantity * (float)$cartItem->vendortype->rate ?? 0;

                OrderDetail::create([      
                    'main_id'               =>  $order->id,
                    'product_id'            =>  $product->id,
                    'type_id'               =>  $cartItem->type_id,
                    'type_mrp'              =>  $cartItem->vendortype->mrp,
                    'gst'                   =>  $cartItem->vendortype->gst_percentage,
                    'gst_percentage_price'  =>  $cartItem->vendortype->gst_percentage_price,
                    'quantity'              =>  $cartItem->quantity,
                    'combo_gst'             =>  0,
                    'combo_product'         =>  (count($comboProduct) > 0) ? $comboProduct['product']['product_name'] : '',
                    'combo_name'            =>  (count($comboProduct) > 0) ? $comboProduct['combodetail']['combo_type_name'] : '',
                    'combo_type'            =>  (count($comboProduct) > 0) ? $comboProduct['combodetail']['id'] : '',
                    'amount'                =>  $cartItem->total_qty_price,
                    'ip'                    =>  $request->ip(),
                    'date'                  =>  now()->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s')
                ]);

            }else {

                $totalWeight += $cartItem->quantity * (float)$cartItem->type->weight;
    
                $subtotal    += $cartItem->total_qty_price;
            
                $type_rate_amount += $cartItem->quantity * (float)$cartItem->type->rate ?? 0;

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



        }

        // Calculate shipping charges
        $shippingChargesResponse = calculateShippingCharges($totalWeight,  $cityId);

        if (!$shippingChargesResponse->original['success']) {

            return $shippingChargesResponse;
        }
        if($user->role_type == 2){
            $deliveryCharge = 0;
        }
        else{
            
            $deliveryCharge = $shippingChargesResponse->original['total_weight_charge'];
        }
       

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

        // Apply reward if Exsit
        // $this->cart->applyReward($totalWeight, Auth::user()->id, 'checkout' , $order->id);
        
        Order::where('id', $order->id)->update([
            'user_id'                    => $userId ?? $user->id,
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

        //reward work for vendor

        if($payment_type == 1){
          return $this->codCheckout($order->id,$payment_type,$user);
        }else{
          return $this->paidCheckout($order->id,$payment_type,$user);
        }

    }


    public function codCheckout($orderId, $paymentType,$user)
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

        $user = User::where('id',$user->id)->first();
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
            return response()->json(['message' => 'invalid status', 'status' => 404], 404);
        }

        if ($paymentType != 1) {
            return response()->json(['message' => 'Invalid payment type', 'status' => 400], 400);
        }

        if($user->role_type != 2) {

            $maxCodAmount = getConstant()->cod_max_process_amount;
            
            $new = $order->sub_total + getConstant()->cod_charge;
            if ($new > $maxCodAmount) {
                return response()->json([
                    'status' => 400,
                    'message' => "Cod not allowed for order above ₹".formatPrice($maxCodAmount)
                ]);
            }
        }

        // Handle COD payment type
        if($user->role_type == 2){
            $codCharge = 0;

        }
        else{
            $codCharge = getConstant()->cod_charge;
        }

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



        //     $order_id = $orderId;
        //     // return $order_id;

        //     $ip = request()->ip();
 
        //     $cur_date = now();

        //     $addedby = Auth::id();

        //     $order = Order::with('address','user')->find($order_id);

        //     if (!$order) {
        //        Session::flash('emessage', 'Order not found');
        //         return redirect()->back();
        //     }

        //     $pincode = $order->address->zipcode;

        //     if ($order->user->role_type == 2) {
               
        //         $delivery_users = DeliveryBoy::where('role_type', 2)->where('pincode', 'LIKE', "%$pincode%")->where('is_active', 1)->get();

        //     }else{

        //         $delivery_users = DeliveryBoy::where('pincode', 'LIKE', "%$pincode%")->where('is_active', 1)->get();

        //     }

        //     if ($delivery_users->isEmpty()) {
        //         Session::flash('emessage', 'No delivery users available for this pincode');
        //         return redirect()->back();
        //     }

        //     $delivery_user_id = $delivery_users->first()->id;

        //     TransferOrder::where('order_id', $order_id)->delete();

        //     $data_insert = [
        //         'order_id' => $order_id,
        //         'delivery_user_id' => $delivery_user_id,
        //         'status' => 1,
        //         'ip' => $ip,
        //         'added_by' => $addedby,
        //         'date' => $cur_date
        //     ];

        //     $last_id = TransferOrder::create($data_insert)->id;

        //     $order->update(['delivery_status' => 1]);

        //     if ($last_id != 0) {

        //         $delivery_user_data = DeliveryBoy::find($delivery_user_id);

        //         if ($delivery_user_data) {
                       
        //             $title = "New Order Arrived";

        //             $body = "New delivery order transferred to you from admin. Please check.";

        //                 // $payload = [
        //                 //     'message' => [
        //                 //         'token' => $delivery_user_data->fcm_token,
        //                 //         'notification' => [
        //                 //             'body' => "New delivery order transferred to you from admin. Please check.",
        //                 //             'title' => "New Order Arrived",
        //                 //         ],
        //                 //     ],
        //                 // ];

        //                 if($delivery_user_data->fcm_token != null){

        //                     $response = $this->firebaseService->sendNotificationToUser($delivery_user_data->fcm_token, $title, $body);
    
        //                     if(!$response['success']) {
                
        //                         if (!$response['success']) {
                    
        //                             Log::error('FCM send error: ' . $response['error']);
                                    
        //                         }
        //                     }
                            
        //                 }
        //                 // $response = Http::withHeaders([
        //                 //     'Authorization' => 'Bearer ' . $this->googleAccessTokenService->getAccessToken(), 
        //                 //     'Content-Type' => 'application/json',
        //                 // ])->post('https://fcm.googleapis.com/v1/projects/oswalsoap-d8508/messages:send', $payload);
                       
        //                 // if ($response->successful()) {
        //                 //     return $response->body(); 
        //                 // } else {
        //                 //     throw new \Exception('FCM Request failed with status: ' . $response->status() . ' and error: ' . $response->body());
        //                 // }
                    
        //             Session::flash('smessage', 'Order Transferred successfully');
        //             return redirect()->back();

        //         } else {
        //             Session::flash('emessage', 'Delivery user not found');
        //             return redirect()->back();
        //         }
        //     } else {
        //         Session::flash('emessage', 'Sorry, an error occurred');
        //         return redirect()->back();
        //     }
        // // } else {
        // //     return redirect()->route('admin_login');
        // // }
            
            $response = [
                'order_id' => $order->id,
                'amount' => formatPrice($order->total_amount,false),
                'invoice_number' => $invoiceNumber
            ];

            if($user->role_type == 2){

                $this->checkEligibleAndNotify();
            }

            return response()->json(['message' => 'Order completed successfully', 'status' => 200, 'data' => $response], 200);
        }

        // Handle case where invoice generation fails
        return response()->json(['message' => 'Failed to generate invoice', 'status' => 500], 500);
    }

    public function paidCheckout($orderId , $paymentType, $user) {

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
        $user = User::where('id',$user->id)->first();
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

    

    public function verify_payment()
    {
        $entityBody = file_get_contents('php://input');
        $body = json_decode($entityBody);
        //--- insert data in webhook table --------
        date_default_timezone_set("Asia/Calcutta");
        $cur_date = date("Y-m-d H:i:s");
        // print_r($body->payload->order->entity->amount_paid);
        if (!empty($body->event)) {
            $event = $body->event; //order.paid
            if ($event == 'order.paid') {
                $data_insert = array(
                    'body' => $entityBody,
                    'razor_id' => $body->payload->order->entity->id,
                    'paid_amount' => $body->payload->order->entity->amount_paid,
                    'date' => $cur_date
                );
                $last_id = $this->base_model->insert_table("tbl_razor_webhook", $data_insert, 1);
                $status = $body->payload->order->entity->status; //paid
                $razor_id = $body->payload->order->entity->id;
                $paid_amount = $body->payload->order->entity->amount_paid;
                if ($status == 'paid') {
                    $this->db->select('*');
                    $this->db->from('tbl_order1');
                    $this->db->where('txn_id', $razor_id);
                    $order_data = $this->db->get()->row();
                    if ($order_data->order_status == 0) {
                        $online_amount = $paid_amount / 100;
                        //start caculation of amount
                        $type_weight = 0;
                        $total_rate_sell = 0;
                        $final_amount = [];
                        $total_type_wght = 0;
                        $total_type_mrp = 0;
                        $total_amountt = 0;
                        $user_id = $order_data->user_id;
                        $order_id = $order_data->id;
                        $address_id = $order_data->address_id;
                        $this->db->select('*');
                        $this->db->from('tbl_cart');
                        $this->db->where('user_id', $user_id);
                        $cart_data = $this->db->get();
                        $this->db->select('*');
                        $this->db->from('tbl_user_address');
                        $this->db->where('id', $order_data->address_id);
                        $addres = $this->db->get()->row();
                        // print_r($cart_data->result()); die();
                        if (!empty($cart_data)) {
                            foreach ($cart_data->result() as $cart) {
                                // echo $i;
                                $type_id = $cart->type_id;
                                $quantity = $cart->quantity;
                                if (!empty($state_id)) {
                                    $this->db->select('*');
                                    $this->db->from('tbl_type');
                                    $this->db->where('id', $type_id);
                                    $this->db->where('is_active', 1);
                                    // $this->db->group_by('type_name');
                                    $this->db->where('state_id', $addres->state_id);
                                    $this->db->where('city_id', $addres->city_id);
                                    $typeData = $this->db->get()->row();
                                } else {
                                    $this->db->select('*');
                                    $this->db->from('tbl_type');
                                    $this->db->where('id', $type_id);
                                    $this->db->where('is_active', 1);
                                    $this->db->group_by('type_name');
                                    $typeData = $this->db->get()->row();
                                }
                                $type_rate_selling = $typeData->selling_price;
                                $type_wgt = $typeData->weight;
                                $total_weight = $type_wgt * $quantity;
                                $total_type_wght = $total_type_wght + $total_weight;
                                $total_qty_price = $cart->total_qty_price;
                                $total_amountt = $total_amountt + $total_qty_price;
                            }
                        }
                        $total_order_weights = $total_type_wght;
                        $total_order_weight = round($total_type_wght);
                        // $order_main_price= $order_main_prices;
                        $total_amount = $total_amountt;
                        // echo $address_id;
                        //user address shipping Charges
                        // print_r($addres) ; die();
                        if (!empty($addres)) {
                            $stateid = $addres->state;
                            $cityid = $addres->city;
                            $this->db->select('*');
                            $this->db->from('tbl_shipping_charge');
                            $this->db->where('city', $cityid);
                            // $this->db->where('state',$state_id);
                            $shipping_data = $this->db->get()->row();
                            if (!empty($shipping_data)) {
                                $weight1 = $shipping_data->weight1;
                                $shipping_amount1 = $shipping_data->shipping_charge1;
                                // die();
                                $weight2 = $shipping_data->weight2;
                                $shipping_amount2 = $shipping_data->shipping_charge2;
                                $weight3 = $shipping_data->weight3;
                                $shipping_amount3 = $shipping_data->shipping_charge3;
                                $weight4 = $shipping_data->weight4;
                                $shipping_amount4 = $shipping_data->shipping_charge4;
                                $weight5 = $shipping_data->weight5;
                                $shipping_amount5 = $shipping_data->shipping_charge5;
                                $weight6 = $shipping_data->weight6;
                                $shipping_amount6 = $shipping_data->shipping_charge6;
                            } else {
                                $res = array(
                                    'message' => "Shipping services not available in this area.",
                                    'status' => 201
                                );
                                echo json_encode($res);
                            }
                        }
                        // total weight charges acording to admin weight
                        if ($weight1 >= $total_order_weight) {
                            $total_weight_charge = $shipping_amount1;
                        } elseif ($weight2 >= $total_order_weight) {
                            $total_weight_charge = $shipping_amount2;
                        } elseif ($weight3 >= $total_order_weight) {
                            $total_weight_charge = $shipping_amount3;
                        } elseif ($weight4 >= $total_order_weight) {
                            $total_weight_charge = $shipping_amount4;
                        } elseif ($weight5 >= $total_order_weight) {
                            $total_weight_charge = $shipping_amount5;
                        } elseif ($weight6 >= $total_order_weight) {
                            $total_weight_charge = $shipping_amount6;
                        } else {
                            $total_weight_charge = $shipping_amount6;
                        }
                        $extradiscount = 0;
                        $discount_am = 0;
                        $del_charge = $total_weight_charge;
                        $am = $total_amount + $total_weight_charge;
                        $total_order_wegt = number_format((float)$total_order_weight, 1, '.', '');
                        $total_order_weight = $total_order_wegt;
                        $order_shipping_amount = round($total_weight_charge);
                        $delivery_charge = round($del_charge);
                        $total_amount = $total_amount;
                        $sub_total = round($am);;
                        //end caculation of amount
                        //online payment status check
                        $payment_statuss = 1;
                        $order_statuss = 1;
                        // // get and save promocode discount in order start
                        // $this->db->select('*');
                        // $this->db->from('tbl_promocode_applied');
                        // $this->db->where('user_id', $user_id);
                        // $this->db->where('order_id', $order_id);
                        // $promoco_da = $this->db->get()->row();
                        // if (!empty($promoco_da)) {
                        //     $promocode_deduction = $promoco_da->promocode_discount;
                        //     $promocode_id = $promoco_da->promocode_id;
                        // } else {
                        //     $promocode_deduction = 0;
                        //     $promocode_id = '';
                        // }
                        $gift_id = $order_data->gift_id;
                        if (!empty($gift_id)) {
                            $this->db->select('*');
                            $this->db->from('tbl_gift_card');
                            $this->db->where('id', $gift_id);
                            $dsa = $this->db->get();
                            $da = $dsa->row();
                            $gift_price1 = $da->price;
                            $gift_price_gst1 = round($gift_price1 + $gift_price1 * gif_percentage / 100, 2);
                            $total_amount = round($total_amount + $gift_price_gst1);
                            $sub_total = round($sub_total + $gift_price_gst1);
                        } else {
                            $gift_price_gst1 = 0;
                        }
                        //---- start calculate invoice number ----
                        $now = date('y');
                        $next = date('y', strtotime('+1 year'));
                        $N = date('Y', strtotime('+1 year'));
                        $order1 = $this->db->order_by('invoice_no', 'desc')->get_where('tbl_order1', array('payment_status' => 1, 'invoice_year' => $now . '-' . $next, 'year' => $N))->result();
                        if (empty($order1)) {
                            $invoice_year = $now . '-' . $next;
                            $invoice_no = 1;
                        } else {
                            $invoice_year = $now . '-' . $next;
                            $invoice_no = $order1[0]->invoice_no + 1;
                        }
                        //---- end calculate invoice number ----
                        $ip = $this->input->ip_address();
                        date_default_timezone_set("Asia/Calcutta");
                        $cur_date = date("Y-m-d H:i:s");
                        $data_update = array(
                            'total_amount' => $total_amount,
                            'sub_total' => $online_amount,
                            'address_id' => $address_id,
                            'promocode' => $order_data->promocode,
                            'promo_deduction_amount' => $order_data->promo_deduction_amount,
                            'payment_type' => 2,
                            'payment_status' => $payment_statuss,
                            'order_status' => $order_statuss,
                            'delivery_charge' => $delivery_charge,
                            'discount' => "",
                            'extra_discount' => 0,
                            'total_order_weight' => $total_order_weight,
                            'total_order_mrp' => 0,
                            'total_order_rate_am' => 0,
                            'order_price' => 0,
                            'ten_percent_of_order_price' => 0,
                            'order_main_price' => 0,
                            'order_shipping_amount' => $order_shipping_amount,
                            'last_update_date' => $cur_date,
                            'invoice_year' => $invoice_year,
                            'invoice_no' => $invoice_no,
                            'year' => $N,
                            'payment_gateway_amount' => $online_amount,
                            'online_payment_status' => $status,
                            'order_from' => "Application",
                            'gift_id' => $gift_id,
                            'gift_amt' => $gift_price_gst1,
                            'webhook' => 1
                        );
                        $this->db->where('id', $order_id);
                        $zapak = $this->db->update('tbl_order1', $data_update);
                        $this->db->select('*');
                        $this->db->from('tbl_users');
                        $this->db->where('id', $order_data->user_id);
                        $user_datass = $this->db->get()->row();
                        if (!empty($user_datass)) {
                            $first_name = $user_datass->first_name;
                            $email = $user_datass->email;
                            $phone = $user_datass->contact;
                        } else {
                            $first_name = "";
                            $email = "";
                            $phone = "";
                        }
                        //----sent push notification to user---------
                        $this->db->select('*');
                        $this->db->from('tbl_users_device_token');
                        $this->db->where('device_id', $user_datass->device_id);
                        $dsa = $this->db->get();
                        $d_token = $dsa->row();
                        if (!empty($d_token)) {
                            $url = 'https://fcm.googleapis.com/fcm/send';
                            $title = "Order Placed";
                            $message = "Your order has been placed. ";
                            $msg2 = array(
                                'body' => $title,
                                'title' => $message,
                                "sound" => "default"
                            );
                            // echo $user_device_tokens->device_token; die();
                            $fields = array(
                                // 'to'=>"/topics/all",
                                'to' => $d_token->device_token,
                                'notification' => $msg2,
                                'priority' => 'high'
                            );
                            $fields = json_encode($fields);
                            $headers = array(
                                'Authorization: key=' . PUSH_AUTH,
                                'Content-Type: application/json'
                            );
                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_URL, $url);
                            curl_setopt($ch, CURLOPT_POST, true);
                            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
                            $result = curl_exec($ch);
                            // echo $fields;
                            // echo $result;
                            curl_close($ch);
                        }
                        //----- send sms to user --------
                        $msg = "Hello $first_name, your order " . "#" . $order_data->id . " of amount Rs. $online_amount has been received. Thank You for placing the order OSWAL SOAP";
                        $message = urlencode($msg);
                        $dlt = '1207166877911124809';
                        $curl = curl_init();
                        curl_setopt_array($curl, array(
                            CURLOPT_URL => 'http://api.msg91.com/api/sendhttp.php?authkey=' . SMSAUTH . '&mobiles=91' . $phone . '&message=' . $message . '&sender=' . SMSID . '&DLT_TE_ID=' . $dlt . '',
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'GET',
                            CURLOPT_HTTPHEADER => array(
                                'Cookie: PHPSESSID=prqus0jgeu7bi43bp2d1hjgtv0'
                            ),
                        ));
                        $response = curl_exec($curl);
                        curl_close($curl);
                        //---------sent push notification to admin-----------------
                        $url = 'https://fcm.googleapis.com/fcm/send';
                        $title = "New Order";
                        $message = "Order recieved of amount:- Rs." . $online_amount . " and order id:- " . $order_data->id . " ";
                        $msg2 = array(
                            'title' => $title,
                            'body' => $message,
                            "sound" => "default"
                        );
                        $fields = array(
                            // 'to'=>"/topics/all",
                            'to' => "/topics/weather",
                            'notification' => $msg2,
                            'priority' => 'high'
                        );
                        $fields = json_encode($fields);
                        $headers = array(
                            'Authorization: key=' . PUSH_AUTH,
                            'Content-Type: application/json'
                        );
                        $ip = $this->input->ip_address();
                        date_default_timezone_set("Asia/Calcutta");
                        $cur_date = date("Y-m-d H:i:s");
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_POST, true);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
                        $result = curl_exec($ch);
                        curl_close($ch);
                        $notification = [
                            'title' => $title,
                            'message' => $message,
                            'ip' => $ip,
                            'date' => $cur_date
                        ];
                        $last_id = $this->base_model->insert_table("tbl_admin_notification", $notification, 1);
                        $payment_method = 2;
                        date_default_timezone_set("Asia/Calcutta");
                        $cur_date = date("Y-m-d H:i:s");
                        if ($payment_method == 1) {
                            $method = "Cash On Delivery";
                        } else {
                            $method = "Online Payment";
                        }
                        $this->db->select('*');
                        $this->db->from('tbl_order2');
                        $this->db->where('main_id', $order_data->id);
                        $orderr_prodct = $this->db->get();
                        // print_r($orderr_prodct);  die();
                        $products = '';
                        if (!empty($orderr_prodct)) {
                            foreach ($orderr_prodct->result() as $ordr_p) {
                                $orderr_prodct_quantity = $ordr_p->quantity;
                                $orderr_prodct_amount = $ordr_p->amount;
                                $this->db->select('*');
                                $this->db->from('tbl_ecom_products');
                                $this->db->where('id', $ordr_p->product_id);
                                $op_data = $this->db->get()->row();
                                if (!empty($op_data)) {
                                    $product_name = $op_data->name;
                                    $image = $op_data->img1;
                                } else {
                                    $product_name = "";
                                    $image = "";
                                }
                                $this->db->select('*');
                                $this->db->from('tbl_type');
                                $this->db->where('id', $ordr_p->type_id);
                                $p_t_data = $this->db->get()->row();
                                if (!empty($p_t_data)) {
                                    $type_name = $p_t_data->type_name;
                                } else {
                                    $type_name = "";
                                }
                                $p = $product_name . "(" . $type_name . " x " . $orderr_prodct_quantity . "), ";
                                $products = $products . $p;
                            }
                        }
                        ///----- send whatsapp msg to admin ----------
                        $others = '';
                        $curl = curl_init();
                        curl_setopt_array($curl, array(
                            CURLOPT_URL => 'https://www.fineoutput.com/Whatsapp/send_order_message',
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'POST',
                            CURLOPT_POSTFIELDS => 'phone=' . WHATSAPP_NUMBERS . '&order_id=' . $order_data->id . '&amount=' . $online_amount . '&date=' . $cur_date . '&method=' . $method . '&products=' . $products . '&customer_name=' . $first_name . '&others=' . $others . '',
                            CURLOPT_HTTPHEADER => array(
                                'token:' . TOKEN . '',
                                'Content-Type:application/x-www-form-urlencoded',
                                'Cookie:ci_session=e40e757b02bc2d8fb6f5bf9c5b7bb2ea74c897e8'
                            ),
                        ));
                        $respons = curl_exec($curl);
                        curl_close($curl);
                        $res = array(
                            'message' => 'success',
                            'status' => 200
                        );
                        echo json_encode($res);
                    }
                } else {
                    $res = array(
                        'message' => 'Payment Failed',
                        'status' => 201
                    );
                    echo json_encode($res);
                }
            }
        }
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

        $entityBody = file_get_contents('php://input');
        $body = json_decode($entityBody);
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

            $webhook_data = Webhook::create([
                'body'        => $body,
                'razor_id'    => $razorpayOrderId,
                'paid_amount' => $order->total_amount,
                'date'        => Carbon::now()->format('Y-m-d'), 
            ]);

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

            if(Auth::check() && Auth::user()->role_type == 2){

                $this->checkEligibleAndNotify();
            }

            return response()->json(['message' => 'Payment successful ,Order completed successfully', 'status' => 200, 'data' => $response], 200);
        } else {
            return response()->json(['status'=> false, 'message' =>  $signatureStatus['message'] ,'status' => 400,], 400);
        }
    }

    public function orders(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lang'         => 'required|string',
            'device_id'   => 'string',
        ]);

        if ($validator->fails()) {

            return response()->json(['message' => $validator->errors()->first(), 'status' => 400]);
        }

        $user_id = 0;
        $role_type = 1;
        $userDetails = null;
    if ($request->header('Authorization')) {
        $auth_token = str_replace('Bearer ', '', $request->header('Authorization'));
        $userDetails = User::where('auth', $auth_token)->first();
        if ($userDetails) {
            $device_id = $userDetails->device_id;
            $user_id = $userDetails->id;
            $role_type = $userDetails->role_type;
        }
    }
   

        if ($user_id == null && ($user_id && $userDetails->role_type == 2)) {

            return response()->json(['success' => false, 'message' => 'Please log in first, then proceed to add the product.' ]);

        }


        $lang    = $request->input('lang');

        $user = User::find($user_id);

        $dataw = [];

        

        if ($user) {

            $orders = Order::with('orderDetails.product')->where('user_id', $user_id)->where('order_status', '!=', 0)->orderBy('id', 'DESC')->get();
            
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
                    $tracktransfer = TransferOrder::orderBy('id','DESC')->where('order_id',$order->id)->first();
                    $track_status = $tracktransfer ? $tracktransfer->status : null;
                    // return $tracktransfer->status;
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
                        'track_status' =>  $track_status,
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

        
        $user_id = 0;
        if ($request->header('Authorization')) {
            $auth_token = str_replace('Bearer ', '', $request->header('Authorization'));
            $userDetails = User::where('auth', $auth_token)->first();
            if ($userDetails) {
                $device_id = $userDetails->device_id;
                $user_id = $userDetails->id;
                $role_type = $userDetails->role_type;
            }
        }

        $validator = Validator::make($request->all(), [
            'order_id'  => 'required',
            'lang'      => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([ 'message' => $validator->errors()->first(), 'status' => 422],422);
        }

        // $user_id = $user_id;
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

            if($user->role_type == 2){

                $type = $detail->vendortype;

            }else{

                $type = $detail->type;
            }

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

        $user_id = 0;
        if ($request->header('Authorization')) {
            $auth_token = str_replace('Bearer ', '', $request->header('Authorization'));
            $userDetails = User::where('auth', $auth_token)->first();
            if ($userDetails) {
                $device_id = $userDetails->device_id;
                $user_id = $userDetails->id;
                $role_type = $userDetails->role_type;
            }
        }


        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
                'status' => 201,
            ]);
        }

        // $user_id = auth()->user()->id;
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
                'user_id' => $user_id,
                'transaction_type' => 'debit',
                'amount' => $order->extra_discount,
                'transaction_date' => now()->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s'),
                'status' => WalletTransactionHistory::STATUS_COMPLETED,
                'description' => "Refunded wallet amount for canceled order ID: {$order->id}",
            ]);

        }
        
        $order->order_status= 5;
        $order->rejected_by = 1;
        $order->rejected_by_id= $user->id;
        $order->save();
        
        return response()->json([
            'message' => 'Order canceled successfully',
            'status' => 200,
        ]);
    }

    public function trackOrder(Request $request)
    {
        
        $validator = Validator::make($request->all(), [

            'order_id' => 'required',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first(), 'status' => 422], 422);
        }
    
        $userId = auth()->user()->id;
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
                'latitude'  => (float)$deliveryBoy->latitude,
                'longitude' => (float)$deliveryBoy->longitude,
                 'detail' => [
                    'name'     => $deliveryBoy->name,
                    'image'    => asset($deliveryBoy->image),
                    'phone_no' => $deliveryBoy->phone,
                 ],
            ],
            'user' => [
                'latitude'  => (float)$order->address->latitude,
                'longitude' => (float)$order->address->longitude,
                 'address' => [
                        'address' =>$order->address->address,
                        'landmark' =>$order->address->landmark,
                        'doorflat' =>$order->address->doorflat,
                        'city' => $order->address->citys->city_name,
                        'state' => $order->address->states->state_name,
                        'zipcode' => $order->address->zipcode,

                 ]
            ],
        ];
    
        return response()->json(['status' => 200, 'data' => $data]);
    }
    
    private function checkEligibleAndNotify() {
      
        $rewardlists = Reward::where('is_active', 1)->orderBy('id', 'desc')->get();
        $user = Auth::user();
    
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not authenticated']);
        }

        $totalWeight = $user->orders->sum('total_order_weight');
        $eligibleRewards = [];
        $notificationSent = false;
    
        foreach ($rewardlists as $reward) {
          
            $vendorStatus = VendorReward::where('reward_id', $reward->id)
                ->where('vendor_id', $user->id)
                ->first();
    
            if ($vendorStatus) {
                if ($vendorStatus->status == 1) {
                    $status = 'applied';
                } elseif ($vendorStatus->status == 2) {
                    $status = 'accepted';
                } elseif ($totalWeight >= $reward->weight) {
                    $status = 'eligible';
                    $eligibleRewards[] = $reward; 
                } else {
                    $status = 'not eligible';
                }
            } elseif ($totalWeight >= $reward->weight) {
                $status = 'eligible';
                $eligibleRewards[] = $reward; 
            } else {
                $status = 'not eligible';
            }
    
        }
    
        if (!empty($eligibleRewards) && !$notificationSent) {

            $this->sendPushNotification($user->fcm_token);

            $notificationSent = true; 
        }
    
    }

    private function sendPushNotification($fcm_token) {

        $title = 'ðŸŽ‰ Reward Alert! ðŸŽ‰';
        $message = 'Congratulations! You are now eligible for a special reward! Tap to claim it now.';

        if($fcm_token != null){

            $response = $this->firebaseService->sendNotificationToUser($fcm_token, $title, $message);
    
            if(!$response['success']) {
                
                if (!$response['success']) {
    
                    Log::error('FCM send error: ' . $response['error']);
                    
                }
            }
        }
        
    }

   

}
