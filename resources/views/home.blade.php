<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Delivery - Home</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .restaurant-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }

        .navbar a:hover {
            text-decoration: underline;
        }

        .hero-section {
            background-image: url('https://via.placeholder.com/1500x600'); /* Hero Image */
            background-size: cover;
            background-position: center;
        }

        .letter {
            display: inline-block;
            opacity: 0;
            animation: fadeIn 0.5s forwards;
        }

        .letter:nth-child(1) {
            animation-delay: 0s;
        }

        .letter:nth-child(2) {
            animation-delay: 0.2s;
        }

        .letter:nth-child(3) {
            animation-delay: 0.4s;
        }

        .letter:nth-child(4) {
            animation-delay: 0.6s;
        }

        .letter:nth-child(5) {
            animation-delay: 0.8s;
        }

        .letter:nth-child(6) {
            animation-delay: 1s;
        }

        .letter:nth-child(7) {
            animation-delay: 1.2s;
        }

        .letter:nth-child(8) {
            animation-delay: 1.4s;
        }

        .letter:nth-child(9) {
            animation-delay: 1.6s;
        }

        .letter:nth-child(10) {
            animation-delay: 1.8s;
        }

        .letter:nth-child(11) {
            animation-delay: 2s;
        }

        .letter:nth-child(12) {
            animation-delay: 2.2s;
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
            }
        }
    </style>
</head>

<body class="bg-gray-100">

    <!-- Navbar -->
    <nav class="bg-blue-600 text-white p-4 shadow-md">
        <div class="container mx-auto flex justify-between items-center">
            <a href="/" class="text-lg font-bold hover:text-yellow-300">Food Delivery</a>
            <div class="space-x-6">
                <a href="/restaurants" class="navbar text-lg hover:text-yellow-300">Restaurants</a>
                <a href="/orders" class="navbar text-lg hover:text-yellow-300">Orders</a>
                <a href="/profile" class="navbar text-lg hover:text-yellow-300">Profile</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section text-white text-center py-32 bg-blue-600 relative bg-opacity-70">
        <div class="absolute inset-0 bg-black opacity-40"></div>
        <h1 class="text-5xl font-extrabold leading-tight">
            <span class="letter">W</span>
            <span class="letter">E</span>
            <span class="letter">L</span>
            <span class="letter">C</span>
            <span class="letter">O</span>
            <span class="letter">M</span>
            <span class="letter">E</span>
            <span class="letter"> </span>
            <span class="letter">T</span>
            <span class="letter">O</span>
            <span class="letter"> </span>
            <span class="letter">F</span>
            <span class="letter">O</span>
            <span class="letter">O</span>
            <span class="letter">D</span>
            <span class="letter"> </span>
            <span class="letter">D</span>
            <span class="letter">E</span>
            <span class="letter">L</span>
            <span class="letter">I</span>
            <span class="letter">V</span>
            <span class="letter">E</span>
            <span class="letter">R</span>
            <span class="letter">Y</span>
        </h1>
        <p class="mt-4 text-2xl font-medium">Discover delicious meals from the best restaurants in your area</p>
        <a href="/restaurants" class="mt-8 inline-block px-8 py-3 text-lg font-semibold text-blue-600 bg-white rounded-lg shadow-lg hover:bg-blue-500 hover:text-white transition duration-300">Browse Restaurants</a>
    </section>

    <!-- Restaurants Section -->
    <section class="container mx-auto py-8 px-4">
        <h2 class="text-3xl font-semibold mb-8 text-center text-gray-800">Popular Restaurants</h2>
        <div id="restaurant-list" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
            <!-- Restaurant list will be populated here -->
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-6 mt-12">
        <div class="container mx-auto text-center">
            <p>&copy; 2025 Food Delivery. All Rights Reserved.</p>
            <div class="mt-4">
                <a href="#" class="text-blue-500 hover:text-yellow-300">Privacy Policy</a> |
                <a href="#" class="text-blue-500 hover:text-yellow-300">Terms of Service</a>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Static image paths from public/images folder
            const images = [
                '/images/restaurant1.jpg',
                '/images/restaurant2.jpg',
                '/images/restaurant3.jpg'
            ];

            fetch('/api/restaurants')
                .then(response => response.json())
                .then(data => {
                    const restaurantList = document.getElementById('restaurant-list');
                    if (data.length > 0) {
                        data.forEach((restaurant, index) => {
                            const restaurantCard = document.createElement('div');
                            restaurantCard.classList.add('bg-white', 'shadow-lg', 'rounded-lg', 'overflow-hidden', 'restaurant-card', 'transition-transform', 'duration-300');

                            const imageIndex = index % images.length;

                            restaurantCard.innerHTML = `
                                <img src="${images[imageIndex]}" alt="Restaurant Image" class="w-full h-48 object-cover">
                                <div class="p-6">
                                    <h3 class="text-xl font-semibold text-gray-800 hover:text-yellow-500 transition duration-300">${restaurant.name}</h3>
                                    <p class="text-gray-600 mt-3">${restaurant.description || 'No description available'}</p>
                                    <div class="mt-4 flex justify-between items-center">
                                        <span class="text-sm text-gray-500">${restaurant.location}</span>
                                        <a href="/restaurants/${restaurant.id}" class="text-blue-600 hover:text-yellow-500 transition duration-300">View Details</a>
                                    </div>
                                </div>
                            `;
                            restaurantList.appendChild(restaurantCard);
                        });
                    } else {
                        restaurantList.innerHTML = '<p class="text-gray-600 text-center">No restaurants available at the moment.</p>';
                    }
                })
                .catch(error => console.error('Error fetching restaurants:', error));
        });
    </script>

</body>

</html>
