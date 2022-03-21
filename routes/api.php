<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OtpController;

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

Route::group(['middleware' => 'auth:api'], function(){
    Route::get('/flixshop/users/logout', [AuthController::class, 'logout']);
    Route::get('/flixshop/users/login_userdata', [AuthController::class, 'login_userdata']); 
    Route::post('/flixshop/users/carts/add', [CartController::class, 'add_to_cart']); 
    Route::post('/flixshop/users/carts/items', [CartController::class, 'addItem_user_cart']); 
    Route::get('/flixshop/users/carts/itemlists', [CartController::class, 'get_user_cart']); 
    Route::post('/flixshop/users/carts/updatequantity', [CartController::class, 'update_qty']); 
    Route::post('/flixshop/users/carts/removecartitem', [CartController::class, 'removeCartItem']); 
});

Route::prefix('/flixshop')->group(function(){
    Route::resource('/category',CategoryController::class);
    Route::get('/subcategory/singlecategory/{id}', [SubcategoryController::class, 'getSubByCat']);
    Route::resource('/subcategory',SubcategoryController::class);
    Route::get('/product/singlesubcategory/{pid}', [ProductController::class, 'getProductBySub']);
    Route::resource('/product',ProductController::class);
    Route::post('/guest/carts/items', [CartController::class, 'get_guest_cart']);
    Route::resource('/users',AuthController::class);
    Route::post('/users/otp',[OtpController::class,'checkOtp']);
    Route::post('/users/sendotp',[OtpController::class,'sendOtp']);
    Route::post('/users/login',[AuthController::class,'loginuser']);

});
