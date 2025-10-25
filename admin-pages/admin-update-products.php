<?php
include __DIR__ . '/../includes/db.php';
include __DIR__ . '/../includes/functions.php';

if (session_status() === PHP_SESSION_NONE) session_start();

// Only admin
if (!isAdmin()) {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = intval($_POST['product_id']);
    $stock = intval($_POST['stock']);

    $stmt = $conn->prepare("UPDATE products SET stock = ? WHERE id = ?");
    $stmt->bind_param("ii", $stock, $product_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => "Product #$product_id stock updated to $stock"]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update stock']);
    }
} else {
    echo json_encode(['error' => 'Invalid request']);
}
?>
