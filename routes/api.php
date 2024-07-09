<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\OrderController;

// Clients
Route::resource('clients', ClientController::class);
Route::post('/clients', [ClientController::class, 'store'])->name('clients.store');


// Products
Route::resource('products', ProductController::class);
Route::post('/products', [ProductController::class, 'store'])->name('products.store');

// Orders
Route::resource('orders', OrderController::class);
Route::delete('/orders/{order}/detach-product/{product}', [OrderController::class, 'detachProduct']);
