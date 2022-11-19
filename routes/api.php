<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\CustomersController;
use App\Http\Controllers\api\NotificationsController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(AuthController::class)->group(function(){
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('send-otp-mail', 'sendOtpMail');
    Route::post('verify-account', 'verifyAccount');
});

Route::controller(CustomersController::class)->group(function(){
    Route::post('customer/save', 'save');
    Route::get('customers', 'list');
});

Route::controller(NotificationsController::class)->group(function(){
    Route::post('notification/save', 'save');
    Route::get('notifications', 'list');
});