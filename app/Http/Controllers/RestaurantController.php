<?php
namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/restaurants",
     *     summary="Fetch all restaurants",
     *     tags={"Restaurants"},
     *     @OA\Response(response=200, description="List of restaurants")
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/restaurants-alt",
     *     summary="Alternative endpoint to fetch restaurants (API access)",
     *     tags={"Restaurants"},
     *     @OA\Response(response=200, description="List of restaurants")
     * )
     */
    public function apiRestaurants()
    {
        return response()->json(Restaurant::all());
    }

    /**
     * @OA\Post(
     *     path="/api/restaurants",
     *     summary="Create a new restaurant",
     *     tags={"Restaurants"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="address", type="string"),
     *             @OA\Property(property="contact_number", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="location", type="string")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Restaurant created"),
     *     @OA\Response(response=403, description="Only admins can create restaurants")
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/restaurants/{id}",
     *     summary="Get details of a specific restaurant",
     *     tags={"Restaurants"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Restaurant details")
     * )
     */
    public function show($id)
    {
        $restaurant = Restaurant::find($id);
    
        if (!$restaurant) {
            return response()->json(['error' => 'Restaurant not found'], 404);
        }
    
        return response()->json($restaurant);
    }
    /**
     * @OA\Put(
     *     path="/api/restaurants/{id}",
     *     summary="Update a restaurant",
     *     tags={"Restaurants"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="address", type="string"),
     *             @OA\Property(property="contact_number", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="location", type="string")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Restaurant updated"),
     *     @OA\Response(response=403, description="Unauthorized to update restaurant")
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/api/restaurants/{id}",
     *     summary="Delete a restaurant",
     *     tags={"Restaurants"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="Restaurant deleted"),
     *     @OA\Response(response=403, description="Unauthorized to delete restaurant")
     * )
     */
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