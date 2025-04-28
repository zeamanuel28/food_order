<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the orders.
     */
    public function index()
    {
        // Get all orders from the database with related restaurant and user information
        $orders = Order::with('restaurant:id,name,email', 'user:id,name,email')
            ->get(['id', 'user_id', 'restaurant_id', 'delivery_address', 'total_price', 'status', 'created_at', 'updated_at']);
        
        // Return orders as JSON response
        return response()->json($orders);
    }

    /**
     * This function returns the Blade view with orders data.
     */
    public function indexView()
    {
        try {
            // Get all orders with related restaurant and user data
            $orders = Order::with('restaurant:id,name,email', 'user:id,name,email')
                ->get(['id', 'user_id', 'restaurant_id', 'delivery_address', 'total_price', 'status', 'created_at', 'updated_at']);
            
            // Return the 'orders' Blade view with the orders data
            return view('orders', compact('orders'));
        } catch (\Exception $e) {
            // Log the error for debugging (you can uncomment this in production)
            // Log::error('Error fetching orders: ' . $e->getMessage());
            return response()->view('errors.500', [], 500);  // Custom 500 page in case of error
        }
    }

    /**
     * Store a newly created order in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
            'delivery_address' => 'required|string',
            'total_price' => 'required|numeric|min:0',
        ]);

        // Ensure the user is authenticated before creating the order
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Create the order
        $order = Order::create([
            'user_id' => auth()->id(),  // Use the authenticated user's ID
            'restaurant_id' => $request->restaurant_id,
            'delivery_address' => $request->delivery_address,
            'total_price' => $request->total_price,
            'status' => 'pending'  // Default status
        ]);

        // Return a JSON response with the created order
        return response()->json($order, 201);  // HTTP 201 created
    }

    /**
     * Display the specified order.
     */
    public function show(string $id)
    {
        // Retrieve the order by ID with related restaurant and user data
        $order = Order::with('restaurant:id,name,email', 'user:id,name,email')
            ->findOrFail($id); // Use findOrFail to return 404 if not found

        return response()->json($order);
    }



    /**
     * Update the specified order in storage.
     */
    public function updateStatus(Request $request, $id)
{
    // Validate the status
    $request->validate([
        'status' => 'required|string|in:pending,assigned,completed,canceled',
    ]);

    // Find the order by ID
    $order = Order::find($id);

    // If order is not found, return a 404 error
    if (!$order) {
        return response()->json(['error' => 'Order not found'], 404);
    }

    $order->status = $request->status;

    // Save the updated order to the database
    $order->save();

    // Return a success response with the updated order details
    return response()->json([
        'message' => 'Order status updated successfully.',
        'order' => $order  // This will include the updated order data
    ]);
}

    /**
     * Assign a delivery person to the order.
     */
    public function assignDeliveryPerson(Request $request, $orderId)
{
    // Find the order by the provided orderId
    $order = Order::find($orderId);

    // If the order doesn't exist, return a 404 response
    if (!$order) {
        return response()->json(['message' => 'Order not found'], 404);
    }

    // Now, we assume that the request contains the delivery_person_id
    // Make sure delivery_person_id is valid, e.g., user exists with that role
    $deliveryPerson = User::find($request->delivery_person_id);

    // Ensure the user is a delivery person
    if (!$deliveryPerson || $deliveryPerson->role !== 'delivery') {
        return response()->json(['message' => 'Invalid delivery person'], 404);
    }

    // Assign the delivery person to the order
    $order->delivery_person_id = $request->delivery_person_id;
    $order->save();

    return response()->json(['message' => 'Delivery person assigned successfully']);
}

    /**
     * Remove the specified order from storage.
     */
    public function destroy(string $id)
    {
        // Find the order and delete it
        $order = Order::findOrFail($id);  // Throws 404 if order not found
        $order->delete();

        return response()->json(['message' => 'Order deleted successfully']);
    }
}
