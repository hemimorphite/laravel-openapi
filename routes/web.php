<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
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

Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return view('user.home');
    })->name('home');

    Route::get(
        '/categories',
        [CategoryController::class, 'index']
    )->name('category.index');
    
    Route::get(
        '/category/create',
        [CategoryController::class, 'create']
    )->name('category.create');
    
    Route::get(
        '/category/{category}',
        [CategoryController::class, 'show']
    )->name('category.show');
    
    Route::get(
        '/category/{category}/edit',
        [CategoryController::class, 'edit']
    )->name('category.edit');
    
    Route::post(
        '/category',
        [CategoryController::class, 'store']
    )->name('category.store');
    
    Route::patch(
        '/category/{category}',
        [CategoryController::class, 'update']
    )->name('category.update');
    
    Route::delete(
        '/category/{category}',
        [CategoryController::class, 'destroy']
    )->name('category.destroy');
    
    
    Route::get(
        '/products',
        [ProductController::class, 'index']
    )->name('product.index');
    
    Route::get(
        '/product/create',
        [ProductController::class, 'create']
    )->name('product.create');
    
    Route::get(
        '/product/{product}',
        [ProductController::class, 'show']
    )->name('product.show');
    
    Route::get(
        '/product/{product}/edit',
        [ProductController::class, 'edit']
    )->name('product.edit');
    
    Route::post(
        '/product',
        [ProductController::class, 'store']
    )->name('product.store');
    
    Route::patch(
        '/product/{product}',
        [ProductController::class, 'update']
    )->name('product.update');
    
    Route::delete(
        '/product/{product}',
        [ProductController::class, 'destroy']
    )->name('product.destroy');
    
    Route::get(
        '/logout',
        [UserController::class, 'logout']
    )->name('logout');
    
    Route::get(
        '/transactions',
        [OrderController::class, 'index']
    )->name('order.index');
    
    Route::get(
        '/shopping-carts',
        [OrderController::class, 'show']
    )->name('order.show');

    Route::post(
        '/product/{product}/order',
        [OrderController::class, 'store']
    )->name('order.store');

    Route::delete(
        '/transaction/{order}',
        [OrderController::class, 'destroy']
    )->name('order.destroy');

    Route::delete(
        '/transaction/item/{order_item}',
        [OrderController::class, 'remove']
    )->name('order.remove');
});


Route::get(
    '/login',
    [UserController::class, 'login']
)->name('login');

Route::post(
    '/login',
    [UserController::class, 'check']
)->name('user.check');

Route::get(
    '/register',
    [UserController::class, 'register']
)->name('register');

Route::post(
    '/register',
    [UserController::class, 'store']
)->name('user.store');
