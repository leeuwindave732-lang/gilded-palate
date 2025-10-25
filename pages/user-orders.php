<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include __DIR__ . '/../includes/db.php';
include __DIR__ . '/../includes/functions.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}
$userId = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Gilded Palate</title>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <main>
        <h1>Your Orders</h1>
        <table>
            <thead>
                <tr><th>Order ID</th><th>Total</th><th>Status</th><th>Date</th></tr>
            </thead>
            <tbody id="userOrdersBody">
                <!-- Orders will be loaded via JS -->
            </tbody>
        </table>
    </main>

    <script>
    const userId = <?= $userId ?>;

    async function loadUserOrders() {
        try {
            const res = await fetch(`../user-orders.php?user_id=${userId}`);
            const orders = await res.json();
            const tbody = document.getElementById('userOrdersBody');
            tbody.innerHTML = '';

            orders.forEach(order => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${order.id}</td>
                    <td>₱${order.total}</td>
                    <td>${order.status}</td>
                    <td>${order.created_at}</td>
                `;
                tbody.appendChild(row);
            });
        } catch(err) {
            console.error('Failed to load user orders:', err);
        }
    }

    // Initial load
    loadUserOrders();

    // Auto-refresh every 5 seconds
    setInterval(loadUserOrders, 5000);
    </script>
</body>
</html>
