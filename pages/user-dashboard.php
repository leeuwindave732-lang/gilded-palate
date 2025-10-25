<?php
include '../includes/db.php';
include '../includes/functions.php';

session_start();
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
    <link rel="stylesheet" href="../Assets/CSS/styles.css">
    <link rel="stylesheet" href="../Assets/CSS/responsive.css">
    <title>User Dashboard - Gilded Palate</title>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <main>
        <h1>Your Orders</h1>
        <table border="1" cellspacing="0" cellpadding="8">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Delivery Type</th>
                    <th>Payment Method</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch all orders for the user
                $orderStmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
                $orderStmt->bind_param("i", $userId);
                $orderStmt->execute();
                $orderResult = $orderStmt->get_result();

                while ($order = $orderResult->fetch_assoc()) {
                    // Fetch items for this order
                    $itemsStmt = $conn->prepare("
                        SELECT p.name, oi.quantity
                        FROM order_items oi
                        JOIN products p ON oi.product_id = p.id
                        WHERE oi.order_id = ?
                    ");
                    $itemsStmt->bind_param("i", $order['id']);
                    $itemsStmt->execute();
                    $itemsResult = $itemsStmt->get_result();

                    $itemsList = [];
                    while ($item = $itemsResult->fetch_assoc()) {
                        $itemsList[] = $item['quantity'] . " x " . $item['name'];
                    }

                    echo "<tr>
                        <td>{$order['id']}</td>
                        <td>" . implode('<br>', $itemsList) . "</td>
                        <td>₱{$order['total']}</td>
                        <td>{$order['status']}</td>
                        <td>{$order['delivery_type']}</td>
                        <td>{$order['payment_method']}</td>
                        <td>{$order['created_at']}</td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </main>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
