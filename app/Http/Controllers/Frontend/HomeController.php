<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;

use App\Models\EcomCategory;

use App\Models\EcomProduct;

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
}
