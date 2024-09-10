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

            $data['user_id'] = Auth::user()->id;

            $identifierColumn = 'user_id';

            $identifierValue = Auth::user()->id;

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

            $cartCount = Cart::where($identifierColumn, $identifierValue)->count();

            return response()->json(['success' => true, 'message' => 'Product added to Cart successfully.', 'count' =>  $cartCount], 201);

        } elseif ($data['quantity'] == 0) {

            $cartItem->delete();

            $cartCount = Cart::where($identifierColumn, $identifierValue)->count();

            return response()->json(['success' => true, 'message' => 'Product remove to Cart successfully.' , 'count' =>  $cartCount], 200);

        } else {

            $cartItem->update($data);

            return response()->json(['success' => true, 'message' => 'Product updated to Cart successfully.', 'data' => $data], 200);
        }
    }

    public function getCartDetails(Request $request)
    {

        if(auth::check()){

            $data['user_id'] = Auth::user()->id;

            $identifierColumn = 'user_id';

            $identifierValue = Auth::user()->id;

            $persistent_id = sendPersistentId($request);

            Cart::where('persistent_id', $persistent_id)->update([
                'user_id' => $identifierValue,
            ]);
        }else{

            $data['persistent_id'] = sendPersistentId($request);

            $identifierColumn = 'persistent_id';

            $identifierValue  = $data['persistent_id'];
        }

        $cartItems = Cart::where($identifierColumn, $identifierValue)->with('type','product','category')->get();

        $cartItems->each(function ($cartItem) {
            if ($cartItem->type) {
                $cartItem->type_price = $cartItem->type->selling_price;
                $cartItem->total_qty_price = $cartItem->quantity * $cartItem->type_price;
                $cartItem->save();
            }
        });

       return view('cart', compact('cartItems'));

    }

    public function updateQty(Request $request)
    {

        $qty = $request->qty;
        $price = $request->price;
        $cart_id = $request->cart_id;

        if ($qty <= 0) {
            return response()->json(['success' => false, 'message' => 'Quantity cannot be zero or less.']);
        }

        $cart = Cart::find($cart_id);

        if (!$cart) {
            return response()->json(['success' => false, 'message' => 'Cart item not found.']);
        }

        $cart->quantity = $qty;
        $cart->total_qty_price = $qty * $price;
        $cart->save();

        return response()->json(['success' => true, 'message' => 'Quantity updated successfully.'], 200);
    }


    public function removeToCart(Request $request)
    {
        if(auth::check()){

            $identifierColumn = 'user_id';

            $identifierValue = Auth::user()->id;

        }else{

            $identifierColumn = 'persistent_id';

            $identifierValue  = sendPersistentId($request);
        }

        $cart_id   = $request->input('cart_id');

        $cart = Cart::query()->where($identifierColumn, $identifierValue)->where('id', $cart_id)->first();

        if ($cart) {

            $cart->delete();

            return response()->json(['success' => true, 'message' => 'Cart remove successfully'], 200);

        } else {

            return response()->json(['success' => true, 'message' => 'Cart not found'], 404);

        }
    }

}
