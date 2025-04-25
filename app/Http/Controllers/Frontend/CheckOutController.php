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

use App\Models\Webhook;

use App\Models\Address;

use App\Models\Order;

use App\Models\Cart;

use App\Models\Type;
use App\Models\UserActivity;
use Carbon\Carbon;

class CheckOutController extends Controller
{
    protected $razorpayOrder;

    public function __construct(RazorpayService $razorpayService)
    {
        $this->razorpayService = $razorpayService;
    }

    public function checkout(Request $request)
    {

        $ipAddress = $request->ip();
        
        $currentDate = Carbon::now()->toDateString();

            $existingVisit = UserActivity::where('ip_address', $ipAddress)->where('status', 4)
            ->whereDate('created_at', $currentDate)
                ->first();

            if (!$existingVisit) {
                UserActivity::create([
                    'ip_address' => $ipAddress,
                    'status' => 4,
                ]);

                $request->session()->put('visited_ip', $ipAddress);
            }


        // return $request;
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

            $userAddressid = Address::findOrFail($addressId);

            // $product_id = $orderdetails[0]->product_id;  // Access the product_id directly
            // // or
            // $product_id = $orderdetails[0]->getAttribute('product_id');

            // return $product_id;

            $updateprice = Type::where('product_id',$orderdetails->product_id)->where('state_id',$userAddressid->state)->where('city_id',$userAddressid->city)->get(); 
            // return $updateprice;

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

        $user = Auth::user();
        if($user){
            if($user->role_type == 2){
                $user_type = 'Vendor';
            }else{
                $user_type = 'User';
            }
            }else{
                $user_type = null;
            }
        $order =  Order::create([
            'order_status'    => 0,
            'delivery_status' => 0,
            'payment_type'    => 0,
            'payment_status'  => 0,
            'ip'              => $request->ip(),
            'order_from'      => 'WebSite',
            'date'            => now()->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s'),
            'user_type'       => $user_type,
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

    // public function placeOrder(Request $request)
    // {
    //     $userLocation = $request->input('user_location');

    //     if ($userLocation) {
    //         $location = json_decode($userLocation, true);
    //         $latitude = $location['latitude'];
    //         $longitude = $location['longitude'];
    //     }

    //     if($request->address_id != 0){
    //     $order = Order::where('id', $request->order_id)->first();
    //      $order->update(['address_id' => $request->address_id],['latitude' => $latitude],['longitude' => $longitude]);
    //     }
    //     if ($request->payment_option == 1) {

    //         return $this->codCheckout(intval($request->order_id), $request->payment_option);

    //     } else {

    //         return $this->paidCheckout(intval($request->order_id), $request->payment_option);

    //     }
    // }

    public function placeOrder(Request $request)
{
    // Get the user location from the request
    // $userLocation = $request->input('user_location');

    // // Check if the location is provided
    // if ($userLocation) {
    //     $location = json_decode($userLocation, true);
    //     $latitude = $location['latitude'];
    //     $longitude = $location['longitude'];
    // }

    // Check if address_id is not 0
    if ($request->address_id != 0) {
        // Fetch the order based on order_id
        $order = Order::where('id', $request->order_id)->first();

        // Update the order with address_id, latitude, and longitude
        $order->update([
            'address_id' => $request->address_id,
            // 'latitude' => $latitude,
            // 'longitude' => $longitude,
        ]);
    }

    // Process payment options
    if ($request->payment_option == 1) {
        return $this->codCheckout(intval($request->order_id), $request->payment_option);
    } else {
        return $this->paidCheckout(intval($request->order_id), $request->payment_option);
    }
}

    public function codCheckout($orderId, $paymentType)
    {
        // $blockStart = now()->subHours(2);
        // $blockEnd = $blockStart->copy()->addHours(24);
        
        // if (now()->lessThan($blockEnd)) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Due to a technical issue, you cannot place an order in the next 24 hours.'
        //     ]);
        // }
        // Get authenticated user
        $user = Auth::user();

        if (!$user) {
            return response()->json(['successs' => false , 'message' => 'Unauthorized']);
        }

        // Fetch the order
        $order = Order::where('id', $orderId)
            ->where('order_status', 0)
            ->first();
        
            if ($order->total_order_weight >= 20) {
                return response()->json(['success' => false, 'message' => 'Cart Weight above 20kg is not allowed.']);
            }
            
        if (!$order) {
            return response()->json(['successs' => false ,'message' => 'Order not found or invalid status']);
        }

        if ($paymentType != 1) {
            return response()->json(['successs' => false ,'message' => 'Invalid payment type']);
        }

        $maxCodAmount = getConstant()->cod_max_process_amount;
        // return $maxCodAmount;
        $new = $order->sub_total + getConstant()->cod_charge;
        if ($new > $maxCodAmount) {
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

        // $blockStart = now()->subHours(2);
        // $blockEnd = $blockStart->copy()->addHours(24);
        
        // if (now()->lessThan($blockEnd)) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Due to a technical issue, you cannot place an order in the next 24 hours.'
        //     ]);
        // }
        
        // Get authenticated user
        $user = Auth::user();

        if (!$user) {
            return response()->json(['success'=> false, 'message' => 'Unauthorized']);
        }

        // Fetch the order
        $order = Order::where('id', $orderId)
            ->where('order_status', 0)
            ->first();

            if ($order->total_order_weight >= 20) {
                return response()->json(['success' => false, 'message' => 'Cart Weight above 20kg is not allowed.']);
            }
            

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


    public function verifyPayment(Request $request)
    {

        $user = Auth::user();
        $entityBody = file_get_contents('php://input');
        $body = json_decode($entityBody);

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

            
            // $webhook_data = Webhook::create([
            //     'body'        => $body,
            //     'razor_id'    => $razorpayOrderId,
            //     'paid_amount' => $order->total_amount,
            //     'date'        => Carbon::now()->format('Y-m-d'), 
            // ]);

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
