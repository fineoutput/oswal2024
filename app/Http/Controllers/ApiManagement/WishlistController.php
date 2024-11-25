<?php
namespace App\Http\Controllers\ApiManagement;

use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

use App\Models\EcomProduct;

use App\Models\VendorType;

use App\Models\Wishlist;

use App\Models\Type;
use App\Models\Type_sub;


use App\Models\Cart;


class WishlistController extends Controller
{
    
    public function store(Request $request) {

        $rules = [
            'device_id'  => 'nullable|string|exists:users,device_id',
            'product_id' => 'required|exists:ecom_products,id',
            'category_id'=> 'required|exists:ecom_categories,id',
            'type_id'    => 'required|required|exists:types,id',
            'type_price' => 'required|string',
        ];


        $validator = Validator::make($request->all(),  $rules);

        if ($validator->fails()) {

            return response()->json(['success' => false, 'errors' => $validator->errors()], 400);
        }
        
        if(Auth::check() && Auth::user()->role_type == 2){
            
            $Rtype = Type::find($request->type_id);

            $type = VendorType::where('product_id', $Rtype->product_id)
                ->where('type_name', $Rtype->type_name)
                ->first();

            $typeid = $type->id;

        }else{
            
            $typeid = $request->type_id;
        }
        $user = auth()->user();
        
    
        if($user->id){
            
            $existingWishlist = Wishlist::where('user_id', $user->id)->where('product_id', $request->product_id)->first();

        }else{

            $existingWishlist = Wishlist::where('device_id', $request->device_id)->where('product_id', $request->product_id)->first();

        }

        $product  = sendProduct($request->category_id, $request->product_id , false);

        if ($existingWishlist) {

            return response()->json(['success' => true, 'message' => 'Product already in Wishlist.', 'data' => $existingWishlist], 200);

        } else if(count($product) <= 0) {

            return response()->json(['success' => false, 'message' => 'Product Not Found.'], 404);

        } else {

            $wishlist = new Wishlist;

            $wishlist->fill($request->all());

            $wishlist->type_id = $typeid;
            $wishlist->user_id = $user->id;
            
            $wishlist->date = now()->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s');

            if($wishlist->save()){

                return response()->json(['success' => true, 'message' => 'Product added to Wishlist successfully.', 'data' => $wishlist], 201);

            }else{

                return response()->json(['success' => false, 'message' => 'Something went wrong, please try again later.'], 500);

            }

        }

    }

    public function Show(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lang'      => 'required|string',
            'state_id'  => 'nullable|string',
            'city_id'   => 'nullable|string',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 400);
        }
    
        // Retrieve request parameters
        $device_id = auth()->user()->device_id;
        $user_id   = auth()->user()->id;
        $lang      = $request->lang;
        $state_id  = $request->state_id ?? null;
        $city_id   = $request->city_id ?? null;
    
        
        $productData = [];
    
      
        if (empty($user_id)) {
        
            $wishlistData = Wishlist::where('device_id', $device_id)->get();
        } else {
       
            $wishlistData = Wishlist::where('user_id', $user_id)->get();
           

        }
    
        foreach ($wishlistData as $wishlistItem) {

            $product_id = $wishlistItem->product_id;

            $category_id = $wishlistItem->category_id;

            $products = sendProduct($category_id, $product_id, false);
      
      
            if (count($products) <= 0) {
                continue;
            }else{

                $product=  $products[0];
            }
           
            $typedata = [];
    
          
            if(Auth::check() && Auth::user()->role_type == 2){
                
                $typeData =  VendorType::where('product_id', $product_id)->where('id', $wishlistItem->type_id)
                ->where('is_active', 1)->get();
                // dd($wishlistItem->product_id);
            }else{
             
                $typeData = Type::where('product_id', $product_id)
                            ->where('id', $wishlistItem->type_id)
                            ->where('is_active', 1);

                            if (!empty($state_id)) {
                                $typeData->where('state_id', $state_id);
                            }

                            if (!empty($city_id)) {
                                $typeData->where('city_id', $city_id);
                            }

                            $typeData = $typeData->get();

            }
    
    
           
    
            // Process each type data
            foreach ($typeData as $type) {
    
                if(Auth::check() && Auth::user()->role_type == 2){
                    $subTypes = Type_sub::where('type_id', $type->id)
                    ->get();
                    $range = [];
                    foreach ($subTypes as $subType) {
                        $percentOff = round((($subType->mrp - $subType->selling_price) * 100) / $subType->mrp);
                        $sub_percent_off = ($subType->mrp > 0) ? round((($subType->mrp - $subType->selling_price) * 100) / $subType->mrp) : 0;
                        $range[] = [
                            'type_mrp' => $subType->mrp,
                            'gst_percentage' => $subType->gst_percentage ?? 0,
                            'gst_percentage_price' => $subType->gst_percentage_price ?? 0,
                            'selling_price' => $subType->selling_price,
                            'type_weight' => $subType->weight ?? null,
                            'type_rate' => $subType->rate ?? null,
                            'percent_off' => $sub_percent_off,
                            'start_range' => $subType->start_range ?? 1,
                            'end_range' => $subType->end_range ?? 1000,
                            'percent_off' => $percentOff,

                        ];
                    }
                
                }
                else{
                    $percentOff = round((($type->del_mrp - $type->selling_price) * 100) / $type->del_mrp);

                    $range = [
                        'type_mrp' => $type->del_mrp,
                    'gst_percentage' => $type->gst_percentage,
                    'gst_percentage_price' => $type->gst_percentage_price,
                    'selling_price' => $type->selling_price,
                    'type_weight' => $type->weight,
                    'type_rate' => $type->rate,
                    'percent_off' => $percentOff,
                    ];
                }
                $typedata[] = [
                    'type_id' => $type->id,
                    'type_name' => $lang != "hi" ? $type->type_name : $type->type_name_hi,
                    'type_category_id' => $type->category_id,
                    'type_product_id' => $type->product_id,
                    'range' => $range,
                    
                ];
            }
    
            // Fetch cart data
            $cartDataQuery = Cart::where('product_id', $product_id);
    
            if (empty($user_id)) {
                $cartDataQuery->where('device_id', $device_id);
            } else {
                $cartDataQuery->where('user_id', $user_id);
            }
    
            $cartData = $cartDataQuery->first();
    
            // Prepare cart information
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
                'type' => ($typedata && $typedata[0] != null) ? $typedata[0] : '',
            ];
        }
    
        return response()->json(['success' => true, 'data' => $productData], 200);

    }
    
    public function destroy(Request $request) {
   
        $validator = Validator::make($request->all(), [
            'wishlist_id' => 'required|integer|exists:wishlists,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 400);
        }
        
        $wishlist = Wishlist::find($request->wishlist_id);

        if (!$wishlist) {
           
            return response()->json(['success' => true, 'message' => 'Wishlist not found', 'data' => $wishlist], 404);
    
        }

        $wishlist->delete();

        return response()->json(['success' => true,'message' => 'Wishlist remove successfully'], 200);
    }

    public function moveToCart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            
            'wishlist_id' => 'required|integer',
            'type_id' => 'required|integer',
            'type_price' => 'required|numeric',
            'state_id' => 'nullable|integer',
            'city_id' => 'nullable|integer',
        ]);

        if ($validator->fails()) {

            return response()->json(['success' => false, 'errors' => $validator->errors()], 400);
        }

        $data = $request->only([
            'wishlist_id', 'type_id', 'type_price', 'cart_from', 'state_id', 'city_id'
        ]);

        $ip = $request->ip();
        $device_id = auth()->user()->device_id;
        $user_id = auth()->user()->id;

        $curDate = now()->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s');

        $wishlist = Wishlist::where('device_id', $device_id)
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

        $cartItem = Cart::where('device_id', $device_id)
                        ->where('product_id', $wishlist->product_id)
                        ->first();

        if ($cartItem) {
            return response()->json(['success' => false, 'message' => 'This Product Already Exists In Cart', 'data' =>  $cartItem], 200);
        }

        $typeData = Type::where('product_id', $product->id)
                        ->where('is_active', 1)
                        ->where(function ($query) use ($data) {
                            if ($data['state_id']) {
                                $query->where('state_id', $data['state_id']);
                            }
                            if ($data['city_id']) {
                                $query->where('city_id', $data['city_id']);
                            }
                        })
                        ->orWhere(function ($query) use ($data, $wishlist) {
                            $query->where('id', $data['type_id'] ?? $wishlist->type_id);
                        })
                        ->first();

        if (!$typeData) {
            return response()->json(['success' => false, 'message' => 'Type data not found'], 404);
        }

        $totalQtyPrice = $data['type_price'] * 1;

        $cartData = [
            'device_id' => $device_id,
            'user_id' => $user_id,
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

        return response()->json(['success' => true, 'message' => 'Item moved to cart successfully.', 'data' => $cartData], 201);
    }
    
}
