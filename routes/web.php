<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Auth\adminlogincontroller;
use App\Http\Controllers\Frontend\LocationController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckOutController;
use App\Http\Controllers\Frontend\Auth\UserAuthController;
use App\Http\Controllers\Frontend\Users\OrderController;
use App\Http\Controllers\Frontend\Users\UserController;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/clear-cache', function () {
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('route:clear');
    $exitCode = Artisan::call('config:clear');
    $exitCode = Artisan::call('view:clear');
});

//=========================================== FRONTEND =====================================================

Route::group(['prefix' => '/'], function () {

    Route::get('/', [HomeController::class, 'index'])->name('/');

    Route::get('/category-list', [HomeController::class, 'category'])->name('category-list');

    Route::get('/product/{slug}/details', [HomeController::class, 'productDetail'])->name('product-detail');

    Route::get('/wislist', [HomeController::class, 'Wislist'])->name('wislist');

    // Route::get('/checkout', [HomeController::class, 'checkout'])->name('checkout');

    Route::get('render/{slug}/products',[HomeController::class, 'renderProducts'])->name('getproducts');

    Route::get('render/product',[HomeController::class, 'renderProduct'])->name('getproduct');
    
    Route::get('render/home/product',[HomeController::class, 'renderProduct'])->name('home.getproduct');

});

Route::prefix('cart')->name('cart.')->group(function () {

    Route::post('add-to-cart', [CartController::class, 'addToCart'])->name('add-to-cart');
    
    Route::post('removeToCart',[CartController::class, 'removeToCart'])->name('remove-to-cart');
    
    Route::get('get-cart-details', [CartController::class, 'getCartDetails'])->name('get-cart-details');

});

Route::prefix('checkout')->middleware(['auth'])->name('checkout.')->group(function () {

    Route::post('/', [CheckOutController::class, 'checkout'])->name('process');

    Route::get('add-address', [HomeController::class, 'addAddress'])->name('add-address');
    
    Route::get('get-address', [HomeController::class, 'getAddress'])->name('get-address');

    Route::post('apply-wallet', [CheckOutController::class, 'applyWallet'])->name('apply-wallet');

    Route::post('apply-promocode', [CheckOutController::class, 'applyPromocode'])->name('apply-promocode');

    Route::post('apply-gift-card', [CheckOutController::class, 'applyGiftCard'])->name('apply-gift-card');

    Route::post('place-order', [CheckOutController::class, 'placeOrder'])->name('place-order');
   
    Route::post('verify-payment', [CheckOutController::class, 'verifyPayment'])->name('verifypayment');
 
});


Route::post('/set-location', [LocationController::class, 'setLocation'])->name('set.location');

Route::get('/get-location', [LocationController::class, 'getLocation'])->name('get.location');

//=========================================== User Login  =====================================================

Route::Post('/register', [UserAuthController::class, 'register'])->name('register');

Route::post('register-otp', [UserAuthController::class, 'verifyOtpProcess'])->name('register.otp');

Route::post('/login', [UserAuthController::class, 'login'])->name('login');

Route::post('login-otp', [UserAuthController::class, 'verifyOtpProcess'])->name('login.otp');

Route::get('/logout', [UserAuthController::class, 'logout'])->name('logout');

Route::prefix('user')->middleware(['auth'])->name('user.')->group(function () {

    Route::get('/', [UserController::class, 'index'])->name('index');
    
    Route::post('removeToCart',[CartController::class, 'removeToCart'])->name('remove-to-cart');
    
    Route::get('get-cart-details', [CartController::class, 'getCartDetails'])->name('get-cart-details');

});
//=========================================== Admin Login  =====================================================

Route::group(['middleware' => 'admin.guest'], function () {
        
    Route::get('/admin_index', [adminlogincontroller::class, 'admin_login'])->name('admin_login');

    Route::post('/login_process', [adminlogincontroller::class, 'admin_login_process'])->name('admin_login_process');

});