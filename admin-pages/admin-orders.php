<?php
session_start();
include __DIR__ . '/../includes/db.php';
include __DIR__ . '/../includes/functions.php';

// Ensure admin
if (!isAdmin()) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Fetch orders
$sql = "SELECT o.*, u.name as user_name 
        FROM orders o 
        LEFT JOIN users u ON o.user_id = u.id 
        ORDER BY o.created_at DESC";

$result = $conn->query($sql);
$orders = [];

while ($row = $result->fetch_assoc()) {
    // Map DB fields to JS-friendly keys
    $orders[] = [
        'order_id' => (int)$row['id'],
        'user_name' => $row['user_name'] ?? 'Guest',
        'total' => (float)$row['total'],
        'status' => $row['status'],
        'delivery_type' => $row['delivery_type'],
        'payment_method' => $row['payment_method'],
        'items' => [] // optionally fetch order items here if needed
    ];
}

header('Content-Type: application/json');
echo json_encode($orders);
