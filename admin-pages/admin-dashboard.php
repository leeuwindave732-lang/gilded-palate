<?php
if (session_status() === PHP_SESSION_NONE) session_start();

include __DIR__ . '/../includes/db.php';
include __DIR__ . '/../includes/functions.php';

// Only allow admin users
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: admin-login.php'); // Redirect to admin login if not admin
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Gilded Palate</title>
    <link rel="stylesheet" href="../Assets/CSS/admin.css">
</head>
<body>
    <!-- HEADER -->
    <header class="admin-header">
        <h1>Admin Dashboard</h1>
        <div>
            <span class="hamburger">&#9776;</span>
            <nav class="nav-links">
                <a href="#" class="tab-btn active" data-tab="ordersTab">Orders</a>
                <a href="#" class="tab-btn" data-tab="productsTab">Products</a>
                <a href="../logout.php" class="logout-btn">Logout</a>
            </nav>
        </div>
    </header>

    <!-- MAIN -->
    <main class="admin-container">
        <!-- ORDERS TAB -->
        <div id="ordersTab" class="tab-content">
            <h2>Orders</h2>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>User</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Delivery Type</th>
                        <th>Payment Method</th>
                        <th>Items</th>
                    </tr>
                </thead>
                <tbody id="ordersBody">
                    <!-- Loaded via JS -->
                </tbody>
            </table>
        </div>

        <!-- PRODUCTS TAB -->
        <div id="productsTab" class="tab-content" style="display:none;">
            <h2>Products</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                    </tr>
                </thead>
                <tbody id="productsBody">
                    <!-- Loaded via JS -->
                </tbody>
            </table>
        </div>
    </main>

    <script src="../Assets/JS/admin.js"></script>
</body>
</html>
