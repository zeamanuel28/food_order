<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DeliveryController;

/*
|--------------------------------------------------------------------------|
| Public Authentication Routes                                              |
|--------------------------------------------------------------------------|
*/

/**
 * @OA\PathItem(path="/api/login")
 * @OA\Post(
 *     path="/api/login",
 *     summary="Log in an existing user",
 *     tags={"Authentication"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email", "password"},
 *             @OA\Property(property="email", type="string", format="email"),
 *             @OA\Property(property="password", type="string", format="password")
 *         )
 *     ),
 *     @OA\Response(response=200, description="User logged in")
 * )
 */
Route::post('/login', [UserController::class, 'login']);

/**
 * @OA\PathItem(path="/api/logout")
 * @OA\Post(
 *     path="/api/logout",
 *     summary="Log out the authenticated user",
 *     tags={"Authentication"},
 *     security={{"sanctum":{}}},
 *     @OA\Response(response=200, description="User logged out")
 * )
 */
Route::post('/logout', [UserController::class, 'logout'])->middleware('auth:sanctum');

/*
|--------------------------------------------------------------------------|
| User & Delivery Resource Routes                                           |
|--------------------------------------------------------------------------|
*/

/**
 * @OA\PathItem(path="/api/users")
 * @OA\Get(
 *     path="/api/users",
 *     summary="Get all users",
 *     tags={"Users"},
 *     @OA\Response(response=200, description="List of users")
 * )
 */
Route::apiResource('users', UserController::class);

/**
 * @OA\PathItem(path="/api/deliveries")
 * @OA\Get(
 *     path="/api/deliveries",
 *     summary="Get all deliveries",
 *     tags={"Deliveries"},
 *     @OA\Response(response=200, description="List of deliveries")
 * )
 */
Route::apiResource('deliveries', DeliveryController::class);

/*
|--------------------------------------------------------------------------|
| Protected Routes (Require Sanctum Authentication)                        |
|--------------------------------------------------------------------------|
*/

Route::middleware('auth:sanctum')->group(function () {
    /**
     * @OA\PathItem(path="/api/restaurants")
     * @OA\Post(
     *     path="/api/restaurants",
     *     summary="Create a new restaurant",
     *     tags={"Restaurants"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "address", "description"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="address", type="string"),
     *             @OA\Property(property="description", type="string")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Restaurant created")
     * )
     */
    Route::post('/restaurants', [RestaurantController::class, 'store']);

    /**
     * @OA\PathItem(path="/api/restaurants")
     * @OA\Get(
     *     path="/api/restaurants",
     *     summary="List all restaurants",
     *     tags={"Restaurants"},
     *     @OA\Response(response=200, description="List of restaurants")
     * )
     */
    Route::get('/restaurants', [RestaurantController::class, 'index']);

    /**
     * @OA\PathItem(path="/api/restaurants/{id}")
     * @OA\Put(
     *     path="/api/restaurants/{id}",
     *     summary="Update a restaurant",
     *     tags={"Restaurants"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="address", type="string"),
     *             @OA\Property(property="description", type="string")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Restaurant updated")
     * )
     */
    Route::put('/restaurants/{id}', [RestaurantController::class, 'update']);

    /**
     * @OA\PathItem(path="/api/restaurants/{id}")
     * @OA\Delete(
     *     path="/api/restaurants/{id}",
     *     summary="Delete a restaurant",
     *     tags={"Restaurants"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="Restaurant deleted")
     * )
     */
    Route::delete('/restaurants/{id}', [RestaurantController::class, 'destroy']);

    /**
     * @OA\PathItem(path="/api/orders")
     * @OA\Post(
     *     path="/api/orders",
     *     summary="Create a new order",
     *     tags={"Orders"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"restaurant_id", "user_id", "items"},
     *             @OA\Property(property="restaurant_id", type="integer"),
     *             @OA\Property(property="user_id", type="integer"),
     *             @OA\Property(property="items", type="array", items=@OA\Items(type="string"))
     *         )
     *     ),
     *     @OA\Response(response=201, description="Order created")
     * )
     */
    Route::post('/orders', [OrderController::class, 'store']);

    /**
     * @OA\PathItem(path="/api/orders/{id}/status")
     * @OA\Put(
     *     path="/api/orders/{id}/status",
     *     summary="Update the status of an order",
     *     tags={"Orders"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             required={"status"},
     *             @OA\Property(property="status", type="string")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Order status updated")
     * )
     */
    Route::put('/orders/{id}/status', [OrderController::class, 'update']);

    /**
     * @OA\PathItem(path="/api/orders/{order}/assign-delivery")
     * @OA\Put(
     *     path="/api/orders/{order}/assign-delivery",
     *     summary="Assign a delivery person to an order",
     *     tags={"Orders"},
     *     @OA\Parameter(name="order", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             required={"delivery_id"},
     *             @OA\Property(property="delivery_id", type="integer")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Delivery person assigned")
     * )
     */
    Route::put('/orders/{order}/assign-delivery', [OrderController::class, 'assignDeliveryPerson']);
});

/*
|--------------------------------------------------------------------------|
| Public Routes                                                             |
|--------------------------------------------------------------------------|
*/

/**
 * @OA\PathItem(path="/api/restaurants")
 * @OA\Get(
 *     path="/api/restaurants",
 *     summary="List all restaurants (public access)",
 *     tags={"Restaurants"},
 *     @OA\Response(response=200, description="List of restaurants")
 * )
 */
Route::get('/restaurants', [RestaurantController::class, 'apiRestaurants']);

/**
 * @OA\PathItem(path="/api/restaurants/{id}")
 * @OA\Get(
 *     path="/api/restaurants/{id}",
 *     summary="Show details of a single restaurant",
 *     tags={"Restaurants"},
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Restaurant details")
 * )
 */
//Route::get('/restaurants/{id}', [RestaurantController::class, 'show']);
Route::get('/restaurants/{restaurant}', [RestaurantController::class, 'show']);

/**
 * @OA\PathItem(path="/api/orders")
 * @OA\Get(
 *     path="/api/orders",
 *     summary="Fetch orders via API (no auth)",
 *     tags={"Orders"},
 *     @OA\Response(response=200, description="List of orders")
 * )
 */
Route::get('/orders', [OrderController::class, 'index']);
