<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

use App\Models\EcomCategory;

use App\Models\EcomProduct;

use App\Models\Address;

use App\Models\Type;

class HomeController extends Controller
{
    // ============================= START INDEX ============================ 
    public function index(Request $request)
    {

        return view('index')->with('title', 'Oswal');
    }

    public function category(Request $request)
    {

        return view('products.category')->with('title', 'Category List');
    }
    public function find_shop(Request $request)
    {

        return view('find_shop')->with('title', 'find_shop');
    }
    
    public function services(Request $request)
    {

        return view('services')->with('title', 'services');
    }
    public function dealer_enq(Request $request)
    {

        return view('dealer_enq')->with('title', 'dealer_enq');
    }
    public function manufacture(Request $request)
    {

        return view('manufacture')->with('title', 'manufacture');
    }
    public function contact(Request $request)
    {

        return view('contact')->with('title', 'contact');
    }
    public function recipes(Request $request)
    {

        return view('recipes')->with('title', 'recipes');
    }
    public function video(Request $request)
    {

        return view('video')->with('title', 'video');
    }
    public function all_products(Request $request)
    {

        return view('all_products')->with('title', 'all_products');
    }
    public function vido_recipie2(Request $request)
    {

        return view('vido_recipie2')->with('title', 'vido_recipie2');
    }
    public function vido_recipie3(Request $request)
    {

        return view('vido_recipie3')->with('title', 'vido_recipie3');
    }
    
    public function privacy_policy(Request $request)
    {

        return view('privacy_policy')->with('title', 'privacy_policy');
    }
    public function terms_conditions(Request $request)
    {

        return view('terms_conditions')->with('title', 'terms_conditions');
    }
    public function about_us(Request $request)
    {

        return view('about_us')->with('title', 'about_us');
    }
    public function career(Request $request)
    {

        return view('career')->with('title', 'career');
    }
    public function order_success(Request $request)
    {

        return view('order_success')->with('title', 'order_success');
    }
    
    public function productDetail(Request $request, $slug)
    {
        $product = EcomProduct::where('url', $slug)->first();

        $images = [];

        for ($i = 1; $i <= 4; $i++) {
            $images[] = [
                'img' => $product->{"img$i"}, 
            ];
        }

        return view('products.productdetails', compact('product', 'images'))->with('title', 'Product Details');
    }


    public function Wislist(Request $request)
    {

        return view('wishlist')->with('title', 'Wishlist');
    }

    public function checkout(Request $request)
    {

        return view('checkout')->with('title', 'checkout');
    }

    public function renderProducts($slug)
    {
        $category = EcomCategory::where('url', $slug)->first();

        $products = sendProduct($category->id, false, false, false, false, false, false, 6);

        $htmlProducts = view('products.partials.product-list', compact('products'))->render();

        $htmlPagination = $products->links('vendor.pagination.bootstrap-4')->render();

        return response()->json([
            'categoryDetails' => [
                'description' => $category->long_desc,
                'banner_image' => asset($category->image),
                'category_name' => $category->name,
            ],
            'products' => $htmlProducts,
            'pagination' => $htmlPagination,
        ]);
    }

    public function renderProduct(Request $request)

    {

        $currentRouteName = Route::currentRouteName();

        $typeId     = $request->type_id;

        $product_id = $request->product_id;

        $stateId = view()->shared('globalState');

        $cityId  = view()->shared('globalCity');

        $seltedType = Type::Where('id', $typeId)->where('product_id' , $product_id)->first();

        $productType = Type::where('product_id', $product_id)->where('state_id', $stateId)->where('city_id', $cityId)->get();

        $product = sendProduct(false, $product_id, false, false, false, false, false, false)[0];
    
        if($currentRouteName == 'getproduct'){
            
            $htmlwebProduct = view('products.partials.render.webproduct', compact('product','productType','seltedType'))->render();
    
            $htmlmobProduct = view('products.partials.render.mobileproduct', compact('product','productType','seltedType'))->render();

        }elseif($currentRouteName == 'home.getproduct') {

            $htmlwebProduct = view('partials.homeparts.render.webproduct', compact('product','productType','seltedType'))->render();
    
            $htmlmobProduct = view('partials.homeparts.render.mobileproduct', compact('product','productType','seltedType'))->render();

        }

        return response()->json(['webproduct' => $htmlwebProduct ,'mobproduct' => $htmlmobProduct]);
    }

    public function addAddress(Request $request)  {
        
    }

    public function getAddress(Request $request) {

        $addresses = Address::where('user_id', 1)->get();

        $address_data = [];

        foreach ($addresses as $address) {

            $address->load('states', 'citys');

            $addr_string = "Doorflat {$address->doorflat}, ";

            if (!empty($address->landmark)) {
                $addr_string .= "{$address->landmark}, ";
            }
            $addr_string .= "{$address->address}, {$address->location_address}, {$address->zipcode}";

            $address['custom_address'] = $addr_string;

            $address_data[] =  $address;
        }

        return view('selectaddress' , compact('address_data'));
    }
}
