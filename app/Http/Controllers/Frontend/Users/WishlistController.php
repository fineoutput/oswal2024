<?php
namespace App\Http\Controllers\Frontend\Users;

use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

use App\Models\EcomProduct;

use App\Models\Wishlist;

use App\Models\Cart;

use App\Models\Type;


class WishlistController extends Controller
{
    
    public function store(Request $request) {
       
        $existingWishlist = Wishlist::where('user_id', Auth::user()->id)->where('product_id', $request->product_id)->first();

        $product  = sendProduct($request->category_id, $request->product_id , false);

        if ($existingWishlist) {

            return response()->json(['success' => true, 'message' => 'Product already in Wishlist.', 'data' => $existingWishlist], 200);

        } else if(count($product) <= 0) {

            return response()->json(['success' => false, 'message' => 'Product Not Found.'], 404);

        } else {

            $wishlist = new Wishlist;

            $wishlist->fill($request->all());

            $wishlist->date = now()->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s');

            $wishlist->user_id = Auth::user()->id;

            if($wishlist->save()){

                $Count = Wishlist::where('user_id', Auth::user()->id)->count();

                return response()->json(['success' => true, 'message' => 'Product added to Wishlist successfully.', 'count' => $Count], 201);

            }else{

                return response()->json(['success' => false, 'message' => 'Something went wrong, please try again later.'], 500);

            }

        }

    }

    public function Show(Request $request)
    {
        
        $lang = 'en';

        $productData = [];
   
        $wishlistData = Wishlist::where('user_id', Auth::id())->get();
        
        foreach ($wishlistData as $wishlistItem) {

            $product_id = $wishlistItem->product_id;

            $category_id = $wishlistItem->category_id;

            // Fetch product details
            $products = sendProduct($category_id, $product_id, false);
      
            // If product not found, skip to next iteration
            if (count($products) <= 0) {
                continue;
            }else{

                $product=  $products[0];
            }
            // Initialize an array to hold type data
            $typedata = [];
    
            // Query type data based on state and city (if provided)
            $typeQuery = Type::where('product_id', $product_id)->where('id', $wishlistItem->type_id)
                            ->where('is_active', 1);
    
            if (!empty($state_id)) {
                $typeQuery->where('state_id', $state_id);
            }
    
            if (!empty($city_id)) {
                $typeQuery->where('city_id', $city_id);
            }
    
            $typeData = $typeQuery->get();
    
            // Process each type data
            foreach ($typeData as $type) {
                $percentOff = round((($type->del_mrp - $type->selling_price) * 100) / $type->del_mrp);
    
                $typedata[] = [
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
                    'percent_off' => $percentOff
                ];
            }
    
            // Fetch cart data
            $cartDataQuery = Cart::where('product_id', $product_id);
    
            $cartDataQuery->where('user_id', Auth::user()->id);
            
    
            $cartData = $cartDataQuery->first();

            $cartInfo = [
                'cart_type_id' => $cartData ? $cartData->type_id : "",
                'cart_type_price' => $cartData ? $cartData->type_price : "",
                'cart_quantity' => $cartData ? $cartData->quantity : "",
                'cart_total_price' => $cartData ? $cartData->total_qty_price : "",
                'cart_status' => $cartData ? 1 : 0
            ];

            // Prepare product data
            $productData[] = [
                'wishlist_id' => $wishlistItem->id,
                'type_id' => $wishlistItem->type_id,
                'type_price' => $wishlistItem->type_price,
                'product_id' => $product_id,
                'category_id' => $category_id,
                'product_name' => $lang != "hi" ? $product['name'] : $product['name_hi'],
                'long_desc' => $lang != "hi" ? $product['long_desc'] : $product['long_desc_hi'],
                'url' => $product['url'],
                'image1' => asset($product['img_app1']),
                'image2' => asset($product['img_app2']),
                'image3' => asset($product['img_app3']),
                'image4' => asset($product['img_app4']),
                'is_active' => $product['is_active'],
                'cart_type_id' => $cartInfo['cart_type_id'],
                'cart_type_price' => $cartInfo['cart_type_price'],
                'cart_quantity' => $cartInfo['cart_quantity'],
                'cart_total_price' => $cartInfo['cart_total_price'],
                'cart_status' => $cartInfo['cart_status'],
                'type' => $typedata[0]
            ];
        }
    
        return view('Users.wishlist', compact('productData'))->with('title' ,'wishlist');

    }
    
    public function destroy(Request $request) {

        $wishlist = Wishlist::where('user_id', Auth::user()->id)->where('product_id', $request->product_id)->first();

        if (!$wishlist) {
           
            return response()->json(['success' => true, 'message' => 'Wishlist not found']);
    
        }

        $wishlist->delete();

        $Count = Wishlist::where('user_id', Auth::user()->id)->count();

        return response()->json(['success' => true,'message' => 'Products removed successfully','count' => $Count], 200);
    }

    public function moveToCart(Request $request)
    {
       
        $data = $request->only([
            'device_id', 'user_id', 'wishlist_id', 'type_id', 'type_price', 'cart_from'
        ]);

        $ip = $request->ip();

        $curDate = now()->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s');

        $wishlist = Wishlist::where('user_id', Auth::user()->id)
                            ->where('id', $data['wishlist_id'])
                            ->first();

        if (!$wishlist) {
            return response()->json(['success' => false, 'message' => 'Wishlist item not found.'], 404);
        }

        $product = EcomProduct::where('id', $wishlist->product_id)
                          ->where('category_id', $wishlist->category_id)
                          ->where('is_active', 1)
                          ->first();

        if (!$product) {

            return response()->json(['success' => false, 'message' => 'Product not found'], 404);
        }

        $cartItem = Cart::where('user_id', Auth::user()->id)
                        ->where('product_id', $wishlist->product_id)
                        ->first();

        if ($cartItem) {
            return response()->json(['success' => false, 'message' => 'This Product Already Exists In Cart', 'data' =>  $cartItem], 200);
        }

        $typeData = Type::where('product_id', $product->id)
                        ->where('is_active', 1)
                        ->orWhere(function ($query) use ($data, $wishlist) {
                            $query->where('id', $data['type_id'] ?? $wishlist->type_id);
                        })
                        ->first();

        if (!$typeData) {
            return response()->json(['success' => false, 'message' => 'Type data not found'], 404);
        }

        $totalQtyPrice = $data['type_price'] * 1;

        $cartData = [
            'user_id' => Auth::user()->id,
            'category_id' => $wishlist->category_id,
            'product_id' => $wishlist->product_id,
            'type_id' => $typeData->id,
            'type_price' => $typeData->selling_price,
            'quantity' => 1,
            'total_qty_price' => $totalQtyPrice,
            'cart_from' => 7,
            'ip' => $ip,
            'curr_date' => $curDate,
        ];

        DB::transaction(function () use ($cartData, $data) {
            Cart::create($cartData);
            Wishlist::destroy($data['wishlist_id']);
        });

        return response()->json(['success' => true, 'message' => 'Item moved to cart successfully.'], 201);
    }
    
}
