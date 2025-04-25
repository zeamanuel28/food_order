<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    // GET /api/restaurants
    public function index()
    {
        // Get all restaurants from the database
        $restaurants = Restaurant::all();

        // Return restaurants as JSON response
        return response()->json($restaurants);
    }

    // This function returns the Blade view with restaurants data
    public function indexView()
    {
        try {
            $restaurants = Restaurant::all();
            return view('restaurants', compact('restaurants'));
        } catch (\Exception $e) {
            // Log the error to the Laravel log file
           // Log::error('Error fetching restaurants: ' . $e->getMessage());
            return response()->view('errors.500', [], 500);  // Custom 500 page
        }
    }

    // API: GET /api/restaurants
    public function apiRestaurants()
    {
        return response()->json(Restaurant::all());
    }

    // POST /api/restaurants
    public function store(Request $request)
    {
        // Check if the logged-in user is an admin
        $user = auth()->user();

        if ($user->role !== 'admin') {
            return response()->json(['error' => 'Only admins can create restaurants'], 403);
        }

        // Validate incoming request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'address' => 'nullable|string',
            'contact_number' => 'nullable|string',
            'description' => 'nullable|string',
            'location' => 'nullable|string',
        ]);

        // Create the new restaurant record
        $restaurant = Restaurant::create([
            'restaurant_admin_id' => $user->id,
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'contact_number' => $request->contact_number,
            'description' => $request->description,
            'location' => $request->location,
        ]);

        // Return the created restaurant as a JSON response
        return response()->json($restaurant, 201);
    }

    // GET /api/restaurants/{id}
    public function show(Restaurant $restaurant)
    {
        return response()->json($restaurant);
    }

    // PUT /api/restaurants/{id}
    public function update(Request $request, string $id)
    {
        // Find the restaurant by its ID
        $restaurant = Restaurant::findOrFail($id);

        // Check if the current user is the admin of this restaurant
        if ($restaurant->restaurant_admin_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Validate the update request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $restaurant->id,
            'address' => 'nullable|string',
            'contact_number' => 'nullable|string',
            'description' => 'nullable|string',
            'location' => 'nullable|string',
        ]);

        // Update the restaurant details
        $restaurant->update($request->only([
            'name', 'email', 'address', 'contact_number', 'description', 'location'
        ]));

        // Return the updated restaurant as a JSON response
        return response()->json($restaurant, 200);
    }

    // DELETE /api/restaurants/{id}
    public function destroy(Restaurant $restaurant)
    {
        // Check if the current user is the admin of this restaurant
        if ($restaurant->restaurant_admin_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Delete the restaurant
        $restaurant->delete();

        // Return a successful response with no content
        return response()->json(null, 204);
    }
}
