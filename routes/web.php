<?php

use App\Http\Controllers\CatalogController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CartController;
use App\Http\Middleware\ProductMiddleware;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

Route::controller(CatalogController::class)->group(function() {
    Route::match(['get', 'post'], '/', 'list')->name('catalog');
    Route::get('/detail/{id}', 'detail')->name('catalog-detail');
    Route::get('/about', 'about')->name('about');
    Route::get('/contact', 'contact')->name('contact');
});

Route::controller(UserController::class)->group(function() {
    Route::match(['get', 'post'], '/signup', 'signup')->name('signup');
    Route::match(['get', 'post'], '/login', 'login')->name('login');
    Route::post('/logout', 'logout')->name('logout');
});

Route::controller(ProductController::class)->group(function() {
    Route::middleware('auth')->group(function() {
        Route::match(['get', 'post'], '/product/create', 'create')->can('create_product', App\Models\Product::class)->name('product-create');
        Route::match(['get', 'post'], '/product/{id}/edit', 'edit')->name('product-edit');
    });
});

Route::controller(CartController::class)->group(function() {
    Route::post('/cart/add/{id}', 'add')->name('cart-add');
    Route::post('/cart/remove/{id}', 'remove')->name('cart-remove');
    Route::post('/cart/decrement/{id}', 'decrement')->name('cart-decrement');
    Route::post('/cart/increment/{id}', 'increment')->name('cart-increment');
    Route::get('/checkout', 'checkout')->name('checkout');
});

Route::post('/products/{product}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
