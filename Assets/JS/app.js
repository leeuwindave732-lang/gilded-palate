// Cart functionality
let cart = JSON.parse(localStorage.getItem('cart')) || [];
function addToCart(productId, name, price) {
    cart.push({ id: productId, name, price });
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartUI();
}
function updateCartUI() {
    // Update cart display in header
    document.getElementById('cart-count').textContent = cart.length;
}
// AJAX for dynamic content (e.g., fetch products)
fetch('api/get-products.php')
    .then(res => res.json())
    .then(data => {
        // Render products
    });

document.addEventListener('DOMContentLoaded', () => {
  // Scroll-triggered navbar shrink
  window.addEventListener('scroll', () => {
    const header = document.querySelector('header');
    if (window.scrollY > 50) {
      header.classList.add('scrolled');
    } else {
      header.classList.remove('scrolled');
    }
  });

  // Parallax for hero
  window.addEventListener('scroll', () => {
    const scrolled = window.pageYOffset;
    const heroImg = document.querySelector('.hero-img');
    if (heroImg) heroImg.style.transform = `translateY(${scrolled * 0.3}px) scale(${1 + scrolled * 0.0005})`;
  });

  // Loading screen
  setTimeout(() => {
    document.querySelector('.loading-screen').style.display = 'none';
  }, 3000);
});

// Save this in ../Assets/JS/app.js
window.addEventListener('scroll', () => {
  const header = document.querySelector('header');
  if (window.scrollY > 20) {
    header.classList.add('scrolled');
  } else {
    header.classList.remove('scrolled');
  }
});
