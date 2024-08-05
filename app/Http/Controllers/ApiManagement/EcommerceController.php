<?php

namespace App\Http\Controllers\ApiManagement;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Route;

use App\Models\EcomProductCategory;

use App\Models\ShippingCharge;

use App\Models\MajorCategory;

use App\Models\MajorProduct;

use App\Models\Type;

use App\Models\Wishlist;

use App\Models\Cart;

use App\Models\ProductRating ;

class EcommerceController extends Controller
{

    public function category(Request $request) {

        $validator = Validator::make($request->all(), [
            'id' => 'nullable|numeric',
            'lang' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 400);
        }

        $categorys = sendCategory($request->id);

        $category_data = [];

        $lang = $request->input('lang');

        foreach ($categorys as $data) {

            $app_img = !empty($data->app_image) ? asset($data->app_image) : "";

            $icon_img = !empty($data->icon) ? asset($data->icon) : "";

            $category_data[] = [
                'id' => $data->id,
                'name' => $lang != "hi" ? $data->name : $data->name_hi,
                'short_desc' => $lang != "hi" ? $data->short_disc : $data->short_disc_hi,
                'long_desc' => $lang != "hi" ? $data->long_desc : $data->long_desc_hi,
                'url' => $data->url,
                'image' => $app_img,
                'icon' => $icon_img ,
                'is_active' => $data->is_active,
            ];
        }
        return response()->json(['success' => true,'data' => $category_data] , 200);

    }

    public function productcategory() {

        $product_categorys = EcomProductCategory::all();

        return response()->json(['success' => true,'data' => $product_categorys] , 200);
    }

    public function products(Request $request)
    {
        $currentRouteName = Route::currentRouteName();
     
        $rules = [
            'device_id'   => 'required|string',
            'user_id'     => 'nullable|integer',
            'lang'        => 'required|string',
            'state_id'    => 'nullable|integer',
            'city_id'     => 'nullable|integer',
            'page'        => 'nullable|integer|min:1',
            'per_page'    => 'nullable|integer|min:1|max:100',  
        ];

        $is_hot = false;

        $is_trn = false;

        $search = false;

        $is_fea = false;
        
        if($currentRouteName == 'ecom.products'){

            // $rules['category_id'] =  'required|integer';

        }else if($currentRouteName == 'ecomm.hot-deals-product'){ 

            $is_hot = true;

        }else if($currentRouteName == 'ecomm.tranding-product'){

           $is_trn =true;

        }else if($currentRouteName == 'ecomm.search-product') {

            // $rules['string'] =  'required';

            $search = $request->string;

        }else if($currentRouteName == 'ecomm.featured-product') {
            
            $is_fea = true;

        }else if($currentRouteName == 'ecomm.related-product'){

            $rules['category_id'] =  'required|integer';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first(), 'status' => 201]);
        }

        $category_id = $request->input('category_id');
        $device_id   = $request->input('device_id');
        $user_id     = $request->input('user_id');
        $lang        = $request->input('lang');
        $state_id    = $request->input('state_id');
        $city_id     = $request->input('city_id');
        $page        = $request->input('page', 1);
        $per_page    = $request->input('per_page', 15);

        $products = sendProduct($category_id, $request->product_id, $request->product_cat_id, $is_hot, $is_trn, $search, $is_fea);

        $total = $products->count();

        $products = $products->slice(($page - 1) * $per_page, $per_page);

        $product_data = [];

        foreach ($products as $product) {
            $typeQuery = Type::where('product_id', $product->id)
                ->where('is_active', 1);

            if ($state_id) {
                $typeQuery->where('state_id', $state_id);
                if ($city_id) {
                    $typeQuery->where('city_id', $city_id);
                }
            } else {
                $typeQuery->groupBy('type_name');
            }

            $types = $typeQuery->get();
            $typedata = [];

            foreach ($types as $type) {
                $percent_off = round((($type->del_mrp - $type->selling_price) * 100) / $type->del_mrp);
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
                    'percent_off' => $percent_off,
                ];
            }

            $wish_status = Wishlist::where('device_id', $device_id)
                ->when($user_id, function ($query) use ($user_id) {
                    $query->orWhere('user_id', $user_id);
                })
                ->where('product_id', $product->id)
                ->exists() ? 1 : 0;

            $cart = Cart::where('device_id', $device_id)
                ->when($user_id, function ($query) use ($user_id) {
                    $query->orWhere('user_id', $user_id);
                })
                ->where('product_id', $product->id)
                ->first();

            $cart_type = $cart ? Type::find($cart->type_id) : null;
            $cart_type_name = $cart_type ? ($lang != "hi" ? $cart_type->type_name : $cart_type->type_name_hi) : "";

            $rating_avg = ProductRating::where('product_id', $product->id)
                ->where('category_id', $product->category_id)
                ->avg('rating');
            $rating_avg = number_format((float)$rating_avg, 1, '.', '');

            $total_reviews = ProductRating::where('product_id', $product->id)
                ->where('category_id', $product->category_id)
                ->count();

            $product_data[] = [
                'id' => $product->id,
                'category_id' => $product->category_id,
                'name' => $lang != "hi" ? $product->name : $product->name_hi,
                'long_desc' => $lang != "hi" ? $product->long_desc : $product->long_desc_hi,
                'cart_type_id' => $cart->type_id ?? "",
                'cart_type_name' => $cart_type_name,
                'cart_type_price' => $cart->type_price ?? "",
                'cart_quantity' => $cart->quantity ?? "",
                'cart_total_price' => $cart->total_qty_price ?? "",
                'cart_status' => $cart ? 1 : 0,
                'wish_status' => $wish_status,
                'rating_status' => $rating_avg > 0 ? 1 : 0,
                'rating' => $rating_avg,
                'total_reviews' => $total_reviews,
                'url' => $product->url,
                'images' => [
                       ['image' => asset($product->img_app1) ],
                       ['image' => asset($product->img_app2) ],
                       ['image' => asset($product->img_app3) ],
                       ['image' => asset($product->img_app4) ],
                    ],
                'is_active' => $product->is_active,
                'type' => $typedata,
                'selected_type_id' => isset($typedata[0]) ? $typedata[0]['type_id'] : '',
                'selected_type_name' => isset($typedata[0]) ? $typedata[0]['type_name'] : '',
                'selected_type_selling_price' => isset($typedata[0]) ? $typedata[0]['selling_price'] : '',
                'selected_type_mrp' => isset($typedata[0]) ? $typedata[0]['type_mrp'] : '',
                'selected_type_percent_off' => isset($typedata[0]) ? $typedata[0]['percent_off'] : '',
            ];
        }

       return response()->json([
            'message' => 'success',
            'status' => 200,
            'data' => $product_data,
            'pagination' => [
                'current_page' => $page,
                'per_page' => $per_page,
                'total' => $total,
                'last_page' => ceil($total / $per_page),
            ]
        ]);
    }
    
    public function type(Request $request) {
        
        $validator = Validator::make($request->all(), [
            'pid'  => 'required|numeric',
            'cid'  => 'required|numeric',
            'tid'  => 'required|numeric',
            'lang' => 'required|string',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 400);
        }
    
        $types = sendType($request->cid, $request->pid, $request->tid);
    
        if ($types->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'No types found'], 404);
        }
    
        $type = $types->first();
    
        $percent_off = round((($type->del_mrp - $type->selling_price) * 100) / $type->del_mrp);

        $typedata = [
            'selected_type_id'              => $type->id,
            'selected_type_name'            => $request->lang != "hi" ? $type->type_name : $type->type_name_hi,
            'selected_type_selling_price'   => $type->selling_price,
            'selected_type_percent_off'     => $percent_off,
        ];
    
        return response()->json(['success' => true, 'type' => $typedata], 200);
    }

    public function shipping_charges(Request $request) {

        $validator = Validator::make($request->all(), [
            'state_id' => 'required|numeric',
            'city_id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 400);
        }
        
        $shippingCharge = ShippingCharge::with('state', 'city')->where('is_active', 1);
 
        if ($request->state_id) {
            $shippingCharge->where('state_id', $request->state_id);
        }

        if ($request->city_id) {
            $shippingCharge->where('city_id', $request->city_id);
        }
        
        $shippingCharge = $shippingCharge->first();

        if ($shippingCharge) {
            
            return response()->json(['success' => true,'shipping_charge' => $shippingCharge], 200);
            
        } else {
            
            return response()->json(['success' => false,'message' => 'Shipping charge not found.'], 404);
            
        }
    }

    // public function major_category(Request $request) {

    //     $validator = Validator::make($request->all(), [
    //         'id' => 'numeric',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['success' => false, 'errors' => $validator->errors()], 400);
    //     }
        
    //     $categorys = MajorCategory::orderby('id', 'desc')->where('is_active' , 1);

    //     if($request->id){
    //         $categorys = $categorys->where('id' , $request->id);
    //     }

    //     $categorys = $categorys->get();

    //     return response()->json(['success' => true,'data' => $categorys] , 200);

    // }

    // public function major_products(Request $request) {
        
    //     $validator = Validator::make($request->all(), [
    //         'pid' =>  'numeric',
    //         'mcid' => 'numeric',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['success' => false, 'errors' => $validator->errors()], 400);
    //     }

    //     $products = MajorProduct::with('majorcategory')->OrderBy('id' ,'Desc')->where('is_active' , 1);

    //     if($request->pid != null){
    //         $products = $products->where('id' , $request->pid);
    //     }
    //     if($request->mcid != null){
    //         $products = $products->where('major_id' , $request->mcid);
    //     }

    //     $products = $products->get();

    //     return response()->json(['success' => true,'data' => $products] , 200);
        
    // }
}
