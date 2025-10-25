<?php 
include '../includes/db.php'; 
include '../includes/functions.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Products - Gilded Palate</title>
  <link rel="stylesheet" href="../Assets/CSS/styles.css">
  <link rel="stylesheet" href="../Assets/CSS/responsive.css">
  
  <!-- ✅ Preload optimization -->
  <link rel="preconnect" href="https://picsum.photos">

  <style>
    /* Smooth page fade-in */
    body {
      opacity: 0;
      transition: opacity 0.5s ease;
    }
    body.loaded {
      opacity: 1;
    }
  </style>
</head>
<body>
  <?php include '../includes/header.php'; ?>

  <main>
    <h1 class="page-title">Our Signature Dishes</h1>

    <?php
    // Fetch all unique categories
    $categoryQuery = $conn->query("SELECT DISTINCT category FROM products");
    $categories = $categoryQuery->fetch_all(MYSQLI_ASSOC);

    if ($categories) {
        echo "<div class='category-filters'>";
        echo "<button class='filter-btn active' data-category='All'>All</button>";
        foreach ($categories as $cat) {
            echo "<button class='filter-btn' data-category='" . htmlspecialchars($cat['category']) . "'>" . htmlspecialchars($cat['category']) . "</button>";
        }
        echo "</div>";
    }

    // Fetch all products
    $productQuery = $conn->query("SELECT * FROM products ORDER BY category, name");
    if ($productQuery->num_rows > 0) {
        echo "<div class='products-grid'>";
        while ($product = $productQuery->fetch_assoc()) {
            $productId = $product['id'];
            $name = htmlspecialchars($product['name']);
            $price = number_format($product['price'], 2);
            $image = htmlspecialchars($product['image']);
            $category = htmlspecialchars($product['category']);
            
            echo "<div class='product-card' data-category='$category'>";
            echo "<img src='$image' alt='$name' loading='lazy'>";
            echo "<h3>$name</h3>";
            echo "<p class='price'>\$$price</p>";
            
            if (isLoggedIn()) {
                echo "<a href='../api/add-to-cart.php?product_id=$productId' class='btn'>Add to Cart</a>";
            } else {
                echo "<p class='login-prompt'><em>Please <a href='login.php'>login</a> to add items to your cart.</em></p>";
            }
            
            echo "</div>";
        }
        echo "</div>";
    } else {
        echo "<p>No products found.</p>";
    }
    ?>
  </main>

  <?php include '../includes/footer.php'; ?>

  <!-- ✅ External JS for better performance -->
  <script defer src="../Assets/JS/app.js"></script>
  <script defer src="../Assets/JS/products.js"></script>

</body>
</html>

