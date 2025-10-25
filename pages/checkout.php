<?php 
session_start();
include '../includes/db.php'; 
include '../includes/functions.php'; 

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user_id'];

// Optional: Fetch cart items to show on checkout
$stmt = $conn->prepare("
    SELECT p.name, p.price, c.quantity
    FROM cart c
    JOIN products p ON c.product_id = p.id
    WHERE c.user_id = ?
");
$stmt->bind_param("i", $userId);
$stmt->execute();
$cartItems = $stmt->get_result();
$total = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Checkout - Gilded Palate</title>
<link rel="stylesheet" href="../Assets/CSS/styles.css">
<link rel="stylesheet" href="../Assets/CSS/responsive.css">
</head>
<body>
<?php include '../includes/header.php'; ?>

<main class="checkout-page">
<div class="checkout-card">
    <h1>Checkout</h1>

    <?php if ($cartItems->num_rows > 0): ?>
        <div class="checkout-items">
            <?php while ($item = $cartItems->fetch_assoc()): 
                $itemTotal = $item['price'] * $item['quantity'];
                $total += $itemTotal;
            ?>
            <div class="checkout-item">
                <span><?= htmlspecialchars($item['name']) ?> x <?= $item['quantity'] ?></span>
                <span>$<?= number_format($itemTotal, 2) ?></span>
            </div>
            <?php endwhile; ?>
        </div>
        <div class="checkout-total">
            <strong>Total: $<?= number_format($total, 2) ?></strong>
        </div>

        <form id="checkout-form" action="../api/process-order.php" method="POST">
            <label>Choose order type:</label>
            <div class="radio-group">
                <label><input type="radio" name="delivery_type" value="Pickup" checked> Pickup</label>
                <label><input type="radio" name="delivery_type" value="Delivery"> Delivery</label>
            </div>

            <label>Delivery Address:</label>
            <textarea name="address" placeholder="Enter your address if Delivery selected"></textarea>

            <label>Payment Method:</label>
            <select name="payment_method" required>
                <option value="Cash">Cash</option>
                <option value="Card">Card</option>
                <option value="Online">Online Payment</option>
            </select>

            <button type="submit" class="btn">Place Order</button>
        </form>
    <?php else: ?>
        <p>Your cart is empty. <a href="index.php">Go back to menu</a></p>
    <?php endif; ?>
</div>
</main>

<?php include '../includes/footer.php'; ?>
<script src="../Assets/JS/app.js"></script>
</body>
</html>
