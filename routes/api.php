<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiManagement\UserAuthController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('register', [UserAuthController::class, 'register']);
Route::post('login', [UserAuthController::class, 'login']);
Route::post('registrat-otp', [UserAuthController::class, 'verifyOtpProcess']);
Route::post('login-otp', [UserAuthController::class, 'verifyOtpProcess']);


Route::middleware('auth:sanctum')->prefix('user')->group(function () {
    Route::get('logout', [UserAuthController::class, 'logout']);
    Route::get('/', function (Request $request) {
        return $request->user();
    });
});
