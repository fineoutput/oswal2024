<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Auth\adminlogincontroller;
use App\Http\Controllers\Frontend\LocationController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckOutController;
use App\Http\Controllers\Frontend\WebhookController;
use App\Http\Controllers\Frontend\Auth\UserAuthController;
use App\Http\Controllers\Frontend\Users\UserController;
use App\Http\Controllers\Frontend\Users\WishlistController;
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
    // routes/web.php
Route::post('/store-visited-category', [HomeController::class, 'storecategory'])->name('store.visited.category');

    Route::post('/webhook', [WebhookController::class, 'handleWebhook']);

    Route::get('/category-list/{type?}', [HomeController::class, 'category'])->name('category-list');

    Route::get('/product/{slug}/details', [HomeController::class, 'productDetail'])->name('product-detail');

    Route::get('render/{slug}/{type?}/products',[HomeController::class, 'renderProducts'])->name('getproducts');

    Route::get('render/product',[HomeController::class, 'renderProduct'])->name('getproduct');
    
    Route::get('render/home/product',[HomeController::class, 'renderProduct'])->name('home.getproduct');

    Route::get('find_shop',[HomeController::class, 'find_shop'])->name('find_shop');

    Route::get('services',[HomeController::class, 'services'])->name('services');

    Route::get('dealer_enq',[HomeController::class, 'dealer_enq'])->name('dealer_enq');

    Route::get('manufacture',[HomeController::class, 'manufacture'])->name('manufacture');

    Route::get('contact',[HomeController::class, 'contact'])->name('contact');

    Route::get('recipes',[HomeController::class, 'recipes'])->name('recipes');

    Route::get('video',[HomeController::class, 'video'])->name('video');

    Route::get('all_products',[HomeController::class, 'all_products'])->name('all_products');
    
    Route::get('vido_recipie2',[HomeController::class, 'vido_recipie2'])->name('vido_recipie2');

    Route::get('vido_recipie3',[HomeController::class, 'vido_recipie3'])->name('vido_recipie3');

    Route::get('Privacy-Policy',[HomeController::class, 'privacy_policy'])->name('Privacy-Policy');

    Route::get('terms_conditions',[HomeController::class, 'terms_conditions'])->name('terms_conditions');

    Route::get('about_us',[HomeController::class, 'about_us'])->name('about_us');

    Route::get('career',[HomeController::class, 'career'])->name('career');

    Route::get('search', [HomeController::class, 'Search'])->name('search');

    Route::get('token', [HomeController::class, 'getAccessToken'])->name('token');

    Route::get('achivements1', [HomeController::class, 'achivements1'])->name('achivements1');

    Route::post('contact-us', [HomeController::class, 'contact_us'])->name('contact_us');
    
    Route::post('career_contact', [HomeController::class, 'career_contact'])->name('career_contact');

    Route::post('dealer_contact', [HomeController::class, 'dealer_contact'])->name('dealer_contact');
    
});

Route::prefix('cart')->name('cart.')->group(function () {

    Route::post('add-to-cart', [CartController::class, 'addToCart'])->name('add-to-cart');

    Route::get('update-qty', [CartController::class, 'updateQty'])->name('update-qty');
    
    Route::get('removeToCart/{cart_id?}',[CartController::class, 'removeToCart'])->name('remove-to-cart');
    
    Route::get('get-cart-details', [CartController::class, 'getCartDetails'])->name('get-cart-details');
});

Route::prefix('checkout')->middleware(['auth'])->name('checkout.')->group(function () {

    Route::get('/', [CheckOutController::class, 'checkout'])->name('process');

    Route::get('get-address/{place?}', [HomeController::class, 'getAddress'])->name('get-address');

    Route::post('apply-wallet', [CheckOutController::class, 'applyWallet'])->name('apply-wallet');

    Route::post('apply-promocode', [CheckOutController::class, 'applyPromocode'])->name('apply-promocode');

    Route::post('remove-promocode', [CheckOutController::class, 'removePromocode'])->name('remove-promocode');

    Route::post('apply-gift-card', [CheckOutController::class, 'applyGiftCard'])->name('apply-gift-card');
    
    Route::post('remove-gift-card', [CheckOutController::class, 'removeGiftCard'])->name('remove-gift-card');

    Route::post('place-order', [CheckOutController::class, 'placeOrder'])->name('place-order');
   
    Route::post('verify-payment', [CheckOutController::class, 'verifyPayment'])->name('verifypayment');
    Route::post('verify_payment', [CheckOutController::class, 'verify_payment'])->name('verify_payment');

    Route::get('order-success/{order_id?}', [CheckOutController::class, 'orderSuccess'])->name('order-success');
 
});


Route::post('/set-location', [LocationController::class, 'setLocation'])->name('set.location');

Route::get('/get-location', [LocationController::class, 'getLocation'])->name('get.location');

//=========================================== User Login  =====================================================

Route::Post('/register', [UserAuthController::class, 'register'])->name('register');

Route::post('register-otp', [UserAuthController::class, 'verifyOtpProcess'])->name('register.otp');

Route::post('/login', [UserAuthController::class, 'login'])->name('login');

Route::post('login-otp', [UserAuthController::class, 'verifyOtpProcess'])->name('login.otp');

Route::get('/logout', [UserAuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->name('user.')->group(function () {

    Route::get('user', [UserController::class, 'index'])->name('index');

    Route::get('get-order-details/{id}', [UserController::class, 'orderDetail'])->name('get-order-details');

    Route::get('cancle-order/{id}', [UserController::class, 'cancelOrder'])->name('cancle-order');

    Route::get('address/add/{redirect}/{id?}', [UserController::class, 'addAddress'])->name('add-address');

    Route::post('stor-address', [UserController::class, 'storeAddress'])->name('stor-address');

    Route::get('delete-address/{id}', [UserController::class, 'deleteAddress'])->name('delete-address');
    Route::post('rating', [UserController::class, 'rating'])->name('rating');

});

Route::get('getcity}', [UserController::class, 'getCity'])->name('getcity');

Route::prefix('wishlist')->middleware(['auth'])->name('wishlist.')->group(function () {

    Route::get('/', [WishlistController::class, 'Show'])->name('index');

    Route::post('store', [WishlistController::class, 'store'])->name('store');

    Route::get('destroy',[WishlistController::class, 'destroy'])->name('destroy');
    
    Route::post('move-to-cart',[WishlistController::class, 'moveToCart'])->name('move-to-cart');
});


//=========================================== Admin Login  =====================================================

Route::group(['middleware' => 'admin.guest'], function () {
        
    Route::get('/admin_index', [adminlogincontroller::class, 'admin_login'])->name('admin_login');

    Route::post('/login_process', [adminlogincontroller::class, 'admin_login_process'])->name('admin_login_process');

});