<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\ProductController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', [CustomerController::class, 'login']);
    Route::post('logout', [CustomerController::class, 'logout']);
    Route::post('refresh',  [CustomerController::class, 'refresh']);
    Route::get('me', [CustomerController::class, 'me']);
});
Route::post('/customer',[CustomerController::class, 'store']);
Route::get('/restaurant',[RestaurantController::class, 'getRestaurant']);
Route::get('/product',[ProductController::class, 'getProduct']);
Route::get('/product/{product_id}',[ProductController::class, 'getDetailProduct']);