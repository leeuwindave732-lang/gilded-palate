<?php
// api/process-order.php
// Process checkout form, insert order + items, clear cart, redirect to thankyou page.

include '../includes/db.php';
include '../includes/functions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ensure user is logged in
if (!isLoggedIn()) {
    // Not logged in: redirect to login page
    header('Location: ../pages/login.php');
    exit;
}

$userId = $_SESSION['user_id'] ?? null;
if (!$userId) {
    $_SESSION['checkout_error'] = 'Invalid user session.';
    header('Location: ../pages/cart.php');
    exit;
}

// Validate request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['checkout_error'] = 'Invalid request method.';
    header('Location: ../pages/cart.php');
    exit;
}

// Basic sanitization
$delivery_type = isset($_POST['delivery_type']) && $_POST['delivery_type'] === 'Delivery' ? 'Delivery' : 'Pickup';
$address = $delivery_type === 'Delivery' ? trim($_POST['address'] ?? '') : '';
$payment_method = trim($_POST['payment_method'] ?? 'Cash');

// Fetch cart items
$stmt = $conn->prepare("
    SELECT c.product_id, c.quantity, p.price
    FROM cart c
    JOIN products p ON c.product_id = p.id
    WHERE c.user_id = ?
");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    $_SESSION['checkout_error'] = 'Failed to read cart.';
    header('Location: ../pages/cart.php');
    exit;
}

if ($result->num_rows === 0) {
    $_SESSION['checkout_error'] = 'Your cart is empty.';
    header('Location: ../pages/cart.php');
    exit;
}

// Calculate total and collect items
$total = 0.0;
$cartItems = [];
while ($row = $result->fetch_assoc()) {
    $total += floatval($row['price']) * intval($row['quantity']);
    $cartItems[] = [
        'product_id' => (int)$row['product_id'],
        'quantity'   => (int)$row['quantity'],
        'price'      => floatval($row['price'])
    ];
}

// Begin transaction to keep DB consistent
$conn->begin_transaction();

try {
    // Insert order
    $stmtOrder = $conn->prepare("INSERT INTO orders (user_id, total, delivery_address, payment_method, created_at) VALUES (?, ?, ?, ?, NOW())");
    if (!$stmtOrder) throw new Exception('Prepare order failed: ' . $conn->error);
    $stmtOrder->bind_param("idss", $userId, $total, $address, $payment_method);
    if (!$stmtOrder->execute()) throw new Exception('Insert order failed: ' . $stmtOrder->error);
    $orderId = $stmtOrder->insert_id;

    // Insert order items
    $stmtItem = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    if (!$stmtItem) throw new Exception('Prepare order_items failed: ' . $conn->error);

    foreach ($cartItems as $it) {
        $stmtItem->bind_param("iiid", $orderId, $it['product_id'], $it['quantity'], $it['price']);
        if (!$stmtItem->execute()) throw new Exception('Insert order_item failed: ' . $stmtItem->error);
    }

    // Clear cart
    $stmtClear = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
    if (!$stmtClear) throw new Exception('Prepare clear cart failed: ' . $conn->error);
    $stmtClear->bind_param("i", $userId);
    if (!$stmtClear->execute()) throw new Exception('Clear cart failed: ' . $stmtClear->error);

    // Commit transaction
    $conn->commit();

    // Redirect to thank-you page (pass order id)
    header('Location: ../pages/thankyou.php?order_id=' . intval($orderId));
    exit;

} catch (Exception $e) {
    // Rollback and set an error for the user
    $conn->rollback();
    error_log('Checkout error: ' . $e->getMessage()); // log the detailed error
    $_SESSION['checkout_error'] = 'There was a problem placing your order. Please try again.';
    header('Location: ../pages/checkout.php');
    exit;
}
