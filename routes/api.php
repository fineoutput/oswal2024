<?php

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ApiManagement\UserAuthController;

use App\Http\Controllers\ApiManagement\EcommerceController;

use App\Http\Controllers\ApiManagement\AppController;

use App\Http\Controllers\ApiManagement\WishlistController;

use App\Http\Controllers\ApiManagement\CartController;

use App\Http\Controllers\ApiManagement\OrderController;

Route::post('register', [UserAuthController::class, 'register']);

Route::post('login', [UserAuthController::class, 'login']);

Route::post('register-otp', [UserAuthController::class, 'verifyOtpProcess']);

Route::post('login-otp', [UserAuthController::class, 'verifyOtpProcess']);

Route::get('state' , [AppController::class , 'GetState']);

Route::get('city/{sid?}' , [AppController::class , 'GetCity']);

Route::get('footer-slider' , [AppController::class , 'footerSlider']);

Route::get('top-slider' , [AppController::class , 'headerSlider']);

// Route::prefix('blog')->name('blog.')->group(function () {

//     Route::get('view-blog',[AppController::class , 'blog'])->name('view-blog');

//     Route::post('add-comment',[AppController::class , 'add_blog_comment'])->name('add-comment');

//     Route::post('edit-comment',[AppController::class , 'add_blog_comment'])->name('edit-comment');

// });

// Route::prefix('major')->name('major.')->group(function () {

//     Route::get('category', [EcommerceController::class, 'major_category'])->name('category');

//     Route::get('products', [EcommerceController::class, 'major_products'])->name('products');
// });

Route::prefix('ecomm')->name('ecomm.')->group(function () {

    Route::get('category/{id?}', [EcommerceController::class, 'category'])->name('category');

    Route::get('productcategory', [EcommerceController::class, 'productcategory'])->name('productcategory');

    Route::post('products', [EcommerceController::class, 'products'])->name('products');

    Route::post('hot-deals-product', [EcommerceController::class, 'products'])->name('hot-deals-product');

    Route::post('tranding-product', [EcommerceController::class, 'products'])->name('tranding-product');

    Route::post('search-product', [EcommerceController::class, 'products'])->name('search-product');

    // Route::get('type', [EcommerceController::class, 'type'])->name('type');

    // Route::get('shipping-charge', [EcommerceController::class, 'shipping_charges'])->name('shipping-charges');

});



Route::prefix('wishlist')->name('wishlist.')->group(function () {

    Route::get('/', [WishlistController::class, 'show'])->name('index');
    
    Route::post('user', [WishlistController::class, 'show'])->name('user');

    Route::post('store', [WishlistController::class, 'store'])->name('store');

    Route::post('destroy',[WishlistController::class, 'destroy'])->name('destroy');
    
    Route::post('move-to-cart',[WishlistController::class, 'moveToCart'])->name('move-to-cart');
});

Route::prefix('cart')->name('cart.')->group(function () {

    Route::post('add-to-cart', [CartController::class, 'addToCart'])->name('add-to-cart');
    
    Route::post('get-cart-details', [CartController::class, 'getCartDetails'])->name('get-cart-details');

    Route::post('destroy',[CartController::class, 'destroy'])->name('destroy');
    
});
Route::post('checkout', [OrderController::class, 'checkout'])->name('checkout');

Route::middleware('auth:sanctum' , 'auth:user')->group(function () {

    Route::get('user', function (Request $request) {

        return $request->user();
        
    });

    Route::get('user/logout' , [UserAuthController::class, 'logout']);

    Route::post('add-address',[AppController::class , 'addAddress'])->name('add-address');

    Route::post('get-address',[AppController::class , 'getAddress'])->name('get-adress');

    Route::prefix('order')->name('order.')->group(function () {

        
        Route::post('get-cart-details', [OrderController::class, 'getCartDetails'])->name('get-cart-details');
    
        Route::post('destroy',[OrderController::class, 'destroy'])->name('destroy');
        
    });

    Route::get('promocode' ,[AppController::class, 'getPromoCode']);

    Route::get('gift-card' ,[AppController::class, 'giftCard']);

    Route::get('gift-card-sec' ,[AppController::class, 'giftCardSec']);
    
});
