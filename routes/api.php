<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\UserController;
use App\Http\Controllers\API\V1\ProductController;
use App\Http\Controllers\API\V1\CategoryController;
use App\Http\Controllers\API\V1\OrderController;
use App\Http\Controllers\API\V1\OrderItemController;
use App\Http\Controllers\API\V1\PaypalPaymentController;
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

Route::middleware('auth:api')->group( function () {
    Route::group(['prefix'=>'paypal'], function(){
        Route::get('/create',[PaypalPaymentController::class,'create']);
        Route::get('/capture',[PaypalPaymentController::class,'capture']);
    });

    Route::get(
        '/categories/all',
        [CategoryController::class, 'all']
    )->name('api.category.all');
    
    Route::get(
        '/categories',
        [CategoryController::class, 'index']
    )->name('api.category.index');
    
    Route::get(
        '/category/{category}',
        [CategoryController::class, 'show']
    )->name('api.category.show');
    
    Route::post(
        '/category',
        [CategoryController::class, 'store']
    )->name('api.category.store');
    
    Route::patch(
        '/category/{category}',
        [CategoryController::class, 'update']
    )->name('api.category.update');
    
    Route::delete(
        '/category/{category}',
        [CategoryController::class, 'destroy']
    )->name('api.category.destroy');
    
    
    
    Route::get(
        '/products',
        [ProductController::class, 'index']
    )->name('api.product.index');
    
    Route::get(
        '/product/{product}',
        [ProductController::class, 'show']
    )->name('api.product.show');
    
    Route::post(
        '/product',
        [ProductController::class, 'store']
    )->name('api.product.store');
    
    Route::patch(
        '/product/{product}',
        [ProductController::class, 'update']
    )->name('api.product.update');
    
    Route::delete(
        '/product/{product}',
        [ProductController::class, 'destroy']
    )->name('api.product.delete');
    
    Route::get(
        '/orders',
        [OrderController::class, 'index']
    )->name('api.order.index');
    
    Route::get(
        '/order/unpaid',
        [OrderController::class, 'show']
    )->name('api.order.show');

    Route::post(
        '/product/{product}/order',
        [OrderController::class, 'store']
    )->name('api.order.store');
    
    Route::delete(
        '/order/item/{order_item}',
        [OrderController::class, 'remove']
    )->name('api.order.remove');

    Route::delete(
        '/order/{order}',
        [OrderController::class, 'destroy']
    )->name('api.order.destory');

    Route::post(
        '/user/logout',
        [UserController::class, 'logout']
    )->name('api.user.logout');
        
});

Route::post(
    '/user/login',
    [UserController::class, 'login']
)->name('api.user.login');

Route::post(
    '/user/register',
    [UserController::class, 'register']
)->name('api.user.store');
