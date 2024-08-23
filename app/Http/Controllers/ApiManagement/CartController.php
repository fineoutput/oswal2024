<?php

namespace App\Http\Controllers\ApiManagement;

use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;

use App\Models\PromocodeApplied;

use Illuminate\Http\Request;

use App\Models\ComboProduct;

use App\Models\GiftCardSec;

use App\Models\EcomProduct;

use App\Models\Promocode;

use App\Models\GiftCard;

use App\Models\CartOld;

use App\Models\Address;

use App\Models\Cart;

use App\Models\Type;

use App\Models\User;


class CartController extends Controller
{

    public function addToCart(Request $request)
    {

        $rules = [
            'device_id'   => 'required|string',
            'user_id'     => 'nullable|string|exists:users,id',
            'category_id' => 'required|string|exists:ecom_categories,id',
            'product_id'  => 'required|string|exists:ecom_products,id',
            'type_id'     => 'required|string',
            'type_price'  => 'required|numeric',
            'quantity'    => 'required|integer|max:' . getConstant()->quantity, 
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
            
            $data['created_at'] = $cur_date;

            // $user_imfo =  User::where('device_id',$data['device_id'])->first();

            // $data['user_id'] = $user_imfo->id;

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
            'user_id'   => 'nullable|integer|exists:users,id',
            'cart_id'   => 'required|integer|exists:carts,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 400);
        }

        $device_id = $request->input('device_id');
        $user_id   = $request->input('user_id');
        $cart_id   = $request->input('cart_id');

        $query = Cart::query()->where(function ($query) use ($user_id, $device_id) {
            $query->Where('device_id', $device_id)
            ->orwhere('user_id', $user_id);
        });

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
            'device_id'       => 'required|string',
            'user_id'         => 'nullable|integer|exists:users,id',
            'lang'            => 'required|string',
            'input_promocode' => 'nullable|string|exists:promocodes,promocode',
            'address_id'      => 'nullable|integer|exists:user_address,id',
            'state_id'        => 'nullable|integer',
            'city_id'         => 'nullable|integer',
            'gift_card_id'    => 'nullable|integer|exists:gift_cards,id',
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

        $cartQuery = Cart::query()
        ->where(function ($query) use ($user_id, $device_id) {
            $query->Where('device_id', $device_id)->orwhere('user_id', $user_id);
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
        $cartItemTotal    = $cartItems->sum('total_qty_price'); //totalamount
        $deliveryCharge   = 0;
        $promocode_id     = null;
        $promo_discount   = 0;
        $extra_discount   = 0;
        $applyGiftCard    = [];
        $applyGiftCardSec = [];
        $promocode_name   = '';

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

                return $this->generateCartResponse($user_id, $device_id, $state_id, $city_id, $lang, $deliveryCharge, $promo_discount, $promocode_id, $promocode_name, $extra_discount, $applyPromocode->original['message'], 200);
            }
        }

        // First Gift Card Detail
        if ($gift_card_id != null) {
            
            $applyGiftCard  = $this->applyGiftCard($cartItemTotal, $gift_card_id);

            if (!$applyGiftCard->original['success']) {

                return $this->generateCartResponse($user_id, $device_id, $state_id, $city_id, $lang, $deliveryCharge, $promo_discount, $promocode_id, $promocode_name, $extra_discount, $applyGiftCard->original['message'], 200);

            }else{
                $applyGiftCard = $applyGiftCard->original['data'];
            }

        }

        // Secound Gift Card Detail
    
        $applyGiftCardSec  = $this->applyGiftCardSec($cartItemTotal);

        if (!$applyGiftCardSec->original['success']) {

            $applyGiftCardSec = [];

        }else{

            $applyGiftCardSec =$applyGiftCardSec->original['gift_detail'];

        }
        
        return $this->generateCartResponse($user_id, $device_id, $state_id, $city_id, $lang, $deliveryCharge, $promo_discount, $promocode_id,$promocode_name, $extra_discount, 'Cart details fetched successfully.', 200, $applyGiftCard, $applyGiftCardSec, $wallet_status);
    }

    public function applyPromocode($deviceId, $userId, $userInputPromoCode, $totalAmount)
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
        // First Gift Card 

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

            $giftCard = GiftCard::where('id' , $gift_card_id)->where('is_Active',1)->first();

            if ($giftCard) {

                $giftCardAmount = round($giftCard->price + ($giftCard->price * 18 / 100), 2);

            }else{
                
                return response()->json(['success' => false, 'message' => 'Gift Card Not Found.'], 400);
                
            }

        }else{
            
            return response()->json(['success' => false, 'message' => 'Your amount is less than the Gift Card minimum amount.'], 400);
        }


        return response()->json([
            'message' => 'gift card applied successfully.',
            'success' => true,
            'data' => [
                'amount' => $giftCardAmount,
                'gst_amount'  =>  ($giftCard->price * 18 / 100),
                'gift_card_status' => $giftCardStatus,
                'promo_card_status' => $promoStatus,
                'gift_detail' => [
                    'id'          => $giftCard->id,
                    'name'        => $giftCard->name,
                    'description' => $giftCard->description,
                    'price'       => $giftCard->price,
                    'image'       => asset($giftCard->image),
                    ]
            ]
        ], 200);
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
    
    public function comboProduct($type_id, $product, $lang)
    {
        $comboProduct = [];

        $comboDetails = ComboProduct::with(['maintype', 'combotype', 'comboproduct'])
        ->where('main_product', $product->id)
        ->first();
    
        if (!$comboDetails) {
            // return response()->json(['message' => 'No combo details found for the given product ID.'], 404);
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
    
    private function generateCartResponse($userId, $deviceId, $stateId, $cityId, $lang, $deliveryCharge, $promo_discount, $promo_id,$promocode_name, $extraDiscount, $message, $status, $applyGiftCard = null, $applyGiftCardSec =null ,$wallet_status=false)
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
        }])
        ->where(function ($query) use ($userId, $deviceId) {
            $query->Where('device_id', $deviceId)->orwhere('user_id', $userId);
        })
        ->get();
        

        $totalWeight = 0;
        $totalAmount = 0;
        $totalSaveAmount = 0;
        $productData = [];
        $walletDescount = 0;
        $totalwalletAmount  = 0;

        foreach ($cartData as $cartItem) {

            $product = $cartItem->product;

            if (!$product || !$product->is_active) {
                continue;
            }

            $comboProduct = $this->comboProduct($cartItem->type_id , $product , $lang);

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
            })->toArray();

            if ($cartItem->type) {

                $totalSaveAmount += $cartItem->quantity * $cartItem->type->del_mrp;
                
                $selectedType = [
                    'type_id' => $cartItem->type->id ??'',
                    'type_name' => $lang !== "hi" ? $cartItem->type->type_name ?? '' : $cartItem->type->type_name_hi ?? '',
                    'type_mrp' => $cartItem->type->del_mrp,
                    'selling_price' => $cartItem->type->selling_price ??'',
                ];
            } else {
                $selectedType = [];
            }
            
            $totalWeight += $cartItem->quantity * (float)$cartItem->type->weight;

            $totalAmount += $cartItem->total_qty_price;

            $productData[] = [
                'id' => $cartItem->id,
                'product_id' => $product->id,
                'category_id' => $cartItem->category_id,
                'selected_type' =>$selectedType,
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
                'type' => $typeData,
                'comboproduct' => $comboProduct
            ];
        }

        if($wallet_status){
            
          $user = User::where('device_id', $deviceId)->orWhere('id', $userId)->first();

          $walletDescount = (float) calculate_wallet_discount($user->wallet_amount);

          $totalwalletAmount = $user->wallet_amount;
        }

        $finalAmount = $totalAmount + $deliveryCharge - $promo_discount - $walletDescount;

        $reponse = [
            'message'          => $message,
            'sucess'           => ($status == 200) ? true : false,
            'data'             => $productData,
            'total_weight'     => $totalWeight,
            'shipping_charge'  => (float)$deliveryCharge,
            // 'promo_discount'   => $promo_discount,
            // 'promo_id'         => $promo_id,
            // 'promo_name'       => $promocode_name,
            // 'wallet_discount'  => $walletDescount,
            // 'extra_discount'   => $extraDiscount,
            // 'total_discount'   => $promo_discount + $extraDiscount + $walletDescount,
            // 'final_amount'     => $finalAmount
        ];

        $reponse['promocode'] = [
            'promo_id'       => $promo_id,
            'promo_discount' => $promo_discount,
            'promo_name'     => $promocode_name,
        ];

        // First Gift Card Detail
        if (!empty($applyGiftCard)) {
            $reponse['promocode_status'] = $applyGiftCard['promo_card_status'];
            $reponse['gift_status']      = $applyGiftCard['gift_card_status'];
            $reponse['gift_card_1']      = [
                  'cal_promo_amu'         => $finalAmount + $applyGiftCard['amount'],
                  'gift_card_amount'      => $applyGiftCard['amount'],
                  'gift_card_gst_amount'  => $applyGiftCard['gst_amount'],
                  'gifd_card_detail'      => $applyGiftCard['gift_detail']
            ];

            $finalAmount += $applyGiftCard['amount'];
        }
        if (!empty($applyGiftCardSec)) {
            $reponse['gift_card2']      = $applyGiftCardSec;
        }
        
        $reponse['wallet_discount']  = $walletDescount;
        $reponse['wallet_amount']    = $totalwalletAmount;
        $reponse['extra_discount' ]  = $extraDiscount;
        $reponse['total_discount' ]  = $promo_discount + $extraDiscount + $walletDescount;
        $reponse['sub_total' ]       = formatPrice($totalAmount,false);
        $reponse['save_total' ]      = formatPrice(($totalSaveAmount - $totalAmount) , false);
        $reponse['final_amount' ]    = formatPrice($finalAmount,false);

        return response()->json($reponse, $status);
    }

   
}
