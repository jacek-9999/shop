<?php

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

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::resource('shops', \App\Http\Controllers\ShopController::class);
    Route::resource('products', \App\Http\Controllers\ProductController::class);
    Route::get('shops/{shop}/weather', 'App\Http\Controllers\ShopController@getWeather')->name('api.shops.weather');
});
Route::post('login', 'App\Http\Controllers\AuthController@login')->name('api.login');
