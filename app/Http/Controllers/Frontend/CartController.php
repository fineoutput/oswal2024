<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

use App\Models\CartOld;

use App\Models\Cart;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {

        $data = $request->only(['category_id', 'product_id', 'type_id', 'type_price', 'quantity', 'cart_from']);

        $data['total_qty_price'] = $data['type_price'] * $data['quantity'];

        $data['ip'] = $request->ip();
   
        date_default_timezone_set("Asia/Calcutta");

        $cur_date = date("Y-m-d H:i:s");
        
        $data['updated_at'] = $cur_date;

        if(auth::check()){
            
            $data['user_id'] = Auth::id();
            
            $identifierColumn = 'user_id';
            
            $identifierValue = Auth::id();
            
        }else{
            
            $data['persistent_id'] = sendPersistentId($request);
            
            $identifierColumn = 'persistent_id';
            
            $identifierValue  = $data['persistent_id'];
        }

        $backupCartItem = CartOld::where($identifierColumn, $identifierValue)->where('product_id', $data['product_id'])->first();

        if(empty($backupCartItem)) {

            $data['created_at'] = $cur_date;

            CartOld::create($data);

        } elseif ($data['quantity'] == 0) {

            $backupCartItem->delete();

        } else {

            $backupCartItem->update($data);
        }

        $cartItem = Cart::where($identifierColumn, $identifierValue)->where('product_id', $data['product_id'])->first();

        if (empty($cartItem)) {
            
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

    public function removeToCart(Request $request)
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

}
