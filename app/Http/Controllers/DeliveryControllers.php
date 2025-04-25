<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    public function index()
    {
        return Delivery::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'delivery_address' => 'required|string',
            'status' => 'nullable|string',
            'delivery_person' => 'nullable|string',
            'delivered_at' => 'nullable|date',
        ]);

        $delivery = Delivery::create($validated);
        return response()->json($delivery, 201);
    }

    public function show($id)
    {
        return Delivery::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $delivery = Delivery::findOrFail($id);
        $delivery->update($request->all());
        return response()->json($delivery);
    }

    public function destroy($id)
    {
        $delivery = Delivery::findOrFail($id);
        $delivery->delete();
        return response()->json(null, 204);
    }
}
