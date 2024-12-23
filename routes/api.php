<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiManagement\UserAuthController;
use App\Http\Controllers\ApiManagement\VendorAuthController;
use App\Http\Controllers\ApiManagement\EcommerceController;
use App\Http\Controllers\ApiManagement\AppController;
use App\Http\Controllers\ApiManagement\WishlistController;
use App\Http\Controllers\ApiManagement\CartController;
use App\Http\Controllers\ApiManagement\OrderController;
use App\Http\Controllers\ApiManagement\DeliveryBoyController;


Route::post('register', [UserAuthController::class, 'register']);
Route::post('login', [UserAuthController::class, 'login']);
Route::post('register-otp', [UserAuthController::class, 'verifyOtpProcess'])->name('register.otp');
Route::post('login-otp', [UserAuthController::class, 'verifyOtpProcess'])->name('login.otp');
Route::post('delivery-boy/login', [DeliveryBoyController::class, 'login']);
Route::post('delivery-boy/logout', [DeliveryBoyController::class, 'logout'])->middleware('auth:sanctum');
Route::post('vendor/register', [VendorAuthController::class, 'register']);
Route::post('vendor/login', [VendorAuthController::class, 'login']);
Route::post('vendor/register-otp', [VendorAuthController::class, 'verifyOtpProcess'])->name('register.otp');
Route::post('vendor/login-otp', [VendorAuthController::class, 'verifyOtpProcess'])->name('login.otp');
Route::get('state' , [AppController::class , 'GetState']);
Route::get('city/{sid?}' , [AppController::class , 'GetCity']);
Route::get('footer-slider' , [AppController::class , 'footerSlider']);
Route::get('top-slider' , [AppController::class , 'headerSlider']);
Route::get('festival-slider' , [AppController::class , 'festivalSlider']);
Route::get('popup' , [AppController::class , 'popup']);
Route::get('popup-image' , [AppController::class , 'popupimage']);

Route::get('update-fcm',[AppController::class , 'updateFcm'])->name('update-fcm');
Route::post('delivery-boy/update-fcm',[DeliveryBoyController::class , 'updateFcm'])->name('update-fcm'); 
Route::middleware('auth:sanctum' , 'auth:user')->group(function () {
    Route::get('user', function (Request $request) {
        return $request->user();
    });
    Route::get('user/logout' , [UserAuthController::class, 'logout']);
    Route::get('vendor/logout' , [VendorAuthController::class, 'logout']);
    Route::post('get-address',[AppController::class , 'getAddress'])->name('get-adress');    
   

    Route::prefix('order')->name('order.')->group(function () {
        Route::post('cod-checkout', [OrderController::class, 'codCheckout'])->name('codCheckout');
      
        Route::post('order-details', [OrderController::class, 'orderDetail'])->name('order-details');
        Route::post('cancel-order', [OrderController::class, 'cancelOrder'])->name('cancel-order');
        Route::post('track-order', [OrderController::class, 'trackOrder'])->name('track-order');
        Route::post('paid-checkout', [OrderController::class, 'paidCheckout'])->name('paidCheckout');
        Route::post('verify-payment', [OrderController::class, 'verifyPayment'])->name('verifypayment');
        Route::post('calculate', [OrderController::class, 'calculate'])->name('calculate');
        Route::post('checkout', [OrderController::class, 'checkout'])->name('checkout');
        
    });

    Route::post('user/wallet-transaction', [AppController::class, 'walletTransaction'])->name('transaction');
    Route::get('promocode' ,[AppController::class, 'getPromoCode']);
    Route::get('gift-card' ,[AppController::class, 'giftCard']);
    Route::get('gift-card-sec' ,[AppController::class, 'giftCardSec']);
    Route::post('rating' ,[AppController::class, 'giveRating']);
    Route::post('wallet' ,[AppController::class, 'getWalletAmount']);
    Route::get('vendor/reward-list' , [AppController::class , 'getReward']);
    Route::get('vendor/reward-claim' , [AppController::class , 'claimReward']);
});



Route::middleware(['auth:sanctum', 'auth:deliveryboy'])->prefix('delivery-boy')->name('delivery-boy.')->group(function () {
    Route::get('dashboard', [DeliveryBoyController::class, 'dashboard'])->name('dashboard');
    Route::get('order-list', [DeliveryBoyController::class , 'orderList'])->name('order-list');
    Route::get('order-accept', [DeliveryBoyController::class, 'acceptOrder'])->name('order-accept');
    Route::get('order-detail', [DeliveryBoyController::class , 'orderDetail'])->name('order-details');
    Route::get('product-details', [DeliveryBoyController::class , 'productDetail'])->name('product-details');
    Route::get('location-order', [DeliveryBoyController::class , 'orders'])->name('location-order');
    Route::post('start-delivery', [DeliveryBoyController::class , 'startDelivery'])->name('start-delivery');
    Route::post('complete-order', [DeliveryBoyController::class , 'completeOrder'])->name('complete-order');
    Route::post('update-current-location', [DeliveryBoyController::class , 'currentLocation'])->name('update-current-location');
       

});
//New route
Route::middleware(['auth:sanctum', 'auth:user'])->group(function () {
    Route::get('get-address',[AppController::class , 'getAddress'])->name('get-adress');
    Route::post('add-address',[AppController::class , 'addAddress'])->name('add-address');
Route::prefix('wishlist')->name('wishlist.')->group(function () {
    Route::post('/', [WishlistController::class, 'Show'])->name('index');
    Route::post('store', [WishlistController::class, 'store'])->name('store');
    Route::post('destroy',[WishlistController::class, 'destroy'])->name('destroy');
    Route::post('move-to-cart',[WishlistController::class, 'moveToCart'])->name('move-to-cart');
});


Route::prefix('order')->name('order.')->group(function () {
    Route::post('cod-checkout', [OrderController::class, 'codCheckout'])->name('codCheckout');
    Route::post('orders', [OrderController::class, 'orders'])->name('orders');
    Route::post('order-details', [OrderController::class, 'orderDetail'])->name('order-details');
    Route::post('cancel-order', [OrderController::class, 'cancelOrder'])->name('cancel-order');
    Route::post('track-order', [OrderController::class, 'trackOrder'])->name('track-order');
    Route::post('paid-checkout', [OrderController::class, 'paidCheckout'])->name('paidCheckout');
    Route::post('verify-payment', [OrderController::class, 'verifyPayment'])->name('verifypayment');
    Route::post('calculate', [OrderController::class, 'calculate'])->name('calculate');
    Route::post('checkout', [OrderController::class, 'checkout'])->name('checkout');
    
});
});

Route::prefix('ecomm')->name('ecomm.')->group(function () {
    Route::get('category/{id?}', [EcommerceController::class, 'category'])->name('category');
    Route::get('productcategory', [EcommerceController::class, 'productcategory'])->name('productcategory');
    Route::post('products', [EcommerceController::class, 'products'])->name('products');
    Route::post('hot-deals-product', [EcommerceController::class, 'products'])->name('hot-deals-product');
    Route::post('tranding-product', [EcommerceController::class, 'products'])->name('tranding-product');
    Route::post('search-product', [EcommerceController::class, 'products'])->name('search-product');
    Route::post('featured-product', [EcommerceController::class, 'products'])->name('featured-product');
    Route::post('related-product', [EcommerceController::class, 'products'])->name('related-product');
    Route::post('category-product', [EcommerceController::class, 'products'])->name('category-product');
    Route::post('product-details', [EcommerceController::class, 'products'])->name('details-product');
    Route::get('type', [EcommerceController::class, 'type'])->name('type');
    // Route::get('shipping-charge', [EcommerceController::class, 'shipping_charges'])->name('shipping-charges');

});

Route::prefix('cart')->name('cart.')->group(function () {
    Route::post('add-to-cart', [CartController::class, 'addToCart'])->name('add-to-cart');
    Route::post('get-cart-details', [CartController::class, 'getCartDetails'])->name('get-cart-details');
    Route::post('get-cart-count', [CartController::class, 'getCartCount'])->name('get-cart-count');
    Route::post('destroy',[CartController::class, 'destroy'])->name('destroy');
});
Route::get('unroute', [AppController::class, 'unauth'])->name('unauth.route');
Route::post('order/orders', [OrderController::class, 'orders'])->name('order/orders');
Route::post('get-location-from-latlong', [AppController::class, 'get_location'])->name('get-location-from-latlong');