<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DeliveryController;

// Authentication routes
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout'])->middleware('auth:sanctum');

// Resource routes for deliveries and users
Route::apiResource('deliveries', DeliveryController::class);
Route::apiResource('users', UserController::class);

// Restaurant routes (authenticated)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/restaurants', [RestaurantController::class, 'store']);
    Route::get('/restaurants', [RestaurantController::class, 'index']);
    Route::put('/restaurants/{id}', [RestaurantController::class, 'update']);
    Route::delete('/restaurants/{restaurant}', [RestaurantController::class, 'destroy']);
    Route::put('/orders/{order}/assign-delivery', [OrderController::class, 'assignDeliveryPerson']);
});

//Route::middleware('auth:sanctum')->put('/orders/{order}/status', [OrderController::class, 'updateStatus']);
Route::put('/orders/{order}/status', [OrderController::class, 'updateStatus']);
// POST route for creating new orders
//Route::post('/orders', [OrderController::class, 'store']); 
Route::middleware('auth:sanctum')->post('/orders', [OrderController::class, 'store']);

//Route::put('/orders/{id}/status', [OrderController::class, 'update']);
Route::middleware(['auth:sanctum'])->put('/orders/{id}/status', [OrderController::class, 'update']);


// Public restaurant route (to get all restaurants)
Route::get('/restaurants', [RestaurantController::class, 'apiRestaurants']);

// Show single restaurant (without auth)
Route::get('/restaurants/{restaurant}', [RestaurantController::class, 'show']);


// Public view route (for Blade view) for orders
Route::get('/orders', [OrderController::class, 'indexView']);

// API endpoint for orders (for fetching orders via API) - no authentication middleware
Route::get('/orders', [OrderController::class, 'index']);  // Now publicly accessible
