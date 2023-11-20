<?php

use App\Http\Controllers\Auth\AuthenticationController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Shop\ShopController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['middleware' =>['auth:sanctum']], function(){
    Route::get('/user', [AuthenticationController::class, 'tokenVerification'])->middleware('user.authenticated');
    Route::resource('products', ProductController::class);
    Route::post('/importProducts',[ProductController::class,'import']);
    Route::post('/updateProducts/{product}',[ProductController::class,'update']);
    Route::resource('shops', ShopController::class);
});
