// products.js
document.addEventListener("DOMContentLoaded", () => {
  // Fade-in animation after page load
  document.body.classList.add("loaded");

  const filterButtons = document.querySelectorAll(".filter-btn");
  const productCards = document.querySelectorAll(".product-card");

  if (!filterButtons.length || !productCards.length) return;

  filterButtons.forEach((btn) => {
    btn.addEventListener("click", () => {
      // Update active state
      filterButtons.forEach((b) => b.classList.remove("active"));
      btn.classList.add("active");

      const category = btn.dataset.category;

      productCards.forEach((card) => {
        // Smooth fade transition
        card.style.opacity = 0;
        setTimeout(() => {
          if (category === "All" || card.dataset.category === category) {
            card.style.display = "block";
          } else {
            card.style.display = "none";
          }
          card.style.opacity = 1;
        }, 200);
      });
    });
  });
});
document.querySelectorAll(".add-to-cart-btn").forEach(btn => {
  btn.addEventListener("click", async (e) => {
    e.preventDefault();
    const productId = btn.dataset.productId;

    try {
      const res = await fetch(`../api/add-to-cart.php?product_id=${productId}`);
      const data = await res.json();
      if (data.success) {
        alert("Added to cart!");
      } else {
        alert(data.message);
      }
    } catch (err) {
      console.error("Error adding to cart:", err);
      alert("Failed to add to cart.");
    }
  });
});
