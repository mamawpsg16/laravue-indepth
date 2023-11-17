<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\App\AppController;
use App\Http\Controllers\Auth\AuthenticationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Route::post('/register',[AuthenticationController::class,'register']);
Route::post('/login',[AuthenticationController::class,'authenticate']);
Route::get('/{any?}', AppController::class)->where('any', '[\/\w\.-]*');