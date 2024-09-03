<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\EcomCategory;

use App\Models\EcomProduct;

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

    public function Cart(Request $request)
    {

        return view('cart')->with('title', 'Cart');
    }

    public function checkout(Request $request)
    {

        return view('checkout')->with('title', 'checkout');
    }

    public function renderProduct($slug)
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

}
