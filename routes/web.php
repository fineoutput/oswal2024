<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Auth\adminlogincontroller;

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

// Route::get('/clear-cache', function () {
//     $exitCode = Artisan::call('cache:clear');
//     // $exitCode = Artisan::call('route:clear');
//     // $exitCode = Artisan::call('config:clear');
//     // $exitCode = Artisan::call('view:clear');
//     // return what you want
// });
//=========================================== FRONTEND =====================================================

Route::group(['prefix' => '/'], function () {

    Route::get('/', [HomeController::class, 'index'])->name('/');

});

//=========================================== Admin Login  =====================================================

Route::group(['middleware' => 'admin.guest'], function () {
        
    Route::get('/admin_index', [adminlogincontroller::class, 'admin_login'])->name('admin_login');

    Route::post('/login_process', [adminlogincontroller::class, 'admin_login_process'])->name('admin_login_process');

});