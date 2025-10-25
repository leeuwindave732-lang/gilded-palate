<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<header>
  <div class="logo">
    <a href="../pages/index.php" class="logo-link">
      <img src="../Assets/Images/logo.png" alt="Gilded Palate Logo">
      <span>Gilded Palate</span>
    </a>
  </div>

  <nav>
    <ul>
      <li><a href="../pages/index.php">Home</a></li>
      <li><a href="../pages/products.php">Products</a></li>

      <?php if (isset($_SESSION['user_id'])): ?>
        <li><a href="../pages/user-dashboard.php">My Account</a></li>
        <li><a href="../pages/cart.php">Cart</a></li>
        <li><a href="../pages/logout.php" class="logout-btn">Logout</a></li>
      <?php else: ?>
        <li><a href="../pages/login.php" class="login-btn">Login</a></li>
      <?php endif; ?>
    </ul>
  </nav>
</header>
