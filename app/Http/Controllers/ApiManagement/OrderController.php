<?php
namespace App\Http\Controllers\ApiManagement;

use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

use App\Models\OrderDetail;

use App\Models\Promocode;

use App\Models\Address;

use App\Models\Order;

use App\Models\Cart;

use App\Models\User;

class OrderController extends Controller
{
    protected $cartController;

    public function __construct(CartController  $cartController)
    {
        $this->cart = $cartController;
    }

    public function checkout(Request $request)
    {
        // Validation rules
        $rules = [
            'user_id'      => 'required|exists:users,id',
            'device_id'    => 'required',
            'address_id'   => 'required|exists:user_address,id',
            'state_id'     => 'required|exists:all_states,id',
            'city_id'      => 'required|exists:all_cities,id',
            'promocode_id' => 'nullable|exists:promocodes,id',
            'gift_card_id' => 'nullable|exists:gift_cards_1,id',
        ];
 
        // Validate request data
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return response()->json(['success' => false, 'errors' => $validator->errors()], 400);

        }

        // Retrieve request inputs
        $userId       = $request->input('user_id');

        $deviceId     = $request->input('device_id');

        $stateId      = $request->input('state_id');

        $cityId       = $request->input('city_id');

        $addressId    = $request->input('address_id');

        $promocodeId  = $request->input('promocode_id');

        $gift_card_id = $request->input('gift_card_id');
    
        

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


        foreach ($cartData as $cartItem) {

            $product = $cartItem->product;

            if (!$product || !$product->is_active) {
                continue;
            }

            $typeData = $product->type->filter(fn ($type) => $cartItem->type_id == $type->id)

                ->map(function ($type) use ($cartItem) {

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

          $orderdetail =  OrderDetail::create([          
                'product_id'            =>  $product->id,
                'type_id'               =>  $cartItem->type_id,
                'type_mrp'              =>  $cartItem->type->mrp,
                'gst'                   =>  $cartItem->type->gst_percentage,
                'gst_percentage_price'  =>  $cartItem->type->gst_percentage_price,
                'quantity'              =>  $cartItem->quantity,
                'amount'                =>  $cartItem->total_qty_price,
                'ip'                    =>  $request->ip(),
                'date'                  =>  now()->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s')
            ]);

        }

        // Fetch user address
        $userAddress = Address::findOrFail($addressId);

        // Calculate shipping charges
        $shippingChargesResponse = calculateShippingCharges($totalWeight, $userAddress->city);

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

        // Apply Gift Card if provided
        if ($gift_card_id) {

            $applyGiftCard = $this->cart->applyGiftCard($totalAmount, $gift_card_id);

            if (!empty($applyGiftCard)) {

                $gift_card_amount     = $applyGiftCard['amount'];

                $gift_card_gst_amount = $applyGiftCard['gst_amount'];

                $gift_card_1     = $applyGiftCard['card_1']['id'];

                $gift_card_2_id     = $applyGiftCard['s_id'];
            }
        }
       
        $order =  Order::create([
            'user_id'                    => $userId ?? Auth::user()->id,
            'total_amount'               => $totalAmount,
            'address_id'                 => $addressId,
            'promocode'                  => $promocodeId ?? '',
            'promo_deduction_amount'     => $deductionAmount,
            'gift_id'                    => $gift_card_1,
            'gift_amt'                   => $gift_card_amount,
            'gift1_id'                   => $gift_card_2_id,
            'gift1_gst_amt'              => $gift_card_gst_amount,
            'delivery_charge'            => $deliveryCharge,
            'order_shipping_amount'      => $deliveryCharge,
            'extra_discount'             => 0,
            'total_order_weight'         => $totalWeight,
            'total_order_mrp'            => round($subtotal),
            'total_order_rate_am'        => round($type_rate_amount),
            'order_price'                => round($subtotal-$type_rate_amount),
            'ten_percent_of_order_price' => round(($subtotal-$type_rate_amount) * 10 / 100),
            'order_main_price'           => round(($subtotal-$type_rate_amount)-(($subtotal-$type_rate_amount) * 10 / 100)),
            'order_status'               => 0,
            'delivery_status'            => 0,
            'payment_type'               => 0,
			'payment_status'             => 0,
            'ip'                         => $request->ip(),
			'date'                       => now()->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s')
       ]);

       if($order){

          OrderDetail::where('id' ,$orderdetail->id)->update(['main_id' => $order->id]);

       }

        // Return the calculated data (or proceed with further processing)
        return response()->json([
            'success'         => true,
            'subtotal'        => $subtotal,
            'shipping_charge' => $deliveryCharge,
            'totalAmount'     => $totalAmount,
            'productData'     => $productData,
            'promo_discount'  => $deductionAmount,
            'total_weight'    => $totalWeight,
            'promo_discount'  => $deductionAmount,
        ]);
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

            $orders = Order::where('user_id', $user->id)->orderBy('id', 'DESC')->get();

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

                    $dataw[] = [
                        'order_id'     => $order->id,
                        'order_status' => $order->order_status,
                        'sub_total'    => $order->sub_total,
                        'amount'       => $order->total_amount,
                        'promocode_discount' => $order->promo_deduction_amount,
                        'delivery_charge' => $order->delivery_charge,
                        'cod_charge'      => $order->cod_charge,
                        'payment_type'    => $payment_type,
                        'date'            => $order->date,
                        'promocode'       => $promo
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

}
