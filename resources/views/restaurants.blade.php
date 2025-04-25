<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Restaurants</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            fetchRestaurants();
        });

        // Function to fetch restaurants from the backend API
        function fetchRestaurants() {
            fetch('/api/restaurants') // Fetch from the API endpoint
                .then(response => response.json()) // Parse the JSON response
                .then(data => {
                    const restaurantsContainer = document.getElementById('restaurants-list');
                    if (data.length === 0) {
                        restaurantsContainer.innerHTML = '<p class="text-center text-gray-600">No restaurants available.</p>';
                    } else {
                        restaurantsContainer.innerHTML = ''; // Clear existing content
                        data.forEach(restaurant => {
                            const restaurantCard = `
                                <div class="restaurant-card bg-white p-6 rounded-lg shadow-lg hover:shadow-2xl transition-shadow duration-300 ease-in-out transform hover:scale-105">
                                    <h2 class="text-2xl font-semibold text-indigo-600 mb-3">${restaurant.name}</h2>
                                    <p class="text-gray-700 text-sm mb-2"><strong>Email:</strong> ${restaurant.email}</p>
                                    <p class="text-gray-700 text-sm mb-2"><strong>Address:</strong> ${restaurant.address || 'N/A'}</p>
                                    <p class="text-gray-700 text-sm mb-2"><strong>Contact:</strong> ${restaurant.contact_number || 'N/A'}</p>
                                    <p class="text-gray-700 text-sm mb-2"><strong>Location:</strong> ${restaurant.location || 'N/A'}</p>
                                    <p class="text-gray-600 text-xs mt-2 italic">${restaurant.description || 'No description.'}</p>

                                    <!-- Rating Section with Stars -->
                                    <div class="flex items-center mt-4">
                                        ${generateStarRating(restaurant.rating || 0)}
                                    </div>

                                    <div class="mt-4">
                                        <p class="text-gray-700 text-sm"><strong>Price Range:</strong> ${restaurant.price_range || 'N/A'}</p>
                                        <p class="text-gray-700 text-sm"><strong>Hours:</strong> ${restaurant.hours || 'N/A'}</p>
                                    </div>
                                </div>
                            `;
                            restaurantsContainer.innerHTML += restaurantCard;
                        });
                    }
                })
                .catch(error => {
                    console.error('Error fetching restaurants:', error);
                });
        }

        // Function to generate star rating HTML
        function generateStarRating(rating) {
            const fullStar = `<svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 15l-3.09 1.63.59-3.43L4 8.93l3.41-.29L10 2l1.59 6.64L15 8.93l-3.5 4.27.59 3.43L10 15z"></path></svg>`;
            const emptyStar = `<svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 15l-3.09 1.63.59-3.43L4 8.93l3.41-.29L10 2l1.59 6.64L15 8.93l-3.5 4.27.59 3.43L10 15z"></path></svg>`;
            let starsHTML = '';

            // Add full stars
            for (let i = 0; i < Math.floor(rating); i++) {
                starsHTML += fullStar;
            }

            // Add empty stars
            for (let i = Math.floor(rating); i < 5; i++) {
                starsHTML += emptyStar;
            }

            return starsHTML;
        }
    </script>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7fafc;
        }

        .restaurant-card {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .restaurant-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .title {
            text-align: center;
            font-size: 3rem;
            color: #4c51bf;
            margin-bottom: 20px;
        }

        .restaurant-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .restaurant-card h2 {
            font-size: 1.75rem;
            color: #4c51bf;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .restaurant-card p {
            color: #4a5568;
            font-size: 1rem;
            margin-bottom: 10px;
        }

        .restaurant-card p strong {
            font-weight: 600;
        }

        .restaurant-card .mt-4 p {
            font-size: 0.95rem;
            color: #2d3748;
            margin-top: 8px;
        }

        .restaurant-card .mt-4 p strong {
            color: #2b6cb0;
        }

        .restaurant-card .italic {
            font-style: italic;
            color: #718096;
        }

        /* Adjustments for responsive design */
        @media (max-width: 640px) {
            .restaurant-list {
                grid-template-columns: 1fr;
                gap: 15px;
            }
        }
    </style>
</head>

<body class="bg-gray-100 text-gray-900 font-sans">
    <div class="max-w-4xl mx-auto mt-10 p-4">
        <h1 class="title">Restaurant List</h1>

        <!-- Display Restaurants -->
        <div id="restaurants-list" class="restaurant-list">
            <!-- The list of restaurants will be dynamically populated here by JavaScript -->
        </div>
    </div>
</body>

</html>
