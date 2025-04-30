<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/orders",
     *     summary="Fetch all orders",
     *     tags={"Orders"},
     *     @OA\Response(response=200, description="List of orders")
     * )
     */
    public function index()
    {
        $orders = Order::with('restaurant:id,name,email', 'user:id,name,email')
            ->get(['id', 'user_id', 'restaurant_id', 'delivery_address', 'total_price', 'status', 'created_at', 'updated_at']);
        return response()->json($orders);
    }

    /**
     * @OA\Get(
     *     path="/api/orders/{id}",
     *     summary="Show details of a specific order",
     *     tags={"Orders"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Order details")
     * )
     */
    public function show(string $id)
    {
        $order = Order::with('restaurant:id,name,email', 'user:id,name,email')
            ->findOrFail($id);
        return response()->json($order);
    }

    /**
     * @OA\Post(
     *     path="/api/orders",
     *     summary="Create a new order",
     *     tags={"Orders"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"restaurant_id", "delivery_address", "total_price"},
     *             @OA\Property(property="restaurant_id", type="integer"),
     *             @OA\Property(property="delivery_address", type="string"),
     *             @OA\Property(property="total_price", type="number", format="float")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Order created")
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
            'delivery_address' => 'required|string',
            'total_price' => 'required|numeric|min:0',
        ]);

        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $order = Order::create([
            'user_id' => auth()->id(),
            'restaurant_id' => $request->restaurant_id,
            'delivery_address' => $request->delivery_address,
            'total_price' => $request->total_price,
            'status' => 'pending'
        ]);

        return response()->json($order, 201);
    }

    /**
     * @OA\Put(
     *     path="/api/orders/{id}/status",
     *     summary="Update order status",
     *     tags={"Orders"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"status"},
     *             @OA\Property(property="status", type="string", enum={"pending", "assigned", "completed", "canceled"})
     *         )
     *     ),
     *     @OA\Response(response=200, description="Order status updated")
     * )
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:pending,assigned,completed,canceled',
        ]);

        $order = Order::find($id);

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        $order->status = $request->status;
        $order->save();

        return response()->json([
            'message' => 'Order status updated successfully.',
            'order' => $order
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/orders/{order}/assign-delivery",
     *     summary="Assign a delivery person to an order",
     *     tags={"Orders"},
     *     @OA\Parameter(name="order", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"delivery_person_id"},
     *             @OA\Property(property="delivery_person_id", type="integer")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Delivery person assigned successfully")
     * )
     */
    public function assignDeliveryPerson(Request $request, $orderId)
    {
        $order = Order::find($orderId);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $deliveryPerson = User::find($request->delivery_person_id);

        if (!$deliveryPerson || $deliveryPerson->role !== 'delivery') {
            return response()->json(['message' => 'Invalid delivery person'], 404);
        }

        $order->delivery_person_id = $request->delivery_person_id;
        $order->save();

        return response()->json(['message' => 'Delivery person assigned successfully']);
    }

    /**
     * @OA\Delete(
     *     path="/api/orders/{id}",
     *     summary="Delete an order",
     *     tags={"Orders"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="Order deleted")
     * )
     */
    public function destroy(string $id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return response()->json(['message' => 'Order deleted successfully']);
    }
}