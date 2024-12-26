<?php

namespace App\Http\Controllers\Frontend;

use App\Models\WalletTransactionHistory;

use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;

use App\Services\RazorpayService;

use App\Models\PromocodeApplied;

use Illuminate\Http\Request;

use App\Models\ComboProduct;

use App\Models\GiftCardSec;

use App\Models\OrderDetail;

use App\Models\Promocode;

use App\Models\GiftCard;

use App\Models\Address;

use App\Models\Order;

use App\Models\Cart;

use App\Models\Type;

class CheckOutController extends Controller
{
    protected $razorpayOrder;

    public function __construct(RazorpayService $razorpayService)
    {
        $this->razorpayService = $razorpayService;
    }

    public function checkout(Request $request)
    {
        
        
        $addressId = $request->input('address_id') ?? session('address_id');

        if ($addressId == null) {

            return redirect()->back()->with('error', 'Address Not Found');

        }

        $totalWeight = 0;

        $subtotal = 0;

        $deductionAmount = 0;

        $type_rate_amount = 0;

        $applyGiftCard = [];

        $applyGiftCardSec = [];

        $walletDescount = 0;

        if ($addressId != session('address_id')) {
            
            session()->forget('address_id');
 
            session()->put('address_id', $addressId);
        }

        if (session()->has('order_id')) {

            $orderId = session('order_id');

            $orderdetails = Order::with('orderDetails', 'orderDetails.product', 'orderDetails.type')->where('id', $orderId)->first();

            if ($orderdetails && $orderdetails->order_status == 0) {

                $cartData = Cart::with('product', 'type')->where('user_id', Auth::user()->id)->get();

                $userAddress = Address::findOrFail($addressId);

                if ($orderdetails->gift1_id) {

                    $giftCard = GiftCardSec::find($orderdetails->gift1_id);

                    $applyGiftCardSec = [
                        'id'          => $giftCard->id,
                        'product_id'  => $giftCard->product_id,
                        'product_name'=> $giftCard->product->name,
                        'type_id'     => $giftCard->type_id,
                        'price'       => $giftCard->price,
                        'image'       => asset($giftCard->image),
                    ];

                }

                return view('checkout', compact('orderdetails', 'cartData', 'userAddress', 'applyGiftCardSec'));

            }

        }

        $userAddress = Address::findOrFail($addressId);

        $cityId       = $userAddress->city;

        $cartData  = Cart::with('product', 'type')->where('user_id', Auth::user()->id)->get();

        $cartData = Cart::with('product', 'type')->where('user_id', Auth::user()->id)->get();

        if ($cartData->isEmpty()) {

            return redirect()->route('/')->with('error', 'Order Not Found');

        }

        $order =  Order::create([
            'order_status'    => 0,
            'delivery_status' => 0,
            'payment_type'    => 0,
            'payment_status'  => 0,
            'ip'              => $request->ip(),
            'order_from'      => 'WebSite',
            'date'            => now()->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s')
        ]);

        session()->put('order_id', $order->id);

        foreach ($cartData as $cartItem) {

            $product = $cartItem->product;

            if (!$product || !$product->is_active) {
                continue;
            }

            $totalWeight += $cartItem->quantity * (float)$cartItem->type->weight;

            $subtotal    += $cartItem->quantity * (float)$cartItem->type->selling_price;

            $type_rate_amount += $cartItem->quantity * (float)$cartItem->type->rate ?? 0;

            // Apply Combo Product if exsit
            $comboProduct =  $this->comboProduct($cartItem->type_id, $product, 'en');

            OrderDetail::create([
                'main_id'               =>  $order->id,
                'product_id'            =>  $product->id,
                'type_id'               =>  $cartItem->type_id,
                'type_mrp'              =>  $cartItem->type->mrp,
                'gst'                   =>  $cartItem->type->gst_percentage,
                'gst_percentage_price'  =>  $cartItem->type->gst_percentage_price,
                'quantity'              =>  $cartItem->quantity,
                'combo_gst'             =>  0,
                'combo_product'         => (count($comboProduct) > 0) ? $comboProduct['product']['product_name'] : '',
                'combo_name'            => (count($comboProduct) > 0) ? $comboProduct['combodetail']['combo_type_name'] : '',
                'combo_type'            => (count($comboProduct) > 0) ? $comboProduct['combodetail']['id'] : '',
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

        // Apply Gift Sec Card if Exsit
        $applyGiftCardSec = $this->applyGiftCardSec($subtotal);

        if ($applyGiftCardSec->original['success']) {

            $applyGiftCardSec = $applyGiftCardSec->original['gift_detail'];
        } else {
            $applyGiftCardSec = [];
        }
// dd($addressId);

        Order::where('id', $order->id)->update([
            'user_id'                    => Auth::user()->id,
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
            'order_price'                => round($subtotal - $type_rate_amount),
            'ten_percent_of_order_price' => round(($subtotal - $type_rate_amount) * 10 / 100),
            'order_main_price'           => round(($subtotal - $type_rate_amount) - (($subtotal - $type_rate_amount) * 10 / 100)),
        ]);

        $orderdetails = Order::with('orderDetails', 'orderDetails.product', 'orderDetails.type')->where('id', $order->id)->first();

        return view('checkout', compact('orderdetails', 'cartData', 'userAddress', 'applyGiftCardSec'));
    }

    public function comboProduct($type_id, $product, $lang)
    {
        $comboProduct = [];

        $comboDetails = ComboProduct::with(['maintype', 'combotype', 'comboproduct'])
            ->where('main_product', $product->id)
            ->first();

        if (!$comboDetails) {

            return $comboProduct;
        }

        $type = Type::find($type_id);

        if ($type && $type->type_name === $comboDetails->maintype->type_name) {
            $comProduct = $comboDetails->comboproduct;
            $combotype = $comboDetails->combotype;

            $comboProduct = [
                'combodetail' => [
                    'id' => $comboDetails->id,
                    'main_type_id' => $comboDetails->maintype->id,
                    'main_type_name' => $comboDetails->maintype->type_name,
                    'combo_type_name' => $combotype->type_name,
                    'combo_type_id' => $combotype->id,
                ],
                'product' => [
                    'product_id' => $comProduct->id,
                    'category_id' => $comProduct->category_id,
                    'product_name' => $lang !== "hi" ? $comProduct->name : $comProduct->name_hi,
                    'long_desc' => $lang !== "hi" ? $comProduct->long_desc : $comProduct->long_desc_hi,
                    'url' => $comProduct->url,
                    'image1' => asset($comProduct->img1),
                    'image2' => asset($comProduct->img2),
                    'image3' => asset($comProduct->img3),
                    'image4' => asset($comProduct->img4),
                    'is_active' => $comProduct->is_active,
                ]
            ];
        }

        return $comboProduct;
    }

    public function applyGiftCardSec($finalAmount)
    {
        $giftCard = GiftCardSec::select('*')
            ->selectRaw('ABS(price - ?) as price_diff', [$finalAmount])
            ->where('price', '<=', $finalAmount)
            ->where('is_active', 1)
            ->orderBy('price_diff')
            ->first();

        if ($giftCard) {

            return response()->json([
                'message' => 'gift card applied successfully.',
                'success' => true,
                'gift_detail' => [
                    'id'          => $giftCard->id,
                    'product_id'  => $giftCard->product_id,
                    'product_name'=> $giftCard->product->name,
                    'type_id'     => $giftCard->type_id,
                    'price'       => $giftCard->price,
                    'image'       => asset($giftCard->image),
                ]
            ], 200);
        } else {
            return response()->json([
                'message' => 'gift card not found',
                'success' => false,
                'gift_detail' => [],
            ], 200);
        }
    }

    public function applyPromocode(Request $request)
    {
        $userInputPromoCode = $request->promoode;

        $subtotalAmount = cleanamount($request->amount);

        $userId = Auth::user()->id ?? 1;

        $orderId = $request->order_id;

        $order = Order::find($orderId);
        
        $promocode = Promocode::where('promocode', $userInputPromoCode)->first();

        if (!$promocode) {
            return response()->json(['success' => false, 'message' => 'Invalid Promocode.']);
        }

        $currentDate = now()->format('Y-m-d');
        if ($currentDate > $promocode->expiry_date) {
            return response()->json(['success' => false, 'message' => 'This Promocode Has Expired.']);
        }

        if ($promocode->type == 1) {

            $promocodeApplied = PromocodeApplied::where('user_id', $userId)->where('promocode_id', $promocode->id)->where('status', '!=', 1)->exists();

            if ($promocodeApplied) {
                return response()->json(['success' => false, 'message' => 'This Promocode Has Been Already Used.']);
            }
        } else {

            $isPromoCodeAppliedToOrder = Order::where('id', $orderId)
                ->where('promocode', $promocode->id)
                ->exists();
    
            if ($isPromoCodeAppliedToOrder) {
                return response()->json(['success' => false, 'message' => 'This Promocode Has Already Been Applied.']);
            }
    
            if ($order && $order->promocode) {
                return response()->json(['success' => false, 'message' => 'Only One Promocode Can Be Applied.']);
            }
        }

        if ($subtotalAmount < $promocode->minimum_amount) {
            return response()->json(['success' => false, 'message' => 'Your amount is less than the promocode minimum amount.']);
        }

        $deductionAmount = ($promocode->percent / 100) * $subtotalAmount;

        if ($deductionAmount > $promocode->maximum_gift_amount) {
            $deductionAmount = $promocode->maximum_gift_amount;
        }

        $totalAmount =  $order->total_amount - $deductionAmount;

        if ($promocode->type == 1) {
            PromocodeApplied::updateOrCreate(
                ['user_id' => $userId, 'order_id' => $orderId, 'promocode_id' => $promocode->id],
                ['status' => 1, 'promocode_discount' => $deductionAmount, 'date' => now()->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s')]
            );
        }

        Order::where('id', $orderId)->update([
            'total_amount' => $totalAmount,
            'promocode'   => $promocode->id,
            'promo_deduction_amount' => $deductionAmount,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Promocode applied successfully.',
            'promo_discount' => formatPrice($deductionAmount),
            'cod_amount'     => formatPrice($totalAmount + 40),
            'prepared_amount'=> formatPrice($totalAmount),
            'promocode_id'  =>  $promocode->id,
            'promocode_name' =>  $promocode->promocode,
        ], 200);
    }

    public function removePromocode(Request $request)
    {
        
        $orderId = $request->input('order_id');

        $userId = Auth::User()->id; 

        $totalAmount = 0;

        if (!$orderId) {
            return response()->json(['success' => false, 'message' => 'Order ID is required.']);
        }

        $order = Order::find($orderId);

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Order not found.']);
        }

        if (!$order->promocode) {
            return response()->json(['success' => false, 'message' => 'No promocode is applied to this order.']);
        }

        $promocode = Promocode::find($order->promocode);

        if ($promocode && $promocode->type == 1) {

            $isPromoCodeUsed = PromocodeApplied::where('user_id', $userId)
            ->where('order_id', $orderId)
            ->where('promocode_id', $promocode->id)
            ->where('status', '!=', 1)
            ->exists();

            if ($isPromoCodeUsed) {
                PromocodeApplied::where('user_id', $userId)
                    ->where('order_id', $orderId)
                    ->where('promocode_id', $promocode->id)
                    ->update(['status' => 0]);
            }
        }

        $totalAmount =  $order->total_amount + $order->promo_deduction_amount;
        
        $order->update([
            'promocode' => null,
            'promo_deduction_amount' => 0,
            'total_amount' =>  $totalAmount,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Promocode removed successfully.',
            'promo_discount' => formatPrice(0),
            'cod_amount'     => formatPrice($totalAmount + 40),
            'prepared_amount'=> formatPrice($totalAmount),
            'promocode_id'  =>  null,
            'promocode_name' =>  'No promo code applied',
        ], 200);
    }

    public function applyGiftCard(Request $request)
    {
        $orderId = $request->order_id;

        $subtotalAmount = cleanamount($request->amount);

        $gift_card_id = $request->gift_card_id;

        $giftCardAmount = 0;

        $giftCardStatus = 0;

        $promoStatus = 1;

        $order = Order::find($orderId);

        $isGiftCardAppliedToOrder = Order::where('id', $orderId)
        ->where('gift_id', $gift_card_id)
        ->exists();

        if($isGiftCardAppliedToOrder){

            return response()->json(['success' => false, 'message' => 'This GiftCard Has Already Been Applied.']);

        }else{
            
            $order = Order::find($orderId);

            if ($order && $order->gift_id) {

                return response()->json(['success' => false, 'message' => 'Only One GiftCard Can Be Applied.']);

            }
        }

        if ($subtotalAmount > getConstant()->gift_min_amount) {

            $promoStatus = DB::table('gift_promo_status')->where('id', 1)->value('is_active');

            $giftCardStatus  = DB::table('gift_promo_status')->where('id', 2)->value('is_active');
        } else {

            $promoStatus = DB::table('gift_promo_status')->where('id', 1)->value('is_active');
        }

        if ($subtotalAmount > getConstant()->gift_min_amount) {

            $giftCard = GiftCard::where('id', $gift_card_id)->where('is_Active', 1)->first();

            if ($giftCard) {

                $giftCardAmount = round($giftCard->price + ($giftCard->price * 18 / 100), 2);

            } else {

                return response()->json(['success' => false, 'message' => 'Gift Card Not Found.']);
            }

        } else {

            return response()->json(['success' => false, 'message' => 'Your amount is less than the Gift Card minimum amount.']);
        }

        $finalAmount = $order->total_amount + $giftCardAmount;

        Order::where('id', $orderId)->update([
            'total_amount'   => $finalAmount,
            'gift_id'        => $giftCard->id,
            'gift_amt'       => $giftCardAmount,
            'gift_gst_amt'   => ($giftCard->price * 18 / 100),
        ]);
        return response()->json([
            'success'        => true,
            'message' => 'gift card applied successfully.',
            'name'        => $giftCard->name,
            'amount'      => formatPrice($giftCardAmount),
            'cod_amount'     => formatPrice($finalAmount + 40),
            'prepared_amount'=> formatPrice($finalAmount),
        ], 200);
    }

    public function removeGiftCard(Request $request)
    {
        $orderId = $request->input('order_id');

        if (!$orderId) {
            return response()->json(['success' => false, 'message' => 'Order ID is required.']);
        }

        $order = Order::find($orderId);
        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Order not found.']);
        }

        if (!$order->gift_id) {
            return response()->json(['success' => false, 'message' => 'No gift card is applied to this order.']);
        }

        $giftCardAmount = $order->gift_amt;

        $totalAmount = $order->total_amount - $giftCardAmount;

        $order->update([
            'total_amount' => $totalAmount,
            'gift_id' => null,
            'gift_amt' => 0,
            'gift_gst_amt' => 0,
        ]);
        return response()->json([
            'success'     => true,
            'message'     => 'Gift card removed successfully.',
            'name'        => 'No Gift Card applied',
            'amount'      => formatPrice(0),
            'cod_amount'     => formatPrice($totalAmount + 40),
            'prepared_amount'=> formatPrice($totalAmount),
        ], 200);

    }

    public function applyWallet(Request $request)
    {
        
        $walletStatus = $request->status;

        $orderId = $request->order_id;

        $order = Order::find($orderId);

        $user = Auth::user();

        $totalAmount = 0;

        $walletDiscount = 0;

        if ($walletStatus == 1) {

            if ($user && $user->wallet_amount > 0) {

                $walletDiscount = calculate_wallet_discount($user->wallet_amount) ;

                $totalAmount = $order->total_amount - $walletDiscount;

                Order::where('id', $orderId)->update([
                    'total_amount'   => $totalAmount,
                    'extra_discount' => $walletDiscount,
                ]);

                $message = 'Wallet discount applied successfully.';
            } else {
                $message = 'Your wallet balance is insufficient.';
            }
        } else {

            $order = Order::find($orderId);

            if ($order) {

                $totalAmount = $order->total_amount + $order->extra_discount;

                $order->update([
                    'total_amount'   => $totalAmount,
                    'extra_discount' => 0,
                ]);

                $message = 'Wallet discount removed successfully.';
            }
        }

        return response()->json([
            'message'  => $message,
            'discount' => formatPrice($walletDiscount),
            'cod_amount'     => formatPrice($totalAmount + 40),
            'prepared_amount'=> formatPrice($totalAmount),
            'wallet_amount' => formatPrice($user->wallet_amount - $walletDiscount)
        ]);
    }

    public function placeOrder(Request $request)
    {
        if($request->address_id != 0){
        $order = Order::where('id', $request->order_id)->first();
         $order->update(['address_id' => $request->address_id]);
        }
        if ($request->payment_option == 1) {

            return $this->codCheckout(intval($request->order_id), $request->payment_option);

        } else {

            return $this->paidCheckout(intval($request->order_id), $request->payment_option);

        }
    }

    public function codCheckout($orderId, $paymentType)
    {

        // Get authenticated user
        $user = Auth::user();

        if (!$user) {
            return response()->json(['successs' => false , 'message' => 'Unauthorized']);
        }

        // Fetch the order
        $order = Order::where('id', $orderId)
            ->where('order_status', 0)
            ->first();

        if (!$order) {
            return response()->json(['successs' => false ,'message' => 'Order not found or invalid status']);
        }

        if ($paymentType != 1) {
            return response()->json(['successs' => false ,'message' => 'Invalid payment type']);
        }

        $maxCodAmount = getConstant()->cod_max_process_amount + getConstant()->cod_charge;
        // return $maxCodAmount;

        if ($order->sub_total > $maxCodAmount) {
            return response()->json([
                'success' => false,
                'message' => "COD not allowed for order above ".formatPrice($maxCodAmount)
            ]);
        }

        // Handle COD payment type
        $codCharge = getConstant()->cod_charge;

        $order->update([
            'order_status'   => 1,
            'payment_type'   => 1,
            'payment_status' => 1,
            'cod_charge'     => $codCharge,
            'total_amount'   => $order->total_amount + $codCharge,
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

            session()->forget('order_id');

            if ($user instanceof \App\Models\User) {

                if ($order->extra_discount != null) {

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
                'form' => 1,
                'order_id' => $order->id,
                'amount' => formatPrice($order->total_amount, false),
                'invoice_number' => $invoiceNumber
            ];

            return response()->json(['success' => true , 'message' => 'Order completed successfully', 'status' => 200, 'data' => $response], 200);
        }

        // Handle case where invoice generation fails
        return response()->json(['success' => false, 'message' => 'Failed to generate invoice', 'status' => 500], 500);
    }

    public function paidCheckout($orderId, $paymentType)
    {

        // Get authenticated user
        $user = Auth::user();

        if (!$user) {
            return response()->json(['success'=> false, 'message' => 'Unauthorized']);
        }

        // Fetch the order
        $order = Order::where('id', $orderId)
            ->where('order_status', 0)
            ->first();

        if (!$order) {
            return response()->json(['success'=> false, 'message' => 'Order not found or invalid status']);
        }

        if ($paymentType != 2) {
            return response()->json(['success'=> false, 'message' => 'Invalid payment type']);
        }

        $razorpayOrder = $this->razorpayService->createOrder($order->total_amount, $order->id);

        $order->update([
            'payment_type'      => 2,
            'razorpay_order_id' => $razorpayOrder->id,
        ]);

        $data = [
            'razor_order_id' => $razorpayOrder->id,
            'amount'         => formatPrice($order->total_amount, false),
            'email'          => $user->email,
            'phone'          => $user->contact,
            'name'           => $user->first_name,
        ];

        return response()->json(['success'=> true, 'data' => $data], 200);
    }

    public function verifyPayment(Request $request)
    {

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
            return response()->json(['success'=> false, 'message' => 'Order not found or invalid status']);
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

                session()->forget('order_id');

                if ($user instanceof \App\Models\User) {

                    if ($order->extra_discount != null) {

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
                'amount' => formatPrice($order->total_amount, false),
                'invoice_number' => $invoiceNumber
            ];

            return view('order_success' , compact('response'))->with('tittle' ,'Order');
            // return response()->json(['message' => 'Payment successful ,Order completed successfully', 'status' => 200, 'data' => $response], 200);
        } else {
            // return response()->json(['status' => false, 'message' =>  $signatureStatus['message'], 'status' => 400,], 400);
        }
    }

    public function orderSuccess($order_id=null) {
        return view('order_success',compact('order_id'))->with('tittle' ,'Order');
    }
}
