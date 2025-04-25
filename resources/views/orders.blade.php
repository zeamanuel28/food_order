<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

<nav class="bg-blue-600 text-white p-4 shadow-md">
    <div class="container mx-auto flex justify-between items-center">
        <a href="/" class="text-lg font-bold hover:text-yellow-300">Food Delivery</a>
        <div class="space-x-4">
            <a href="/restaurants" class="navbar">Restaurants</a>
            <a href="/orders" class="navbar font-bold underline">Orders</a>
            <a href="/profile" class="navbar">Profile</a>
        </div>
    </div>
</nav>

<section class="container mx-auto py-8">
    <h2 class="text-3xl font-semibold mb-8 text-center text-gray-800">Your Recent Orders</h2>
    <div id="order-list" class="space-y-6">
        <!-- Orders will be injected here -->
    </div>
</section>

<footer class="bg-gray-800 text-white py-6 mt-12">
    <div class="container mx-auto text-center">
        <p>&copy; 2025 Food Delivery. All Rights Reserved.</p>
    </div>
</footer>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        fetch('/api/orders')
            .then(res => res.json())
            .then(orders => {
                const list = document.getElementById('order-list');
                list.innerHTML = '';

                if (orders.length === 0) {
                    list.innerHTML = '<p class="text-gray-600">You have no orders yet.</p>';
                    return;
                }

                orders.forEach(order => {
                    const items = order.items ? order.items.map(i => i.name).join(', ') : 'N/A';
                    const statusOptions = ['pending', 'assigned', 'delivering', 'delivered', 'cancelled']
                        .map(status => `<option value="${status}" ${status === order.status ? 'selected' : ''}>${status}</option>`)
                        .join('');

                    const orderCard = document.createElement('div');
                    orderCard.className = 'bg-white shadow-md rounded-lg p-6';
                    orderCard.id = `order-${order.id}`;
                    orderCard.innerHTML = `
                        <h3 class="text-lg font-semibold text-gray-800">Order #${order.id}</h3>
                        <p class="text-gray-700">Restaurant: ${order.restaurant?.name || 'N/A'}</p>
                        <p class="text-gray-700">Items: ${items}</p>
                        <p class="text-gray-700 mb-2">Status: 
                            <select id="status-${order.id}" class="ml-2 border rounded p-1">
                                ${statusOptions}
                            </select>
                            <button class="ml-2 bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700" onclick="updateStatus(${order.id})">Update</button>
                        </p>
                        <p class="text-gray-600 text-sm mt-2">Ordered at: ${new Date(order.created_at).toLocaleString()}</p>
                    `;
                    list.appendChild(orderCard);
                });
            })
            .catch(err => {
                document.getElementById('order-list').innerHTML = `<p class="text-red-600">Failed to load orders.</p>`;
                console.error(err);
            });
    });

    function updateStatus(orderId) {
        const selectedStatus = document.getElementById(`status-${orderId}`).value;
        fetch(`/api/orders/${orderId}/status`, {
    method: 'PUT',  // Make sure it's PUT
    headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${localStorage.getItem('token')}`
    },
    body: JSON.stringify({ status: selectedStatus })
})
.then(res => {
    if (!res.ok) {
        console.error(`Error: ${res.status} - ${res.statusText}`);
        throw new Error('Failed to update order status');
    }
    return res.json();
})
.then(data => {
    alert(`Order #${data.id} status updated to "${data.status}"`);
})
.catch(err => {
    console.error('Error updating order status:', err);
    alert('Failed to update order status. Please try again.');
});

    }
</script>

</body>
</html>
