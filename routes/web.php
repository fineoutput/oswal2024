<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Auth\adminlogincontroller;
use App\Http\Controllers\Frontend\LocationController;
use App\Http\Controllers\Frontend\CartController;
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
//  return back();
});
//=========================================== FRONTEND =====================================================

Route::group(['prefix' => '/'], function () {

    Route::get('/', [HomeController::class, 'index'])->name('/');

    Route::get('/category-list', [HomeController::class, 'category'])->name('category-list');

    Route::get('/product/{slug}/details', [HomeController::class, 'productDetail'])->name('product-detail');

    Route::get('/wislist', [HomeController::class, 'Wislist'])->name('wislist');

    Route::get('/checkout', [HomeController::class, 'checkout'])->name('checkout');

    Route::get('render/{slug}/products',[HomeController::class, 'renderProducts'])->name('getproducts');

    Route::get('render/product',[HomeController::class, 'renderProduct'])->name('getproduct');
    
    Route::get('render/home/product',[HomeController::class, 'renderProduct'])->name('home.getproduct');

});
Route::prefix('cart')->name('cart.')->group(function () {

    Route::post('add-to-cart', [CartController::class, 'addToCart'])->name('add-to-cart');
    
    Route::post('removeToCart',[CartController::class, 'removeToCart'])->name('remove-to-cart');
    
    Route::post('get-cart-details', [CartController::class, 'getCartDetails'])->name('get-cart-details');

    
});


// In web.php
Route::post('/set-location', [LocationController::class, 'setLocation'])->name('set.location');

Route::get('/get-location', [LocationController::class, 'getLocation'])->name('get.location');


//=========================================== Admin Login  =====================================================

Route::group(['middleware' => 'admin.guest'], function () {
        
    Route::get('/admin_index', [adminlogincontroller::class, 'admin_login'])->name('admin_login');

    Route::post('/login_process', [adminlogincontroller::class, 'admin_login_process'])->name('admin_login_process');

});