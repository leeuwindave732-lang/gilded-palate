<?php
session_start();
include '../includes/db.php';
include '../includes/functions.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['success'=>false,'message'=>'Please log in first.']);
    exit;
}

// Accept POST (form) or GET (AJAX)
$productId = $_POST['product_id'] ?? $_GET['product_id'] ?? 0;
$productId = intval($productId);

if ($productId <= 0) {
    echo json_encode(['success'=>false,'message'=>'Invalid product ID.']);
    exit;
}

$userId = $_SESSION['user_id'];

// Check if item already in cart
$stmt = $conn->prepare("SELECT id, quantity FROM cart WHERE user_id=? AND product_id=?");
$stmt->bind_param("ii", $userId, $productId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $newQty = $row['quantity'] + 1;
    $updateStmt = $conn->prepare("UPDATE cart SET quantity=? WHERE id=?");
    $updateStmt->bind_param("ii", $newQty, $row['id']);
    $updateStmt->execute();
} else {
    $insertStmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, 1)");
    $insertStmt->bind_param("ii", $userId, $productId);
    $insertStmt->execute();
}

// If request is AJAX (expects JSON), return JSON
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    echo json_encode(['success'=>true,'message'=>'Added to cart']);
} else {
    // Redirect form submission
    header("Location: ../pages/cart.php");
}
exit;
