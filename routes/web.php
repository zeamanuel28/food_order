<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController; // <- Uncommented this line

// Home route
Route::get('/', function () {
    return view('home');
});

// Order routes
Route::get('/orders', [OrderController::class, 'indexView']); // Blade view for orders
Route::put('/orders/{order}/status', [OrderController::class, 'updateStatus']);


// Restaurant routes
Route::get('/restaurants', [RestaurantController::class, 'indexView'])->name('restaurants.index'); // List all restaurants

// Login and logout routes
Route::post('/login', [AuthController::class, 'login'])->name('login'); // Login route
Route::post('/logout', [AuthController::class, 'logout'])->name('logout'); // Logout route

