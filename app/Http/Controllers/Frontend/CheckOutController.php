<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\Address;
use App\Models\Cart;
use App\Models\ComboProduct;
use App\Models\GiftCard;
use App\Models\GiftCardSec;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Promocode;
use App\Models\PromocodeApplied;
use App\Models\Type;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\WalletTransactionHistory;
use App\Services\RazorpayService;

class CheckOutController extends Controller
{
    protected $razorpayOrder;
    
    public function __construct(RazorpayService $razorpayService)
    {
        $this->razorpayService = $razorpayService;
    }

    public function checkout(Request $request)
    {

        $addressId    = $request->input('address_id');

        $userAddress = Address::findOrFail($addressId);

        $cityId       = $userAddress->city;

        $cartData  = Cart::with('product', 'type')->where('user_id', 1)->get();

        $totalWeight = 0;

        $subtotal = 0;

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
            'order_from'      => 'WebSite',
            'date'            => now()->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s')
        ]);

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

        Order::where('id', $order->id)->update([
            'user_id'                    => 1,
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

        return view('checkout', compact('orderdetails', 'cartData', 'userAddress'));
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
                    'id'         => $giftCard->id,
                    'product_id' => $giftCard->product_id,
                    'type_id'    => $giftCard->type_id,
                    'price'       => $giftCard->price,
                    'image'      => asset($giftCard->appimage),
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
        $totalAmount = cleanamount($request->amount);
        $userId = Auth::user()->id ?? 1;
        $orderId = $request->order_id;

        
        $promocode = Promocode::where('promocode', $userInputPromoCode)->first();

        if (!$promocode) {
            return response()->json(['success' => false, 'message' => 'Invalid Promocode.'], 400);
        }

        $currentDate = now()->format('Y-m-d');
        if ($currentDate > $promocode->expiry_date) {
            return response()->json(['success' => false, 'message' => 'This Promocode Has Expired.'], 400);
        }

        if ($promocode->type == 1) {

            $promocodeApplied = PromocodeApplied::where('user_id', $userId)->where('promocode_id', $promocode->id)->where('status', '!=', 1)->exists();

            if ($promocodeApplied) {
                return response()->json(['success' => false, 'message' => 'This Promocode Has Been Already Used.'], 400);
            }
        }

        if ($totalAmount < $promocode->minimum_amount) {
            return response()->json(['success' => false, 'message' => 'Your amount is less than the promocode minimum amount.'], 400);
        }

        $deductionAmount = ($promocode->percent / 100) * $totalAmount;

        if ($deductionAmount > $promocode->maximum_gift_amount) {
            $deductionAmount = $promocode->maximum_gift_amount;
        }

        $totalAmount -= $deductionAmount;

        Order::where('id', $orderId)->update([
            'total_amount' => $totalAmount,
            'promocode'   => $promocode->id,
            'promo_deduction_amount' => $deductionAmount,
        ]);

        return response()->json([
            'message' => 'Promocode applied successfully.',
            'promo_discount' => round($deductionAmount),
            'total_amount'  => formatPrice($totalAmount),
            'promocode_id'  =>  $promocode->id,
            'promocode_name' =>  $promocode->promocode,
        ], 200);
    }

    public function applyGiftCard(Request $request)
    {
        $orderId = $request->order_id;

        $finalAmount = cleanamount($request->amount);

        $gift_card_id = $request->gift_card_id;

        $giftCardAmount = 0;

        $giftCardStatus = 0;

        $promoStatus = 1;

        if ($finalAmount > getConstant()->gift_min_amount) {

            $promoStatus = DB::table('gift_promo_status')->where('id', 1)->value('is_active');

            $giftCardStatus  = DB::table('gift_promo_status')->where('id', 2)->value('is_active');
        } else {

            $promoStatus = DB::table('gift_promo_status')->where('id', 1)->value('is_active');
        }

        if ($finalAmount > getConstant()->gift_min_amount) {

            $giftCard = GiftCard::where('id', $gift_card_id)->where('is_Active', 1)->first();

            if ($giftCard) {

                $giftCardAmount = round($giftCard->price + ($giftCard->price * 18 / 100), 2);
            } else {

                return response()->json(['success' => false, 'message' => 'Gift Card Not Found.'], 400);
            }
        } else {

            return response()->json(['success' => false, 'message' => 'Your amount is less than the Gift Card minimum amount.'], 400);
        }

        $finalAmount += $giftCardAmount;

        Order::where('id', $orderId)->update([
            'total_amount'   => $finalAmount,
            'gift_id'        => $giftCard->id,
            'gift_amt'       => $giftCardAmount,
            'gift_gst_amt'   => ($giftCard->price * 18 / 100),
        ]);
        return response()->json([
            'message' => 'gift card applied successfully.',
            'name'        => $giftCard->name,
            'amount'      => formatPrice($giftCardAmount),
            'total_amount'   => formatPrice($finalAmount),
        ], 200);
    }

    public function applyWallet(Request $request)
    {

        $walletStatus = $request->status;

        $totalAmount = cleanamount($request->amount);

        $orderId = $request->order_id;

        $user = User::find(1);

        if ($walletStatus == 1) {

            if ($user && $user->wallet_amount > 0) {

                $walletDiscount = (float) calculate_wallet_discount($user->wallet_amount);

                $totalAmount -= $walletDiscount;

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

                $totalAmount += $order->extra_discount;

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
            'total_amount' => formatPrice($totalAmount),
            'wallet_amount' => formatPrice($user->wallet_amount - $walletDiscount)
        ]);
    }

    public function placeOrder(Request $request)
    {
       
        if ($request->payment_option == 1) {

            return $this->codCheckout(intval($request->order_id), $request->payment_option);
        } else {
            return $this->paidCheckout(intval($request->order_id), $request->payment_option);
        }
    }

    public function codCheckout($orderId, $paymentType)
    {

        // Get authenticated user
        // $user = Auth::user();
        $user = User::find(1);
        if (!$user) {
            return response()->json(['message' => 'Unauthorized', 'status' => 401], 401);
        }

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

            return response()->json(['message' => 'Order completed successfully', 'status' => 200, 'data' => $response], 200);
        }

        // Handle case where invoice generation fails
        return response()->json(['message' => 'Failed to generate invoice', 'status' => 500], 500);
    }

    public function paidCheckout($orderId, $paymentType)
    {

        // Get authenticated user
        // $user = Auth::user();
        $user = User::find(1);
        if (!$user) {
            return response()->json(['message' => 'Unauthorized', 'status' => 401], 401);
        }

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

        $razorpayOrder = $this->razorpayService->createOrder($order->total_amount, $order->id);

        $order->update([
            'payment_type'      => 2,
            'razorpay_order_id' => $razorpayOrder->id,
        ]);

        $data = [
            'razor_order_id' => $razorpayOrder->id,
            'amount' => formatPrice($order->total_amount, false),
            'email'  => $user->email,
            'phone'  => $user->contact,
            'name'   => $user->first_name,
        ];

        $htmlProducts = view('payment.razorpay', compact('data'))->render();


        return response()->json(['form' => $htmlProducts], 200);
    }

    public function verifyPayment(Request $request)
    {

        // $user = Auth::user();
        $user = User::find(1);
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

            return response()->json(['message' => 'Payment successful ,Order completed successfully', 'status' => 200, 'data' => $response], 200);
        } else {
            return response()->json(['status' => false, 'message' =>  $signatureStatus['message'], 'status' => 400,], 400);
        }
    }
}
