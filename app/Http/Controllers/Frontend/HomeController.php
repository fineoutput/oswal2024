<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Redirect;
use Laravel\Sanctum\PersonalAccessToken;
use DateTime;


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

    public function productDetail(Request $request)
    {
     
        return view('products.productdetails')->with('title', 'Product Details');

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
}
