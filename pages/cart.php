<?php 
session_start();
include '../includes/db.php'; 
include '../includes/functions.php'; 

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user_id'];

// Fetch cart items
$stmt = $conn->prepare("
    SELECT p.id, p.name, p.price, p.image, c.quantity
    FROM cart c
    JOIN products p ON c.product_id = p.id
    WHERE c.user_id = ?
");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$total = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cart - Gilded Palate</title>
<link rel="stylesheet" href="../Assets/CSS/styles.css">
<link rel="stylesheet" href="../Assets/CSS/responsive.css">
</head>
<body>
<?php include '../includes/header.php'; ?>

<main class="cart-page">
<div class="cart-container">
    <h1>Your Cart</h1>

    <?php if ($result->num_rows > 0): ?>
        <div class="cart-items-grid">
            <?php while ($row = $result->fetch_assoc()): 
                $itemTotal = $row['price'] * $row['quantity'];
                $total += $itemTotal;
            ?>
            <div class="cart-item">
                <div class="thumb">
                    <img src="<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>" loading="lazy">
                </div>
                <div class="cart-item-details">
                    <h3><?= htmlspecialchars($row['name']) ?></h3>
                    <div class="meta">Quantity: <?= $row['quantity'] ?></div>
                    <div class="meta">Price: $<?= number_format($itemTotal, 2) ?></div>
                    <div class="cart-item-controls">
                        <a href="../api/remove-from-cart.php?product_id=<?= $row['id'] ?>" class="remove-btn">Remove</a>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>

        <div class="cart-summary">
            <div class="cart-total">Total: $<?= number_format($total, 2) ?></div>
            <!-- Updated form: full path to checkout.php -->
            <form action="../pages/checkout.php" method="POST" id="proceedForm">
                <button type="submit" class="proceed-btn" id="proceedBtn">
                    Proceed to Checkout
                </button>
            </form>
        </div>
    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>
</div>
</main>

<?php include '../includes/footer.php'; ?>
<script defer src="../Assets/JS/cart.js"></script>
</body>
</html>
