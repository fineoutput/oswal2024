<?php
namespace App\Http\Controllers\ApiManagement;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;
use App\Models\EcomProductCategory;
use App\Models\ShippingCharge;
use App\Models\MajorCategory;
use App\Models\ProductRating;
use App\Models\MajorProduct;
use Illuminate\Http\Request;
use App\Models\VendorType;
use App\Models\Wishlist;
use App\Models\Type;
use App\Models\Type_sub;
use App\Models\User;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;
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

            $banner_img = !empty($data->image) ? asset($data->image) : "";

            $icon_img = !empty($data->icon) ? asset($data->icon) : "";

            $category_data[] = [
                'id' => $data->id,
                'name' => $lang != "hi" ? $data->name : $data->name_hi,
                'short_desc' => $lang != "hi" ? $data->short_disc : $data->short_disc_hi,
                'long_desc' => $lang != "hi" ? $data->long_desc : $data->long_desc_hi,
                'url' => $data->url,
                'image' => $app_img,
                'banner_image' => $banner_img,
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
        'lang'      => 'required|string',
        'state_id'  => 'nullable|integer',
        'city_id'   => 'nullable|integer',
        'page'      => 'nullable|integer|min:1',
        'per_page'  => 'nullable|integer|min:1|max:100',
    ];

    $is_hot = false;
    $is_trn = false;
    $is_fea = false;
    $search = false;

    $device_id = null;
    $user_id = null;
    if ($request->header('Authorization')) {
        $auth_token = str_replace('Bearer ', '', $request->header('Authorization'));
        $userDetails = User::where('auth', $auth_token)->first();
        if ($userDetails) {
            $device_id = $userDetails->device_id;
            $user_id = $userDetails->id;
        }
    }

    switch ($currentRouteName) {
        case 'ecomm.products':
            $rules['type'] = 'nullable|string';

            switch ($request->type) {
                case 'tranding':
                    $rules['category_id'] = 'required|integer';
                    $is_trn = true;
                    break;

                case 'featured':
                    $rules['category_id'] = 'required|integer';
                    $is_fea = true;
                    break;

                case 'hot-product':
                    $rules['category_id'] = 'required|integer';
                    $is_hot = true;
                    break;

                case 'all':
                default:
                    break;
            }
            break;

        case 'ecomm.hot-deals-product':
            $is_hot = true;
            break;

        case 'ecomm.tranding-product':
            $is_trn = true;
            break;

        case 'ecomm.search-product':
            $search = $request->string;
            break;

        case 'ecomm.featured-product':
            $is_fea = true;
            break;

        case 'ecomm.related-product':
            $rules['category_id'] = 'required|integer';
            break;

        case 'ecomm.category-product':
            $rules['category_id'] = 'required|integer';
            break;

        case 'ecomm.details-product':
            $rules['product_id'] = 'required|integer';
            $rules['type_id']    = 'nullable|integer';
            break;
    }

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        return response()->json(['message' => $validator->errors()->first(), 'status' => 201]);
    }

    $user = $user_id ? User::find($user_id) : null;
    // print_r($user);
    // exit;
    // Check user role type if logged in
    $roleType = $user ? $user->role_type : null;

    $category_id = $request->input('category_id');
    $lang = $request->input('lang');
    $state_id = $request->input('state_id');
    $city_id = $request->input('city_id');
    $page = $request->input('page', 1);
    $per_page = $request->input('per_page', 15);

    // Fetch the products
    $products = sendProduct($category_id, $request->product_id, $request->product_cat_id, $is_hot, $is_trn, $search, $is_fea, false, $roleType);

    $total = $products->count();

    $products = $products->slice(($page - 1) * $per_page, $per_page);

    $product_data = [];

    foreach ($products as $product) {
        // If the user is logged in, we add extra data like wishlist, cart, ratings, etc.
        $wishlist = $user_id ? Wishlist::where('product_id', $product->id)->where('user_id', $user_id)->first() : null;
        $wish_status = $wishlist ? 1 : 0;
        $wishlist_id = $wishlist ? $wishlist->id : null;

        $cart = $user_id ? Cart::where('product_id', $product->id)->where('user_id', $user_id)->where('device_id', $device_id)->first() : null;
        $cart_type_name = $cart ? ($lang !== "hi" ? $cart->type->type_name : $cart->type->type_name_hi) : '';
        $cart_type_price = $cart ? $cart->type_price : null;
        $cart_quantity = $cart ? $cart->quantity : null;
        $cart_total_price = $cart ? $cart->total_qty_price : null;
        $cart_status = $cart ? 1 : 0;

        $rating_avg = ProductRating::where('product_id', $product->id)->where('category_id', $product->category_id)->avg('rating');
        $rating_avg = number_format((float)$rating_avg, 1, '.', '');
        $total_reviews = ProductRating::where('product_id', $product->id)->where('category_id', $product->category_id)->count();

        // Get product types
        $typedata = $this->fetchProductTypes($product->id, $roleType, $state_id, $city_id, $lang);

        // Determine selected type
        if (isset($request->type_id)) {
            $getSelectedtype = sendType($product->category_id, $product->id, $request->type_id)[0];
            $vendorSelectedType = vendorType::where('type_name', $getSelectedtype->type_name)->first();
            $percent_off = round((( $getSelectedtype->del_mrp -  $getSelectedtype->selling_price) * 100) /  $getSelectedtype->del_mrp);
            $selected_type_id = $getSelectedtype->id;
            $selected_type_name = $getSelectedtype->type_name;
            $selected_type_selling_price = $getSelectedtype->selling_price;
            $selected_type_mrp = $getSelectedtype->del_mrp;
            $selected_type_percent_off = $percent_off;
            $selected_min_qty = $vendorSelectedType->min_qty ?? '';
        } else {
            // print_r($typedata['types']['regular_types']);
            // exit;
            $vendorSelectedType = vendorType::where('type_name', $typedata['types']['regular_types'][0]['type_name'])->first();
            $selected_type_id = $typedata['types']['regular_types'][0]['type_id'] ?? '';
            $selected_type_name = $typedata['types']['regular_types'][0]['type_name'] ?? '';
            $selected_type_selling_price = $typedata['types']['regular_types'][0]['selling_price'] ?? '';
            $selected_type_mrp = $typedata['types']['regular_types'][0]['type_mrp'] ?? '';
            $selected_type_percent_off = $typedata['types']['regular_types'][0]['percent_off'] ?? '';
            $selected_min_qty = $vendorSelectedType->min_qty ?? '';
        }

        // Add the product data
        $product_data[] = [
            'id' => $product->id,
            'category_id' => $product->category_id,
            'name' => $lang != "hi" ? $product->name : $product->name_hi,
            'long_desc' => $lang != "hi" ? $product->long_desc : $product->long_desc_hi,
            'cart_type_id' => $cart->type_id ?? "",
            'cart_type_name' => $cart_type_name,
            'cart_type_price' => $cart_type_price,
            'cart_quantity' => $cart_quantity,
            'cart_total_price' => $cart_total_price,
            'cart_status' => $cart_status,
            'wish_status' => $wish_status,
            'wish_id' => $wishlist_id,
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
            'selected_type_id' => $selected_type_id,
            'selected_type_name' => $selected_type_name,
            'selected_type_selling_price' => $selected_type_selling_price,
            'selected_type_mrp' => $selected_type_mrp,
            'selected_type_percent_off' => $selected_type_percent_off,
            'selected_min_qty' => $selected_min_qty,
        ];
    }

    return response()->json([
        'message' => 'success',
        'status' => 200,
        'data' => (isset($request->product_id) && $request->product_id != null &&  $product_data != null) ? $product_data[0] : $product_data,
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
            'user_id'  => 'nullable|numeric|exists:users,id',
            'pid'      => 'required|numeric',
            'cid'      => 'required|numeric',
            'tid'      => 'required|numeric',
            'lang'     => 'required|string',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 400);
        }
    
        $user = User::find($request->user_id);

        if($user) {

            $roleType = $user->role_type;

        }else{

            $roleType = false;
        }

        $types = sendType($request->cid, $request->pid, $request->tid , $roleType);
    
        if ($types->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'No types found']);
        }
    
        $type = $types->first();
    
        $percent_off = round((($type->del_mrp - $type->selling_price) * 100) / $type->del_mrp);

        $typedata = [
            'selected_type_id'              => $type->id,
            'selected_type_name'            => $request->lang != "hi" ? $type->type_name : $type->type_name_hi,
            'selected_type_selling_price'   => $type->selling_price,
            'selected_type_mrp'             => $type->del_mrp,
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

    private function fetchProductTypes($product_id, $roleType, $state_id, $city_id, $lang)
{
    // echo $roleType;
    // exit;
    $vendorTypes = [];
    $regularTypes = [];

    // If the user is a vendor (roleType == 2)
    if ($roleType && $roleType == 2) {
        // Query for vendor types
        $typeQuery = DB::table('vendor_types')
    ->leftJoin('type_subs', 'vendor_types.id', '=', 'type_subs.type_id')
    ->where('vendor_types.product_id', $product_id)
    ->where('vendor_types.is_active', 1)
    ->select('vendor_types.*', 'type_subs.*');

        if ($state_id) {
            $typeQuery->where('state_id', $state_id);
            if ($city_id) {
                $typeQuery->where('city_id', $city_id);
            }
        }

        $typeQuery->groupBy('type_name');

        $vendorTypes = $typeQuery->get();  // Get the result as a collection
    }

    // Query for regular types (non-vendor users)
    $typeQuery = Type::where('product_id', $product_id)
        ->where('is_active', 1);

    if ($state_id) {
        $typeQuery->where('state_id', $state_id);
        if ($city_id) {
            $typeQuery->where('city_id', $city_id);
        }
    } else {
        $typeQuery->groupBy('type_name');
    }

    $regularTypes = $typeQuery->get();  // Get the result as a collection

    // Format function for types
    $formatTypes = function ($types) use ($lang) {
        return $types->map(function ($type) use ($lang) {
            $percent_off = round((($type->del_mrp - $type->selling_price) * 100) / $type->del_mrp);
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
                'percent_off' => $percent_off,
                'min_qty' => $type->min_qty ?? 0,
            ];
        });
    };

  

    // Return response based on roleType (vendor or regular user)
    if ($roleType && $roleType == 2) {
        // If the user is a vendor, return only vendor types
        return [
            'types' => [
                'regular_types' => $vendorTypes->isNotEmpty() ? $formatTypes($vendorTypes) : [],
            ]
        ];
    } else {
        // If the user is a regular customer (not a vendor), return only regular types
        return [
            'types' => [
                'regular_types' => $regularTypes->isNotEmpty() ? $formatTypes($regularTypes) : [],
            ]
        ];
    }
}

}
