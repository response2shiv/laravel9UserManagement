<?php

use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\LayoutsController;

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

// Route::get('/verify-account/{$token}', [AuthController::class, 'verifyAccount']);
// Route::get('/verify-account/{$token}', function(){
//     echo 'innnn';exit;
// });

Route::get('/{any}', [LayoutsController::class, 'layout'])->where('any','.*');
// Route::get('/{any}', function () {
//     return view('welcome');
// })->where('any','.*');


