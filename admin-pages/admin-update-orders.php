<?php
session_start();
include __DIR__ . '/../includes/db.php';
include __DIR__ . '/../includes/functions.php';

header('Content-Type: application/json');

// Ensure admin
if (!isAdmin()) {
    http_response_code(403);
    echo json_encode(['message' => 'Unauthorized']);
    exit;
}

// Validate input
$order_id = isset($_POST['order_id']) ? (int)$_POST['order_id'] : 0;
$status = isset($_POST['status']) ? $_POST['status'] : '';

$valid_statuses = ['pending','processing','shipped','delivered'];

if ($order_id <= 0 || !in_array($status, $valid_statuses)) {
    echo json_encode(['message' => 'Invalid data']);
    exit;
}

// Update order
$stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
$stmt->bind_param("si", $status, $order_id);

if ($stmt->execute()) {
    echo json_encode(['message' => "Order #$order_id status updated to $status"]);
} else {
    echo json_encode(['message' => 'Failed to update order']);
}

