<?php 
include '../includes/db.php'; 
include '../includes/functions.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Gilded Palate</title>
<link rel="stylesheet" href="../Assets/CSS/styles.css">
<link rel="stylesheet" href="../Assets/CSS/responsive.css">
</head>
<body>
<?php include '../includes/header.php'; ?>

<main>

  <!-- Hero Section -->
  <section class="hero">
    <div class="hero-overlay"></div>
    <div class="hero-content">
      <img src="../Assets/Images/logo.png" alt="Gilded Palate Logo" class="hero-logo">
      <div class="hero-text">
        <h1>Welcome to <span>Gilded Palate</span></h1>
        <p>Your taste, our passion.</p>
        <a href="products.php" class="hero-btn">Explore Menu</a>
      </div>
    </div>
  </section>

  <!-- Featured Products -->
  <section class="featured-products">
    <h2>Featured Dishes</h2>
    <div class="products-grid">
      <?php
      $query = $conn->query("SELECT * FROM products ORDER BY RAND() LIMIT 8");

      if ($query && $query->num_rows > 0) {
          while ($product = $query->fetch_assoc()):
              $id = (int)$product['id'];
              $name = htmlspecialchars($product['name']);
              $price = number_format($product['price'], 2);
              $image = htmlspecialchars($product['image']);
              $category = htmlspecialchars($product['category']);
      ?>
      <div class="product-card" data-category="<?= $category ?>">
          <img src="<?= $image ?>" alt="<?= $name ?>">
          <h3><?= $name ?></h3>
          <p>$<?= $price ?></p>
          <?php if (isLoggedIn()): ?>
            <form method="POST" action="../api/add-to-cart.php">
                <input type="hidden" name="product_id" value="<?= $id ?>">
                <button type="submit" class="btn add-to-cart-btn" data-product-id="<?= $id ?>">Add to Cart</button>
            </form>
          <?php else: ?>
            <p><em><a href="login.php">Login</a> to add to cart</em></p>
          <?php endif; ?>
      </div>
      <?php
          endwhile;
      } else {
          echo "<p>No featured products available at the moment.</p>";
      }
      ?>
    </div>
  </section>

</main>

<?php include '../includes/footer.php'; ?>
<script src="../Assets/JS/product.js"></script>
</body>
</html>
