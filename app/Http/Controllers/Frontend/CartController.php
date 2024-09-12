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

        // dd($request->all());
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
      
        $qty = $request->input('qty');
        $cartId = $request->input('cart_id');
        $typeId = $request->input('type_id');
    
        if ($qty <= 0) {
            return response()->json(['success' => false, 'message' => 'Quantity cannot be zero or less.'], 400);
        }
    
        if (Auth::check()) {
            $identifierColumn = 'user_id';
            $identifierValue = Auth::id(); 
        } else {
            $identifierColumn = 'persistent_id';
            $identifierValue = sendPersistentId($request); 
        }
    
        $cart = Cart::where($identifierColumn, $identifierValue)
                    ->where('id', $cartId)
                    ->first();
    
        if (!$cart) {
            return response()->json(['success' => false, 'message' => 'Cart item not found.'], 404);
        }
    
        $type = sendType(null, null, $typeId)[0] ?? null;
    
        if (!$type) {
            return response()->json(['success' => false, 'message' => 'Type not found.'], 404);
        }
    
        $cart->update([
            'quantity' => $qty,
            'type_id' => $typeId,
            'type_price' => $type->selling_price,
            'total_qty_price' => $qty * $type->selling_price,
        ]);
    
        $totalAmount = Cart::where($identifierColumn, $identifierValue)
                            ->sum('total_qty_price');
    
        return response()->json([
            'success' => true,
            'message' => 'Quantity updated successfully.',
            'selling_price' => formatPrice($type->selling_price),
            'total_qty_price' => formatPrice(($qty * $type->selling_price)),
            'totalamount' => formatPrice($totalAmount),
        ], 200);
    }
    
    public function removeToCart(Request $request, $cart_id = null)
    {
        if(auth::check()){

            $identifierColumn = 'user_id';

            $identifierValue = Auth::user()->id;

        }else{

            $identifierColumn = 'persistent_id';

            $identifierValue  = sendPersistentId($request);
        }

        $cart_id   = $request->input('cart_id') ?? $cart_id;

        
        // $cart = Cart::query()->where($identifierColumn, $identifierValue)->where('product_id', $cart_id)->first();
        $cart = Cart::query()->where($identifierColumn, $identifierValue)->where('id', $cart_id)->first();


        if ($cart) {

            $cart->delete();
            
            $totalAmount = Cart::where($identifierColumn, $identifierValue)
            ->sum('total_qty_price');

            $cartcount = Cart::query()->where($identifierColumn, $identifierValue)->count();
            
            return response()->json(['success' => true, 'message' => 'Cart remove successfully' ,'totalAmount' =>formatPrice($totalAmount) ,'count' => $cartcount], 200);

            // return redirect()->back()->with('success' ,'Cart remove successfully');

        } else {
            // return redirect()->back()->with('error' ,'Cart not found');
            return response()->json(['success' => true, 'message' => 'Cart not found'], 404);

        }
    }

}
