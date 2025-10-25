<?php
session_start();
include '../includes/db.php';
include '../includes/functions.php';

// Ensure user is logged in
if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You - Gilded Palate</title>
    <link rel="stylesheet" href="../Assets/CSS/styles.css">
    <link rel="stylesheet" href="../Assets/CSS/responsive.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <main class="thankyou-page">
        <div class="thankyou-card">
            <h1>Thank You!</h1>
            <p>Your order has been placed successfully.</p>
            <a href="../pages/products.php" class="btn-home">Continue Shopping</a>
        </div>
    </main>

    <?php include '../includes/footer.php'; ?>
</body>
</html>

