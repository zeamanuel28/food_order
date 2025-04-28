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
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            // No need to validate 'role' since it has a default value in the database
            'address' => 'nullable|string',
            'phone_number' => 'nullable|string',
        ]);
    
        // Create the user with a default role of 'delivery'
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => 'delivery', // Default to delivery
            'address' => $validated['address'],
            'phone_number' => $validated['phone_number'],
        ]);
    
        return response()->json($user, 201);
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
