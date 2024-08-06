<?php

namespace App\Http\Controllers\ApiManagement;

use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;

use App\Models\PromocodeApplied;

use Illuminate\Http\Request;

use App\Models\GiftCardSec;

use App\Models\Promocode;

use App\Models\GiftCard;

use App\Models\CartOld;

use App\Models\Address;

use App\Models\Cart;

use App\Models\User;

class CartController extends Controller
{

    public function addToCart(Request $request)
    {

        $rules = [
            'device_id'   => 'required|string|exists:users,device_id',
            'user_id'     => 'nullable|string|exists:users,id',
            'category_id' => 'required|string|exists:ecom_categories,id',
            'product_id'  => 'required|string|exists:ecom_products,id',
            'type_id'     => 'required|string',
            'type_price'  => 'required|numeric',
            'quantity'    => 'required|integer|max:5',
            'cart_from'   => 'required|string',
        ];

        $validator = Validator::make($request->all(),  $rules);

        if ($validator->fails()) {

            return response()->json(['success' => false, 'errors' => $validator->errors()], 400);
        }

        $data = $request->only(['device_id', 'user_id', 'category_id', 'product_id', 'type_id', 'type_price', 'quantity', 'cart_from']);

        $data['total_qty_price'] = $data['type_price'] * $data['quantity'];
        $data['ip'] = $request->ip();
        // $cartModel  = Cart::class;

        date_default_timezone_set("Asia/Calcutta");
        $cur_date = date("Y-m-d H:i:s");
        $data['updated_at'] = $cur_date;

        // Handle backup in CartOld
        $backupCartItem = CartOld::where('device_id', $data['device_id'])

            ->where('product_id', $data['product_id'])

            ->when(!empty($data['user_id']), function ($query) use ($data) {

                $query->where('user_id', $data['user_id']);
            })
            ->first();


        if (empty($backupCartItem)) {

            $data['created_at'] = $cur_date;

            CartOld::create($data);
        } elseif ($data['quantity'] == 0) {

            $backupCartItem->delete();
        } else {

            $backupCartItem->update($data);
        }

        // Handle current cart in Cart
        $cartItem = Cart::where('device_id', $data['device_id'])

            ->where('product_id', $data['product_id'])

            ->when(!empty($data['user_id']), function ($query) use ($data) {

                $query->where('user_id', $data['user_id']);
            })

            ->first();


        if (empty($cartItem)) {
            // dd(  $data);
            $data['created_at'] = $cur_date;

            Cart::create($data);

            return response()->json(['success' => true, 'message' => 'Product added to Cart successfully.', 'data' => $data], 201);

        } elseif ($data['quantity'] == 0) {

            $cartItem->delete();

            return response()->json(['success' => true, 'message' => 'Product remove to Cart successfully.'], 200);
            
        } else {

            $cartItem->update($data);

            return response()->json(['success' => true, 'message' => 'Product updated to Cart successfully.', 'data' => $data], 200);
        }
    }


    public function destroy(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'device_id' => 'required|string',
            'user_id'   => 'nullable|integer',
            'cart_id'   => 'required|integer|exists:carts,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 400);
        }

        $device_id = $request->input('device_id');
        $user_id   = $request->input('user_id');
        $cart_id   = $request->input('cart_id');

        $query = Cart::query();

        if ($user_id) {
            $query->where('user_id', $user_id);
        } else {
            $query->where('device_id', $device_id);
        }

        $cart = $query->where('id', $cart_id)->first();

        if ($cart) {
            $cart->delete();
            return response()->json(['success' => true, 'message' => 'Cart remove successfully'], 200);
        } else {
            return response()->json(['success' => true, 'message' => 'Cart not found'], 404);
        }
    }

    public function getCartDetails(Request $request)
    {

        $rules = [
            'device_id'       => 'required|string|exists:users,device_id',
            'user_id'         => 'nullable|integer|exists:users,id',
            'lang'            => 'required|string',
            'input_promocode' => 'nullable|string|exists:promocodes,promocode',
            'address_id'      => 'nullable|integer|exists:user_address,id',
            'state_id'        => 'nullable|integer',
            'city_id'         => 'nullable|integer',
            'gift_card_id'    => 'nullable|integer|exists:gift_cards_1,id',
            'wallet_status'   => 'required|integer',
        ];

        $validator = Validator::make($request->all(), $rules);


        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 400);
        }

        $device_id       = $request->input('device_id');
        $user_id         = $request->input('user_id');
        $lang            = $request->input('lang');
        $input_promocode = $request->input('input_promocode');
        $address_id      = $request->input('address_id');
        $state_id        = $request->input('state_id');
        $city_id         = $request->input('city_id');
        $gift_card_id    = $request->input('gift_card_id');
        $wallet_status   = $request->input('wallet_status');

        $cartQuery = Cart::query()->when($user_id, function ($query) use ($user_id) {
            return $query->where('user_id', $user_id);
        })->when(!$user_id && $device_id, function ($query) use ($device_id) {
            return $query->where('device_id', $device_id);
        });


        $cartItems = $cartQuery->with(['type' => function ($query) use ($state_id, $city_id) {
            $query->when($state_id, function ($query) use ($state_id, $city_id) {
                $query->where('state_id', $state_id)
                    ->where('city_id', $city_id);
            });
        }])->get();


        if ($cartItems->isEmpty()) {
            return response()->json([
                'message' => 'Your cart is empty.',
                'status' => 400,
            ]);
        }

        $cartItems->each(function ($cartItem) {
            if ($cartItem->type) {
                $cartItem->type_price = $cartItem->type->selling_price;
                $cartItem->total_qty_price = $cartItem->quantity * $cartItem->type_price;
                $cartItem->save();
            }
        });

        // Calculate totals
        $cartItemTotal = $cartItems->sum('total_qty_price'); //totalamount
        $deliveryCharge = 0;
        $promocode_id = null;
        $promo_discount = 0;
        $extra_discount  = 0;
        $applyGiftCard = [];
        $promocode_name = '';

        if (!empty($address_id)) {

            $userAddress = Address::findOrFail($address_id);

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
        if ($input_promocode != null) {

            $applyPromocode = $this->applyPromocode($device_id, $user_id,$input_promocode, $cartItemTotal);

            if ($applyPromocode->original['success']) {

                $promo_discount       = $applyPromocode->original['promo_discount'];

                $promocode_id         = $applyPromocode->original['promocode_id'];

                $promocode_name       = $input_promocode;
            } else {

                return $this->generateCartResponse($user_id, $device_id, $state_id, $city_id, $lang, $deliveryCharge, $promo_discount, $promocode_id, $promocode_name, $extra_discount, $applyPromocode->original['message'], 400);
            }
        }

        if ($gift_card_id != null) {

            $applyGiftCard  = $this->applyGiftCard($cartItemTotal, $gift_card_id);
        }

        return $this->generateCartResponse($user_id, $device_id, $state_id, $city_id, $lang, $deliveryCharge, $promo_discount, $promocode_id,$promocode_name, $extra_discount, 'Cart details fetched successfully.', 200, $applyGiftCard , $wallet_status);
    }

    private function applyPromocode($deviceId, $userId, $userInputPromoCode, $totalAmount)
    {
        $promocode = Promocode::where('promocode', $userInputPromoCode)->first();

        if (!$promocode) {
            return response()->json(['success' => false, 'message' => 'Invalid Promocode.'], 400);
        }

        $currentDate = now()->format('Y-m-d');
        if ($currentDate > $promocode->expiry_date) {
            return response()->json(['success' => false, 'message' => 'This Promocode Has Expired.'], 400);
        }

        if ($promocode->type == 1) {

            $promocodeApplied = PromocodeApplied::where(function ($query) use ($userId, $deviceId) {
                if ($userId) {
                    $query->where('user_id', $userId);
                } else {
                    $query->where('device_id', $deviceId);
                }
            })->where('promocode_id', $promocode->id)->where('status', '!=', 1)->exists();

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

        return response()->json([
            'message' => 'Promocode applied successfully.',
            'success' => true,
            'promo_discount' => round($deductionAmount),
            'promocode_id' =>  $promocode->id,
        ], 200);
    }

    public function applyGiftCard($finalAmount, $gift_card_id)
    {

        $giftCard1 = [];
        $giftCardAmount = 0;
        $giftCardStatus = 0;
        $promoStatus = 1;

        if ($finalAmount > 2000) {
            $promoStatus = DB::table('gift_promo_status')->where('id', 1)->value('is_active');
            $giftCardStatus  = DB::table('gift_promo_status')->where('id', 2)->value('is_active');
        } else {
            $promoStatus = DB::table('gift_promo_status')->where('id', 1)->value('is_active');
        }

        if ($finalAmount > 2000) {

            $giftCard1Data = GiftCard::all();

            foreach ($giftCard1Data as $key) {
                if ($finalAmount >= $key->price) {
                    $giftCard1 = [
                        'id'   => $key->id,
                        'name' => $key->name,
                        'image' => asset($key->image),
                        'price' => $key->price,
                        'gift_card_1_status' => 1
                    ];
                    $giftCardStatus = 1;
                } else {
                    if ($giftCardStatus == 0) {
                        $giftCard1 = [
                            'id'   => '',
                            'name' => '',
                            'image' => '',
                            'price' => '',
                            'gift_card_1_status' => 0
                        ];
                    }
                }
            }
        }

        if (!empty($gift_card_id)) {

            $giftCard = GiftCardSec::findOrFail($gift_card_id);

            if ($giftCard) {
                $giftCardAmount = round($giftCard->price + ($giftCard->price * 18 / 100), 2);
            }
        }

        return [
            's_id' => $gift_card_id,
            'amount' => $giftCardAmount,
            'gst_amount' =>  ($giftCard->price * 18 / 100),
            'gift_card_status' => $giftCardStatus,
            'promo_card_status' => $promoStatus,
            'card_1' => $giftCard1
        ];
    }

    private function generateCartResponse($userId, $deviceId, $stateId, $cityId, $lang, $deliveryCharge, $promo_discount, $promo_id,$promocode_name, $extraDiscount, $message, $status, $applyGiftCard = null ,$wallet_status=false)
    {

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
        }])->where(function ($query) use ($userId, $deviceId) {
            if ($userId) {
                $query->where('user_id', $userId);
            } else {
                $query->where('device_id', $deviceId);
            }
        })->get();

        $totalWeight = 0;
        $totalAmount = 0;
        $productData = [];
        $walletDescount = 0;

        foreach ($cartData as $cartItem) {

            $product = $cartItem->product;

            if (!$product || !$product->is_active) {
                continue;
            }

            $typeData = $product->type->map(function ($type) use ($cartItem, $lang) {

                $totalTypeQuantityPrice = $cartItem->quantity * $type->selling_price;

                return [
                    'type_id' => $type->id,
                    'type_name' => $lang != "hi" ? $type->type_name : $type->type_name_hi,
                    'type_category_id' => $type->category_id,
                    'type_product_id' => $type->product_id,
                    'type_mrp' => $type->del_mrp,
                    'gst_percentage' => $type->gst_percentage,
                    'gst_percentage_price' => $type->gst_percentage_price,
                    'selling_price' => $type->selling_price,
                    'type_weight' => $type->weight,
                    'type_rate' => $type->rate,
                    'total_typ_qty_price' => $totalTypeQuantityPrice
                ];
            });

            $totalWeight += $cartItem->quantity * (float)$cartItem->type->weight;

            $totalAmount += $cartItem->total_qty_price;

            $productData[] = [
                'id' => $cartItem->id,
                'product_id' => $product->id,
                'category_id' => $cartItem->category_id,
                'type_id' => $cartItem->type_id,
                'type_price' => $cartItem->type_price,
                'quantity' => $cartItem->quantity,
                'total_qty_price' => round($cartItem->total_qty_price),
                'product_name' => $lang !== "hi" ? $product->name : $product->name_hi,
                'long_desc' => $lang !== "hi" ? $product->long_desc : $product->long_desc_hi,
                'url' => $product->url,
                'image1' => asset($product->img1),
                'image2' => asset($product->img2),
                'image3' => asset($product->img3),
                'image4' => asset($product->img4),
                'is_active' => $product->is_active,
                'type' => $typeData
            ];
        }

        if($wallet_status){
            
          $user = User::where('device_id', $deviceId)->orWhere('id', $userId)->first();

          $walletDescount = (float) calculate_wallet_discount($user->wallet_amount);

        }

        $finalAmount = $totalAmount + $deliveryCharge - $promo_discount - $walletDescount;

        $reponse = [
            'message'          => $message,
            'sucess'           => ($status == 200) ? true : false,
            'data'             => $productData,
            'total_weight'     => $totalWeight,
            'shipping_charge'  => (float)$deliveryCharge,
            'promo_discount'   => $promo_discount,
            'promo_id'         => $promo_id,
            'promo_name'         => $promocode_name,
            'wallet_discount'  => $walletDescount,
            'extra_discount'   => $extraDiscount,
            'total_discount'   => $promo_discount + $extraDiscount + $walletDescount
        ];

        if (!empty($applyGiftCard)) {
            $reponse['promocode_status'] = $applyGiftCard['promo_card_status'];
            $reponse['cal_promo_amu']    = $finalAmount + $applyGiftCard['amount'];
            $reponse['gift_card_amount'] = $applyGiftCard['amount'];
            $reponse['gift_status']      = $applyGiftCard['gift_card_status'];
            $reponse['gift_card_1']      = $applyGiftCard['card_1'];
            $reponse['gift_card_2_id']   = $applyGiftCard['s_id'];
        }

        return response()->json($reponse, $status);
    }

   
}
